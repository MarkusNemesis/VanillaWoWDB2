<?php

require_once('includes/allspells.php');
require_once('includes/allquests.php');
require_once('includes/allnpcs.php');
require_once('includes/allcomments.php');

// Настраиваем Smarty ;)
$smarty->config_load($conf_file, 'npc');

$id = intval($podrazdel);

$cache_key = cache_key($id);

if(!$npc = load_cache(1, $cache_key))
{
	unset($npc);

	// Ищем NPC:
	$npc = array();
	$row = $DB->selectRow('
		SELECT
			?#, c.entry, c.name,
			{
				l.name_loc'.$_SESSION['locale'].' as `name_loc`,
				l.subname_loc'.$_SESSION['locale'].' as `subname_loc`,
				?,
			}
			f.name_loc'.$_SESSION['locale'].' as `faction-name`, ft.factionID as `factionID`
		FROM ?_factiontemplate ft, ?_factions f, creature_template c
		{
			LEFT JOIN (locales_creature l)
			ON l.entry = c.entry AND ?
		}
		WHERE
			c.entry = ?
			AND ft.factiontemplateID = c.faction_A
			AND f.factionID = ft.factionID
		LIMIT 1
			',
		$npc_cols[1],
		($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
		$id
	);

	if($row)
	{
		$npc = $row;
		$npc['name'] = localizedName($row);
		$npc['subname'] = localizedName($row, 'subname');
		if($npc['rank'] == 3)
		{
			$npc['minlevel'] = '??';
			$npc['maxlevel'] = '??';
		}
        $npc['mindmg'] = ($row['mindmg'] /* + $row['attackpower'] */) * $row['dmg_multiplier'];
        $npc['maxdmg'] = ($row['maxdmg'] /* + $row['attackpower'] */) * $row['dmg_multiplier'];
		
		$toDiv = array('minhealth', 'maxmana', 'minmana', 'maxhealth', 'armor', 'mindmg', 'maxdmg');
		// Разделяем на тысячи (ххххххххх => ххх,ххх,ххх)
		foreach($toDiv as $e)
			$npc[$e] = number_format($npc[$e]);

		$npc['rank'] = $smarty->get_config_vars('rank'.$npc['rank']);
		// faction_A = faction_H
		$npc['faction_num'] = $row['factionID'];
		$npc['faction'] = $row['faction-name'];
		// Деньги
		$money = ($row['mingold']+$row['maxgold']) / 2;
		$npc = array_merge($npc, money2coins($money));

		// Дроп
		$lootid=$row['lootid'];
		// Используемые спеллы
		$npc['ablities'] = array();
		$tmp = array();
		for($j=0;$j<=4;++$j)
		{
			if($row['spell'.$j] && !in_array($row['spell'.$j], $tmp))
			{
				$tmp[] = $row['spell'.$j];
				if($data = spellinfo($row['spell'.$j], 0))
				{
					if($data['name'])
						$npc['abilities'][] = $data;
				}
			}
		}
		for($j=1;$j<4;$j++)
		{
			$tmp2 = $DB->select('
				SELECT action?d_param1
				FROM creature_ai_scripts
				WHERE
					creature_id=?d
					AND action?d_type=11
				',
				$j,
				$npc['entry'],
				$j
			);
			if($tmp2)
				foreach($tmp2 as $i=>$tmp3)
					if(!in_array($tmp2[$i]['action'.$j.'_param1'], $tmp))
					{
						$tmp[] = $tmp2[$i]['action'.$j.'_param1'];
						if($data = spellinfo($tmp2[$i]['action'.$j.'_param1'], 0))
						{
							if($data['name'])
								$npc['abilities'][] = $data;
						}
					}
		}
		if(!$npc['ablities'])
			unset($npc['ablities']);

		// Обучает:
		// Если это пет со способностью:
		/* // Временно закомментировано
		$row = $DB->selectRow('
			SELECT Spell1, Spell2, Spell3, Spell4
			FROM petcreateinfo_spell
			WHERE
				entry=?d
			',
			$npc['entry']
		);
		if($row)
		{
			$npc['teaches'] = array();
			for($j=1;$j<=4;$j++)
				if($row['Spell'.$j])
					for($k=1;$k<=3;$k++)
					{
						$spellrow = $DB->selectRow('
							SELECT ?#, spellID
							FROM ?_spell, ?_spellicons
							WHERE
								spellID=(SELECT effect'.$k.'triggerspell FROM ?_spell WHERE spellID=?d AND (effect'.$k.'id IN (36,57)))
								AND id=spellicon
							LIMIT 1
							',
							$spell_cols[2],
							$row['Spell'.$j]
						);
						if($spellrow)
						{
							$num = count($npc['teaches']);
							$npc['teaches'][$num] = array();
							$npc['teaches'][$num] = spellinfo2($spellrow);
						}
					}
		}
		unset ($row);*/

		// Если это просто тренер
		$teachspells = $DB->select('
			SELECT ?#, spellID
			FROM npc_trainer, ?_spell, ?_spellicons
			WHERE
				entry=?d
				AND spellID=Spell
				AND id=spellicon
			',
			$spell_cols[2],
			$npc['entry']
		);
		if($teachspells)
		{
			if(!(IsSet($npc['teaches'])))
				$npc['teaches'] = array();
			foreach($teachspells as $teachspell)
			{
						$num = count($npc['teaches']);
						$npc['teaches'][$num] = array();
						$npc['teaches'][$num] = spellinfo2($teachspell);
			}
		}
		unset ($teachspells);

		// Продает:
		$rows_s = $DB->select('
			SELECT ?#, i.entry, i.maxcount, n.`maxcount` as `drop-maxcount`
				{, l.name_loc?d AS `name_loc`}
			FROM npc_vendor n, ?_icons, item_template i
				{LEFT JOIN (locales_item l) ON l.entry=i.entry AND ?d}
			WHERE
				n.entry=?
				AND i.entry=n.item
				AND id=i.displayid
			',
			$item_cols[2],
			($_SESSION['locale'])? $_SESSION['locale']: DBSIMPLE_SKIP,
			($_SESSION['locale'])? 1: DBSIMPLE_SKIP,
			$id
		);
		if($rows_s)
		{
			$rows_s = sanitiseitemrows($rows_s);
			$npc['sells'] = array();
			foreach($rows_s as $numRow=>$row)
			{
				$npc['sells'][$numRow] = array();
				$npc['sells'][$numRow] = iteminfo2($row);
				$npc['sells'][$numRow]['maxcount'] = $row['drop-maxcount'];
				$npc['sells'][$numRow]['cost'] = array();
				if($row['BuyPrice']>0)
					$npc['sells'][$numRow]['cost']['money'] = $row['BuyPrice'];
			}
			unset ($row);
			unset ($numRow);
			unset ($extcost);
		}
		unset ($rows_s);

		// Дроп
		if(!($npc['drop'] = loot('creature_loot_template', $lootid)))
			unset ($npc['drop']);

		// Кожа
		if(!($npc['skinning'] = loot('skinning_loot_template', $lootid)))
			unset ($npc['skinning']);

		// Воруеццо
		if(!($npc['pickpocketing'] = loot('pickpocketing_loot_template', $lootid)))
			unset ($npc['pickpocketing']);

		// Начиниают квесты...
		$rows_qs = $DB->select('
			SELECT ?#
			FROM creature_questrelation c, quest_template q
			WHERE
				c.id=?
				AND q.entry=c.quest
			',
			$quest_cols[2],
			$id
		);
		if($rows_qs)
		{
			$npc['starts'] = array();
			foreach($rows_qs as $numRow=>$row) {
				$npc['starts'][] = GetQuestInfo($row, 0xFFFFFF);
			}
		}
		unset ($rows_qs);

		// Заканчивают квесты...
		$rows_qe = $DB->select('
			SELECT ?#
			FROM creature_involvedrelation c, quest_template q
			WHERE
				c.id=?
				AND q.entry=c.quest
			',
			$quest_cols[2],
			$id
		);
		if($rows_qe)
		{
			$npc['ends'] = array();
			foreach($rows_qe as $numRow=>$row) {
				$npc['ends'][] = GetQuestInfo($row, 0xFFFFFF);
			}
		}
		unset ($rows_qe);

		// Необходимы для квеста..
		$rows_qo = $DB->select('
			SELECT ?#
			FROM quest_template
			WHERE
				ReqCreatureOrGOId1=?
				OR ReqCreatureOrGOId2=?
				OR ReqCreatureOrGOId3=?
				OR ReqCreatureOrGOId4=?
			',
			$quest_cols[2],
			$id, $id, $id, $id
		);
		if($rows_qo)
		{
			$npc['objectiveof'] = array();
			foreach($rows_qo as $numRow=>$row)
				$npc['objectiveof'][] = GetQuestInfo($row, 0xFFFFFF);
		}
		unset ($rows_qo);

		// Положения созданий божих (для героик НПС не задана карта, юзаем из нормала):
		if($normal_entry)
			// мы - героик НПС, определяем позицию по нормалу
			$npc['position'] = position($normal_entry, 'creature', 2);
		else
			// мы - нормал НПС или НПС без сложности
			$npc['position'] = position($npc['entry'], 'creature', 1);

		save_cache(1, $cache_key, $npc);
	}
}

global $page;
$page = array(
	'Mapper' => true,
	'Book' => false,
	'Title' => $npc['name'].' - '.$smarty->get_config_vars('NPCs'),
	'tab' => 0,
	'type' => 1,
	'typeid' => $npc['entry'],
	'path' => path(0, 4, $npc['family'], $npc['type'])
);

$smarty->assign('page', $page);

// Комментарии
$smarty->assign('comments', getcomments($page['type'], $page['typeid']));

// Если хоть одна информация о вещи найдена - передаём массив с информацией о вещях шаблонизатору
$smarty->assign('allitems', $allitems);
$smarty->assign('allspells', $allspells);

$smarty->assign('npc', $npc);

// Количество MySQL запросов
$smarty->assign('mysql', $DB->getStatistics());

// Запускаем шаблонизатор
$smarty->display('npc.tpl');

?>