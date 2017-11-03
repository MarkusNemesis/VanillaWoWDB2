<?php

require_once('includes/allnpcs.php');
require_once('includes/allitems.php');
require_once('includes/allquests.php');
require_once('includes/allcomments.php');
require_once('includes/allachievements.php');

$smarty->config_load($conf_file, 'faction');

// Номер фракции
$id = intval($podrazdel);

$cache_key = cache_key($id);

if(!$faction = load_cache(18, $cache_key))
{
	unset($faction);

	$row = $DB->selectRow('
			SELECT
				f1.factionID, f1.name_loc?d, f1.description1_loc?d, f1.description2_loc?d,
				f1.team, f1.side, f2.factionID AS category2, f2.name_loc?d AS `group`
			FROM ?_factions f1
			LEFT JOIN (?_factions f2) ON f1.team <> 0
			WHERE
				f1.factionID = ?d
				AND f1.team = f2.factionID
			LIMIT 1
		',
		$_SESSION['locale'],
		$_SESSION['locale'],
		$_SESSION['locale'],
		$_SESSION['locale'],
		$id
	);
	if($row)
	{
		$faction=array();
		// Номер фракции
		$faction['entry'] = $row['factionID'];
		// Название фракции
		$faction['name'] = $row['name_loc'.$_SESSION['locale']];
		// Описание фракции, из клиента:
		$faction['description1'] = $row['description1_loc'.$_SESSION['locale']];
		// Описание фракции, c wowwiki.com, находится в таблице factions.sql:
		$faction['description2'] = $row['description2_loc'.$_SESSION['locale']];
		// Команда/Группа фракции
		if($row['category2'] <> 0)
		{
			$faction['group'] = $row['group'];
			$faction['category'] = $DB->selectCell('SELECT team FROM ?_factions WHERE factionID = ?d LIMIT 1', $row['category2']);
			$faction['category2'] = $row['category2'];
		}
		// Альянс(1)/Орда(2)
		if($row['side'] <> 0)
			$faction['side'] = $row['side'];

		// Итемы с requiredreputationfaction
		$item_rows = $DB->select('
			SELECT ?#, entry
			FROM item_template i, ?_icons a
			WHERE
				i.RequiredReputationFaction=?d
				AND a.id=i.displayid
			',
			$item_cols[2],
			$id
		);
		if($item_rows)
		{
			$faction['items'] = array();
			foreach($item_rows as $i=>$row)
				$faction['items'][] = iteminfo2($row, 0);
			unset($faction['items']);
		}

		// Персонажи, состоящие во фракции
		$creature_rows = $DB->select('
			SELECT ?#, entry
			FROM creature_template, ?_factiontemplate
			WHERE
				faction_A IN (SELECT factiontemplateID FROM ?_factiontemplate WHERE factionID=?d)
				AND factiontemplateID=faction_A
			',
			$npc_cols[0],
			$id
		);
		if($creature_rows)
		{
			$faction['npcs'] = array();
			foreach($creature_rows as $i=>$row)
				$faction['npcs'][] = creatureinfo2($row);
			unset($creature_rows);
		}

		// Квесты для этой фракции
		$quests_rows = $DB->select('
			SELECT ?#
			FROM quest_template
			WHERE
				RewRepFaction1=?d
				OR RewRepFaction2=?d
				OR RewRepFaction3=?d
				OR RewRepFaction4=?d
			',
			$quest_cols[2],
			$id, $id, $id, $id
		);
		if($quests_rows)
		{
			$faction['quests'] = array();
			foreach($quests_rows as $i => $row)
				$faction['quests'][] = GetQuestInfo($row, 0xFFFFFF);
			unset($quests_rows);
		}

		// Цель критерии
		$rows = $DB->select('
				SELECT a.id, a.faction, a.name_loc?d AS name, a.description_loc?d AS description, a.category, a.points, s.iconname, z.areatableID
				FROM ?_spellicons s, ?_achievementcriteria c, ?_achievement a
				LEFT JOIN (?_zones z) ON a.map != -1 AND a.map = z.mapID
				WHERE
					a.icon = s.id
					AND a.id = c.refAchievement
					AND c.type IN (?a)
					AND c.value1 = ?d
				GROUP BY a.id
				ORDER BY a.name_loc?d
			',
			$_SESSION['locale'],
			$_SESSION['locale'],
			array(ACHIEVEMENT_CRITERIA_TYPE_GAIN_REPUTATION),
			$faction['entry'],
			$_SESSION['locale']
		);
		if($rows)
		{
			$faction['criteria_of'] = array();
			foreach($rows as $row)
			{
				allachievementsinfo2($row['id']);
				$faction['criteria_of'][] = achievementinfo2($row);
			}
		}

		// Faction cache
		save_cache(18, $cache_key, $faction);
	}
}

$page = array(
	'Mapper' => false,
	'Book' => false,
	'Title' => $faction['name'].' - '.$smarty->get_config_vars('Factions'),
	'tab' => 0,
	'type' => 8,
	'typeid' => $faction['entry'],
	// path will be 0,8,... when zones are implemented
	'path' => path(0, 7, $faction['category2'], $faction['category'])
);
$smarty->assign('page', $page);

// Комментарии
$smarty->assign('comments', getcomments($page['type'], $page['typeid']));

// Данные о квесте
$smarty->assign('faction', $faction);
// Если хоть одна информация о вещи найдена - передаём массив с информацией о вещях шаблонизатору
$smarty->assign('allitems', $allitems);
$smarty->assign('allachievements', $allachievements);

// Количество MySQL запросов
$smarty->assign('mysql', $DB->getStatistics());
// Загружаем страницу
$smarty->display('faction.tpl');
?>