<?php

// Необходима функция questinfo
require_once('includes/allquests.php');
require_once('includes/allobjects.php');
require_once('includes/allnpcs.php');
require_once('includes/allitems.php');
require_once('includes/allcomments.php');
require_once('includes/allcommentsalkz.php');

$smarty->config_load($conf_file, 'quest');

// Номер квеста
$id = intval($podrazdel);

$cache_key = cache_key($id);

if(!$quest = load_cache(10, $cache_key))
{
	unset($quest);

	// Основная инфа
	$quest = GetDBQuestInfo($id, 0xFFFFFF);


	/*              ЦЕПОЧКА КВЕСТОВ              */
	// Добавляем сам квест в цепочку
	$quest['series'] = array(
		array(
			'entry' => $quest['entry'],
			'Title' => $quest['Title'],
			'NextQuestInChain' => $quest['NextQuestInChain']
			)
	);
	// Квесты в цепочке до этого квеста
	$tmp = $quest['series'][0];
	while($tmp)
	{
		$tmp = $DB->selectRow('
			SELECT q.entry, q.Title
				{, l.Title_loc?d as Title_loc}
			FROM quest_template q
				{LEFT JOIN (locales_quest l) ON l.entry=q.entry AND ?d}
			WHERE q.NextQuestInChain=?d
			LIMIT 1
			',
			($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
			($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
			$quest['series'][0]['entry']
		);
		if($tmp)
		{
			$tmp['Title'] = localizedName($tmp, 'Title');
			array_unshift($quest['series'], $tmp);
		}
	}
	// Квесты в цепочке после этого квеста
	$tmp = end($quest['series']);
	while($tmp)
	{
		$tmp = $DB->selectRow('
			SELECT q.entry, q.Title, q.NextQuestInChain
				{, l.Title_loc?d as Title_loc}
			FROM quest_template q
				{LEFT JOIN (locales_quest l) ON l.entry=q.entry AND ?}
			WHERE q.entry=?d
			LIMIT 1
			',
			($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
			($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
			$quest['series'][count($quest['series'])-1]['NextQuestInChain']
		);
		if($tmp)
		{
			$tmp['Title'] = localizedName($tmp, 'Title');
			array_push($quest['series'], $tmp);
		}
	}
	unset($tmp);
	if(count($quest['series'])<=1)
		unset($quest['series']);


	/*              ДРУГИЕ КВЕСТЫ              */
	// (после их нахождения проверяем их тайтлы на наличие локализации)


	// Квесты, которые необходимо выполнить, что бы получить этот квест
	if(!$quest['req'] = $DB->select('
				SELECT q.entry, q.Title, q.NextQuestInChain
					{, l.Title_loc?d as Title_loc}
				FROM quest_template q
					{LEFT JOIN (locales_quest l) ON l.entry=q.entry AND ?}
				WHERE
					(q.NextQuestID=?d AND q.ExclusiveGroup<0)
					OR (q.entry=?d AND q.NextQuestInChain<>?d)
				LIMIT 20',
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP, ($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$quest['entry'], $quest['PrevQuestID'], $quest['entry']
				)
		)
			unset($quest['req']);
		else
			$questItems[] = 'req';

	// Квесты, которые становятся доступными, только после того как выполнен этот квест (необязательно только он)
	if(!$quest['open'] = $DB->select('
				SELECT q.entry, q.Title
					{, l.Title_loc?d as Title_loc}
				FROM quest_template q
					{LEFT JOIN (locales_quest l) ON l.entry=q.entry AND ?}
				WHERE
					(q.PrevQuestID=?d AND q.entry<>?d)
					OR q.entry=?d
				LIMIT 20',
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP, ($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$quest['entry'], $quest['NextQuestInChain'], $quest['NextQuestID']
				)
		)
			unset($quest['open']);
		else
			$questItems[] = 'open';

	// Квесты, которые становятся недоступными после выполнения этого квеста
	if($quest['ExclusiveGroup']>0)
		if(!$quest['closes'] = $DB->select('
				SELECT q.entry, q.Title
					{, l.Title_loc?d as Title_loc}
				FROM quest_template q
					{LEFT JOIN (locales_quest l) ON l.entry=q.entry AND ?}
				WHERE
					q.ExclusiveGroup=?d AND q.entry<>?d
				LIMIT 20
				',
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP, ($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$quest['ExclusiveGroup'], $quest['entry']
				)
		)
			unset($quest['closes']);
		else
			$questItems[] = 'closes';

	// Требует выполнения одного из квестов, на выбор:
	if(!$quest['reqone'] = $DB->select('
				SELECT q.entry, q.Title
					{, l.Title_loc?d as Title_loc}
				FROM quest_template q
					{LEFT JOIN (locales_quest l) ON l.entry=q.entry AND ?}
				WHERE
					q.ExclusiveGroup>0 AND q.NextQuestId=?d
				LIMIT 20
				',
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP, ($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$quest['entry']
				)
		)
			unset($quest['reqone']);
		else
			$questItems[] = 'reqone';

	// Квесты, которые доступны, только во время выполнения этого квеста
	if(!$quest['enables'] = $DB->select('
				SELECT q.entry, q.Title
					{, l.Title_loc?d as Title_loc}
				FROM quest_template q
					{LEFT JOIN (locales_quest l) ON l.entry=q.entry AND ?}
				WHERE q.PrevQuestID=?d
				LIMIT 20
				',
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP, ($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				-$quest['entry']
				)
		)
			unset($quest['enables']);
		else
			$questItems[] = 'enables';

	// Квесты, во время выполнения которых доступен этот квест
	if($quest['PrevQuestID']<0)
		if(!$quest['enabledby'] = $DB->select('
				SELECT q.entry, q.Title
					{, l.Title_loc?d as Title_loc}
				FROM quest_template q
					{LEFT JOIN (locales_quest l) ON l.entry=q.entry AND ?}
				WHERE q.entry=?d
				LIMIT 20
				',
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP, ($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				-$quest['PrevQuestID']
				)
		)
			unset($quest['enabledby']);
		else
			$questItems[] = 'enabledby';

	// Теперь локализуем все тайтлы квестов
	if($questItems)
		foreach($questItems as $item)
			foreach($quest[$item] as $i => $x)
				$quest[$item][$i]['Title'] = localizedName($quest[$item][$i], 'Title');



	/*             НАГРАДЫ И ТРЕБОВАНИЯ             */

	if($quest['RequiredSkillValue']>0 && $quest['SkillOrClass']>0)
	{
		// Требуемый уровень скилла, что бы получить квест
		/*
		$skills = array(
			-264 => 197,	// Tailoring
			-182 => 165,	// Leatherworking
			-24 => 182,		// Herbalism
			-101 => 356,	// Fishing
			-324 =>	129,	// First Aid
			-201 => 202,	// Engineering
			-304 => 185,	// Cooking
			-121 => 164,	// Blacksmithing
			-181 => 171		// Alchemy
		);
		*/
		
		// TODO: skill localization
		$quest['reqskill'] = array(
			'name' => $DB->selectCell('SELECT name_loc'.$_SESSION['locale'].' FROM ?_skill WHERE skillID=?d LIMIT 1',$quest['SkillOrClass']),
			'value' => $quest['RequiredSkillValue']
		);
	}
	elseif($quest['SkillOrClass']<0)
		// Требуемый класс, что бы получить квест
		$quest['reqclass'] = $classes[abs($quest['SkillOrClass'])];

	// Требуемые отношения с фракциями, что бы начать квест
	if($quest['RequiredMinRepFaction'])
		$quest['RequiredMinRep'] = array(
			'name' => $DB->selectCell('SELECT name_loc'.$_SESSION['locale'].' FROM ?_factions WHERE factionID=?d LIMIT 1', $quest['RequiredMinRepFaction']),
			'entry' => $quest['RequiredMinRepFaction'],
			'value' => reputations($quest['RequiredMinRepValue'])
		);
	if($quest['RequiredMaxRepFaction'])
		$quest['RequiredMaxRep'] = array(
			'name' => $DB->selectCell('SELECT name_loc'.$_SESSION['locale'].' FROM ?_factions WHERE factionID=?d LIMIT 1', $quest['RequiredMaxRepFaction']),
			'entry' => $quest['RequiredMaxRepFaction'],
			'value' => reputations($quest['RequiredMaxRepValue'])
		);

	// Спеллы не требуют локализации, их инфа берется из базы
	// Хранить в базе все локализации - задачка на будующее

	// Спелл, кастуемый на игрока в начале квеста
	if($quest['SrcSpell'])
	{
		$tmp = $DB->selectRow('
			SELECT ?#, s.spellname_loc'.$_SESSION['locale'].'
			FROM ?_spell s, ?_spellicons si
			WHERE
				s.spellID=?d
				AND si.id=s.spellicon
			LIMIT 1',
			$spell_cols[0],
			$quest['SrcSpell']
		);
		if($tmp)
		{
			$quest['SrcSpell'] = array(
				'name' => $tmp['spellname_loc'.$_SESSION['locale']],
				'entry' => $tmp['spellID']);
			allspellsinfo2($tmp);
		}
		unset($tmp);
	}

	// Итем, выдаваемый игроку в начале квеста
	if($quest['SrcItemId'])
	{
		$quest['SrcItem'] = iteminfo($quest['SrcItemId']);
		$quest['SrcItem']['count'] = $quest['SrcItemCount'];
	}

	// Дополнительная информация о квесте (флаги, повторяемость, скрипты)
	$quest['flagsdetails'] = GetQuestFlagsDetails($quest);
	if (!$quest['flagsdetails'])
		unset($quest['flagsdetails']);

	// Спелл, кастуемый на игрока в награду за выполнение
	if($quest['RewSpellCast']>0 || $quest['RewSpell']>0)
	{
		$tmp = $DB->SelectRow('
			SELECT ?#, s.spellname_loc'.$_SESSION['locale'].'
			FROM ?_spell s, ?_spellicons si
			WHERE
				s.spellID=?d
				AND si.id=s.spellicon
			LIMIT 1',
			$spell_cols[0],
			$quest['RewSpell']>0?$quest['RewSpell']:$quest['RewSpellCast']
		);
		if($tmp)
		{
			$quest['spellreward'] = array(
				'name' => $tmp['spellname_loc'.$_SESSION['locale']],
				'entry' => $tmp['spellID'],
				'realentry' => $quest['RewSpellCast']>0 ? $quest['RewSpellCast'] : $quest['RewSpell']);
			allspellsinfo2($tmp);
		}
		unset($tmp);
	}

	// Создания, необходимые для квеста
	//$quest['creaturereqs'] = array();
	//$quest['objectreqs'] = array();
	$quest['coreqs'] = array();
	for($i=0;$i<=4;++$i)
	{
		//echo $quest['ReqCreatureOrGOCount'.$i].'<br />';
		if($quest['ReqCreatureOrGOId'.$i] != 0 && $quest['ReqCreatureOrGOCount'.$i] != 0)
		{
			if($quest['ReqCreatureOrGOId'.$i] > 0)
			{
				// Необходимо какое-либо взамодействие с созданием
				$quest['coreqs'][$i] = array_merge(
					creatureinfo($quest['ReqCreatureOrGOId'.$i]),
					array('req_type' => 'npc')
				);
			}
			else
			{
				// необходимо какое-то взаимодействие с объектом
				$quest['coreqs'][$i] = array_merge(
					objectinfo(-$quest['ReqCreatureOrGOId'.$i]),
					array('req_type' => 'object')
				);
			}
			// Количество
			$quest['coreqs'][$i]['count'] = $quest['ReqCreatureOrGOCount'.$i];
			// Спелл
			if($quest['ReqSpellCast'.$i])
				$quest['coreqs'][$i]['spell'] = array(
					'name' => $DB->selectCell('SELECT spellname_loc'.$_SESSION['locale'].' FROM ?_spell WHERE spellid=?d LIMIT 1', $quest['ReqSpellCast'.$i]),
					'entry' => $quest['ReqSpellCast'.$i]
				);
		}
	}
	if(!$quest['coreqs'])
		unset($quest['coreqs']);

	// Вещи, необходимые для квеста
	$quest['itemreqs'] = array();
	for($i=0;$i<=4;++$i)
	{
		if($quest['ReqItemId'.$i]!=0 && $quest['ReqItemCount'.$i]!=0)
			$quest['itemreqs'][] = array_merge(iteminfo($quest['ReqItemId'.$i]), array('count' => $quest['ReqItemCount'.$i]));
	}
	if(!$quest['itemreqs'])
		unset($quest['itemreqs']);

	// Фракции необходимые для квеста
	if($quest['RepObjectiveFaction']>0)
	{
		$quest['factionreq'] = array(
			'name' => $DB->selectCell('SELECT name_loc'.$_SESSION['locale'].' FROM ?_factions WHERE factionID=?d LIMIT 1', $quest['RepObjectiveFaction']),
			'entry' => $quest['RepObjectiveFaction'],
			'value' => reputations($quest['RepObjectiveValue'])
		);
	}

	/* КВЕСТГИВЕРЫ И КВЕСТТЕЙКЕРЫ */

	// КВЕСТГИВЕРЫ
	// НПС
	$rows = $DB->select('
		SELECT c.entry, c.name, A, H
			{, l.name_loc?d AS name_loc}
		FROM creature_questrelation q, ?_factiontemplate, creature_template c
			{LEFT JOIN (locales_creature l) ON l.entry=c.entry AND ?}
		WHERE
			q.quest=?d
			AND c.entry=q.id
			AND factiontemplateID=c.faction_A
		',
		($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
		$quest['entry']
	);
	if($rows)
	{
		foreach($rows as $tmp)
		{
			$tmp['name'] = localizedName($tmp);
			if($tmp['A'] == -1 && $tmp['H'] == 1)
				$tmp['side'] = 'horde';
			elseif($tmp['A'] == 1 && $tmp['H'] == -1)
				$tmp['side'] = 'alliance';
			$quest['start'][] = array_merge($tmp, array('type' => 'npc'));
		}
	}
	unset($rows);

	// ГО
	$rows = $DB->select('
		SELECT g.entry, g.name
			{, l.name_loc?d AS name_loc}
		FROM gameobject_questrelation q, gameobject_template g
			{LEFT JOIN (locales_gameobject l) ON l.entry = g.entry AND ?}
		WHERE
			q.quest=?d
			AND g.entry=q.id
		',
		($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
		$quest['entry']
	);
	if($rows)
	{
		foreach($rows as $tmp)
		{
			$tmp['name'] = localizedName($tmp);
			$quest['start'][] = array_merge($tmp, array('type' => 'object'));
		}
	}
	unset($rows);

	// итем
	$rows = $DB->select('
		SELECT i.name, i.entry, i.quality, LOWER(a.iconname) AS iconname
			{, l.name_loc?d AS name_loc}
		FROM ?_icons a, item_template i
			{LEFT JOIN (locales_item l) ON l.entry=i.entry AND ?}
		WHERE
			startquest = ?d
			AND id = displayid
		',
		($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
		$quest['entry']
	);
	if($rows)
	{
		$rows = sanitiseitemrows($rows);
		foreach($rows as $tmp)
		{
			$tmp['name'] = localizedName($tmp);
			$quest['start'][] = array_merge($tmp, array('type' => 'item'));
		}
	}
	unset($rows);
	
	// КВЕСТТЕЙКЕРЫ
	// НПС
	$rows = $DB->select('
		SELECT c.entry, c.name, A, H
			{, l.name_loc?d AS name_loc}
		FROM creature_involvedrelation q, ?_factiontemplate, creature_template c
			{LEFT JOIN (locales_creature l) ON l.entry=c.entry AND ?}
		WHERE
			q.quest=?d
			AND c.entry=q.id
			AND factiontemplateID=c.faction_A
		',
		($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
		$quest['entry']
	);
	if($rows)
	{
		foreach($rows as $tmp)
		{
			$tmp['name'] = localizedName($tmp);
			if($tmp['A'] == -1 && $tmp['H'] == 1)
				$tmp['side'] = 'horde';
			elseif($tmp['A'] == 1 && $tmp['H'] == -1)
				$tmp['side'] = 'alliance';
			$quest['end'][] = array_merge($tmp, array('type' => 'npc'));
		}
	}
	unset($rows);

	// ГО
	$rows = $DB->select('
		SELECT g.entry, g.name
			{, l.name_loc?d AS name_loc}
		FROM gameobject_involvedrelation q, gameobject_template g
			{LEFT JOIN (locales_gameobject l) ON l.entry = g.entry AND ?}
		WHERE
			q.quest=?d
			AND g.entry=q.id
		',
		($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
		$quest['entry']
	);
	if($rows)
	{
		foreach($rows as $tmp)
		{
			$tmp['name'] = localizedName($tmp);
			$quest['end'][] = array_merge($tmp, array('type' => 'object'));
		}
	}
	unset($rows);

	// Награды и благодарности, присылаемые почтой
	if ($quest['RewMailTemplateId'])
	{
		if(!($quest['mailrewards'] = loot('mail_loot_template', $quest['entry'])))
			unset ($quest['mailrewards']);
	}
	if ($quest['RewMailDelaySecs'])
		$quest['maildelay'] = sec_to_time($quest['RewMailDelaySecs']);

	save_cache(10, $cache_key, $quest);
}

global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Title' => $quest['Title'].' - '.$smarty->get_config_vars('Quests'),
	'tab' => 0,
	'type' => 5,
	'typeid' => $quest['entry'],
	'path' => path(0, 5) // TODO
);
$smarty->assign('page', $page);

// Комментарии
$smarty->assign('comments', getcomments($page['type'], $page['typeid']));
$smarty->assign('commentsalkz', getcommentsalkz($page['type'], $page['typeid']));

// Данные о квесте
$smarty->assign('quest', $quest);
// Если хоть одна информация о вещи найдена - передаём массив с информацией о вещях шаблонизатору
$smarty->assign('allitems', $allitems);
$smarty->assign('allspells', $allspells);

// Количество MySQL запросов
$smarty->assign('mysql', $DB->getStatistics());
// Загружаем страницу
$smarty->display('quest.tpl');

?>