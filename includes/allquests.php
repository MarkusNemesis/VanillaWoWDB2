<?php

require_once('includes/game.php');
require_once('includes/alllocales.php');
require_once('includes/allitems.php');

global $AoWoWconf;

$quest_class = array(
	0 => array(36, 45, 3, 25, 4, 35, 46, 132, 279, 41, 154, 2257, 1, 10, 139, 12, 3430, 3433, 267, 1537, 131, 38, 24, 9, 44, 51, 3487, 130, 1519, 33, 3431, 8, 47, 85, 1497, 28, 40, 11, 4080),
	1 => array(2079, 3526, 331, 16, 3524, 3525, 148, 221, 1657, 405, 14, 15, 1116, 361, 357, 493, 215, 1637, 220, 702, 188, 1377, 406, 440, 141, 17, 3557, 400, 1638, 1216, 490, 363, 618),
	/* Dungeons */ 2 => array(3790, 3688, 719, 1584, 1583, 1941, 3607, 2557, 133, 3535, 3792, 2100, 2437, 722, 491, 796, 2057, 3789, 209, 2017, 1417, 3842, 1581, 3717, 3715, 717, 3716, 1337, 718, 978),
	3 => array(3428, 2677, 3606, 2562, 3836, 2717, 3456, 2159, 3429, 3840, 19),
	4 => array(-263, -261, -161, -141, -262, -162, -82, -61, -81),
	5 => array(-181, -121, -304, -201, -324, -101, -24, -182, -264),
	6 => array(3358, 2597, 3277),
	7 => array(-365, -370, -364, -1, -368, -344, -366, -369, -367, -22, -284, -221),
	8 => array(3522, 3483, 3518, 3523, 3520, 3703, 3679, 3519, 3696, 3521),
	-2 => array(0)
);

$quest_type = array(
	1  => LOCALE_QUEST_TYPE_GROUP,
	21 => LOCALE_QUEST_TYPE_LIFE,
	41 => LOCALE_QUEST_TYPE_PVP,
	62 => LOCALE_QUEST_TYPE_RAID,
	81 => LOCALE_QUEST_TYPE_DUNGEON,
	82 => LOCALE_QUEST_TYPE_WORLD_EVENT,
	83 => LOCALE_QUEST_TYPE_LEGENDARY,
	84 => LOCALE_QUEST_TYPE_ESCORT,
	85 => LOCALE_QUEST_TYPE_HEROIC,
	88 => LOCALE_QUEST_TYPE_RAID10,
	89 => LOCALE_QUEST_TYPE_RAID25
);

// Флаги квестов
define('QUEST_FLAGS_NONE',				0);
define('QUEST_FLAGS_STAY_ALIVE',		1);
define('QUEST_FLAGS_PARTY_ACCEPT',		2);
define('QUEST_FLAGS_EXPLORATION',		4);
define('QUEST_FLAGS_SHARABLE',			8);
define('QUEST_FLAGS_NONE2',				16);
define('QUEST_FLAGS_EPIC',				32);
define('QUEST_FLAGS_RAID',				64);
define('QUEST_FLAGS_TBC',				128);
define('QUEST_FLAGS_UNK2',				256);
define('QUEST_FLAGS_HIDDEN_REWARDS',	512);
define('QUEST_FLAGS_AUTO_REWARDED',	1024);
define('QUEST_FLAGS_TBC_RACES',			2048);
define('QUEST_FLAGS_DAILY',				4096);
define('QUEST_FLAGS_UNK5',				8192);

define('QUEST_SPECIALFLAGS_NONE',		0);
define('QUEST_SPECIALFLAGS_REPEATABLE',	1);
define('QUEST_SPECIALFLAGS_SCRIPTED',	2);

// Флаги для GetQuestInfo
define('QUEST_DATAFLAG_MINIMUM',	1);
define('QUEST_DATAFLAG_STRINGS',	2);
define('QUEST_DATAFLAG_SERIES',		4);
define('QUEST_DATAFLAG_LOCALE',		8); // Специальный флаг, $questcols не требуется
define('QUEST_DATAFLAG_REWARDS',	16); // Содержит также Req's
define('QUEST_DATAFLAG_PROPS',		32);
define('QUEST_DATAFLAG_LISTINGS', (QUEST_DATAFLAG_MINIMUM | QUEST_DATAFLAG_REWARDS | QUEST_DATAFLAG_PROPS));
define('QUEST_DATAFLAG_AJAXTOOLTIP', (QUEST_DATAFLAG_LISTINGS | QUEST_DATAFLAG_SERIES | QUEST_DATAFLAG_STRINGS | QUEST_DATAFLAG_LOCALE));

$questcols[QUEST_DATAFLAG_MINIMUM]	= array('entry', 'Title');
$questcols[QUEST_DATAFLAG_STRINGS]	= array('Objectives', 'Details', 'RequestItemsText', 'OfferRewardText', 'EndText', 'ObjectiveText1', 'ObjectiveText2', 'ObjectiveText3', 'ObjectiveText4');
$questcols[QUEST_DATAFLAG_REWARDS]	= array('RewChoiceItemId1', 'RewChoiceItemId2', 'RewChoiceItemId3', 'RewChoiceItemId4', 'RewChoiceItemId5', 'RewChoiceItemId6', 'RewChoiceItemCount1', 'RewChoiceItemCount2', 'RewChoiceItemCount3', 'RewChoiceItemCount4', 'RewChoiceItemCount5', 'RewChoiceItemCount6', 'RewItemId1', 'RewItemId2', 'RewItemId3', 'RewItemId4', 'RewItemCount1', 'RewItemCount2', 'RewItemCount3', 'RewItemCount4', 'RewMoneyMaxLevel', 'RewOrReqMoney', 'ReqSpellCast1', 'ReqSpellCast2', 'ReqSpellCast3', 'ReqSpellCast4', 'ReqCreatureOrGOId1', 'ReqCreatureOrGOId2', 'ReqCreatureOrGOId3', 'ReqCreatureOrGOId4', 'ReqItemId1', 'ReqItemId2', 'ReqItemId3', 'ReqItemId4', 'ReqItemCount1', 'ReqItemCount2', 'ReqItemCount3', 'ReqItemCount4', 'SuggestedPlayers', 'ReqCreatureOrGOCount1', 'ReqCreatureOrGOCount2', 'ReqCreatureOrGOCount3', 'ReqCreatureOrGOCount4', 'RewSpell', 'RewSpellCast', 'RewRepFaction1', 'RewRepFaction2', 'RewRepFaction3', 'RewRepFaction4', 'RewRepFaction5', 'RewRepValue1', 'RewRepValue2', 'RewRepValue3', 'RewRepValue4', 'RewRepValue5', 'SrcItemId', 'SrcItemCount', 'SrcSpell', 'RepObjectiveFaction', 'RepObjectiveValue', 'RequiredMinRepFaction', 'RequiredMinRepValue', 'RequiredMaxRepFaction', 'RequiredMaxRepValue', 'ReqSourceId1', 'ReqSourceCount1', 'ReqSourceId2', 'ReqSourceCount2', 'ReqSourceId3', 'ReqSourceCount3', 'ReqSourceId4', 'ReqSourceCount4', 'RewMailTemplateId', 'RewMailDelaySecs', 'PointX', 'PointY', 'StartScript', 'CompleteScript');
$questcols[QUEST_DATAFLAG_PROPS]	= array('Type', 'ZoneOrSort', 'QuestFlags', 'QuestLevel', 'MinLevel', 'RequiredRaces', 'RequiredSkillValue', 'LimitTime', 'SpecialFlags');
$questcols[QUEST_DATAFLAG_SERIES]	= array('PrevQuestID', 'NextQuestInChain', 'ExclusiveGroup', 'NextQuestID');

$quest_cols[2] = array('entry', 'Title', 'QuestLevel', 'MinLevel', 'RequiredRaces', 'RewChoiceItemId1', 'RewChoiceItemId2', 'RewChoiceItemId3', 'RewChoiceItemId4', 'RewChoiceItemId5', 'RewChoiceItemId6', 'RewChoiceItemCount1', 'RewChoiceItemCount2', 'RewChoiceItemCount3', 'RewChoiceItemCount4', 'RewChoiceItemCount5', 'RewChoiceItemCount6', 'RewItemId1', 'RewItemId2', 'RewItemId3', 'RewItemId4', 'RewItemCount1', 'RewItemCount2', 'RewItemCount3', 'RewItemCount4', 'RewMoneyMaxLevel', 'RewOrReqMoney', 'Type', 'ZoneOrSort', 'QuestFlags');
$quest_cols[3] = array('Title', 'QuestLevel', 'MinLevel', 'RequiredRaces', 'RewChoiceItemId1', 'RewChoiceItemId2', 'RewChoiceItemId3', 'RewChoiceItemId4', 'RewChoiceItemId5', 'RewChoiceItemId6', 'RewChoiceItemCount1', 'RewChoiceItemCount2', 'RewChoiceItemCount3', 'RewChoiceItemCount4', 'RewChoiceItemCount5', 'RewChoiceItemCount6', 'RewItemId1', 'RewItemId2', 'RewItemId3', 'RewItemId4', 'RewItemCount1', 'RewItemCount2', 'RewItemCount3', 'RewItemCount4', 'RewMoneyMaxLevel', 'RewOrReqMoney', 'Type', 'ZoneOrSort', 'QuestFlags', 'RewRepFaction1', 'RewRepFaction2', 'RewRepFaction3', 'RewRepFaction4', 'RewRepFaction5', 'RewRepValue1', 'RewRepValue2', 'RewRepValue3', 'RewRepValue4', 'RewRepValue5', 'Objectives', 'Details', 'RequestItemsText', 'OfferRewardText', 'ReqCreatureOrGOId1', 'ReqCreatureOrGOId2', 'ReqCreatureOrGOId3', 'ReqCreatureOrGOId4', 'ReqItemId1', 'ReqItemId2', 'ReqItemId3', 'ReqItemId4', 'ReqItemCount1', 'ReqItemCount2', 'ReqItemCount3', 'ReqItemCount4', 'SrcItemId', 'ReqCreatureOrGOCount1', 'ReqCreatureOrGOCount2', 'ReqCreatureOrGOCount3', 'ReqCreatureOrGOCount4', 'ObjectiveText1', 'ObjectiveText2', 'ObjectiveText3', 'ObjectiveText4', 'EndText', 'PrevQuestID', 'NextQuestInChain', 'ExclusiveGroup', 'NextQuestID', 'RewSpellCast', 'RewSpell', 'RequiredSkillValue', 'RepObjectiveFaction', 'RepObjectiveValue', 'SuggestedPlayers', 'LimitTime', 'QuestFlags', 'SpecialFlags', 'CharTitleId', 'RequiredMinRepFaction', 'RequiredMinRepValue', 'RequiredMaxRepFaction', 'RequiredMaxRepValue', 'SrcSpell', 'SkillOrClass', 'ReqSpellCast1', 'ReqSpellCast2', 'ReqSpellCast3', 'ReqSpellCast4');

$locale_quest_cols = array('Title_loc'.$AoWoWconf['locale'], 'Details_loc'.$AoWoWconf['locale'], 'Objectives_loc'.$AoWoWconf['locale'], 'OfferRewardText_loc'.$AoWoWconf['locale'], 'RequestItemsText_loc'.$AoWoWconf['locale'], 'EndText_loc'.$AoWoWconf['locale'], 'ObjectiveText1_loc'.$AoWoWconf['locale'], 'ObjectiveText2_loc'.$AoWoWconf['locale'], 'ObjectiveText3_loc'.$AoWoWconf['locale'], 'ObjectiveText4_loc'.$AoWoWconf['locale']);

function QuestReplaceStr($STR)
{
	global $smarty;
	// сначала заменяем $N, $R, $C
	$toreplace = array (
		0=>array('1'=>'$b', '2'=>'<br />',),
		1=>array('1'=>'$r', '2'=>'&lt;'.(isset($smarty) ? $smarty->get_config_vars('race')  : 'race'  ).'&gt;',),
		2=>array('1'=>'$c', '2'=>'&lt;'.(isset($smarty) ? $smarty->get_config_vars('class') : 'class' ).'&gt;',),
		3=>array('1'=>'$n', '2'=>'&lt;'.(isset($smarty) ? $smarty->get_config_vars('name')  : 'name'  ).'&gt;',),
		4=>array('1'=>'$G', '2'=>'$g',),
	);
	for($i=0;$i<=3;$i++)
	{
		$STR = str_replace($toreplace[$i][1], $toreplace[$i][2], $STR);
		$STR = str_replace(strtoupper($toreplace[$i][1]), $toreplace[$i][2], $STR);
	}
	// теперь - пол
	while(strpos($STR, '$g') || strpos($STR, '$G'))
	{
		$gPos = strpos($STR, '$g');
		if(!$gPos)
			$gPos = strpos($STR, '$G');
		if($gPos)
		{
			$ePos = strpos($STR, ';', $gPos);
			if(!$ePos)
				return $STR; // error!
			$string = explode(':', substr($STR, $gPos+2, $ePos));
			$STR = substr($STR, 0, $gPos) . $string[0] . substr($STR, $ePos+1, 0xffff);
		}
	}
	return $STR;
}

// Информация, возвращаемая этой функцией, очень помогает
// оценить доступность и выполнимость квестов.
function GetQuestFlagsDetails($data)
{
	$result = array();
	// Неявно используемые в квесте итемы
	$srcitems = array();
	for ($i=1; $i<=4; $i++)
		if (isset($data['ReqSourceId'.$i]) && isset($data['ReqSourceCount'.$i]) && $data['ReqSourceId'.$i])
			if ($data['ReqSourceCount'.$i] == 1)
				$srcitems[] = $data['ReqSourceId'.$i];
			else
				$srcitems[] = $data['ReqSourceId'.$i] . "x" . $data['ReqSourceCount'.$i];

	// Разные квестовые флаги, и клиентские и серверные
	if ($data['QuestFlags'])
	{
		if ($data['QuestFlags'] & QUEST_FLAGS_STAY_ALIVE)     $result[] = LOCALE_QUEST_FLAGS_STAY_ALIVE;
		if ($data['QuestFlags'] & QUEST_FLAGS_PARTY_ACCEPT)   $result[] = LOCALE_QUEST_FLAGS_PARTY_ACCEPT;
		if ($data['QuestFlags'] & QUEST_FLAGS_EXPLORATION)    $result[] = LOCALE_QUEST_FLAGS_EXPLORATION;
		if ($data['QuestFlags'] & QUEST_FLAGS_SHARABLE)       $result[] = LOCALE_QUEST_FLAGS_SHARABLE;
		if ($data['QuestFlags'] & QUEST_FLAGS_EPIC)           $result[] = LOCALE_QUEST_FLAGS_EPIC;
		if ($data['QuestFlags'] & QUEST_FLAGS_RAID)           $result[] = LOCALE_QUEST_FLAGS_RAID;
		if ($data['QuestFlags'] & QUEST_FLAGS_TBC)            $result[] = LOCALE_QUEST_FLAGS_TBC;
		//if ($data['QuestFlags'] & QUEST_FLAGS_UNK2)           $result[] = LOCALE_QUEST_FLAGS_UNK2;
		if ($data['QuestFlags'] & QUEST_FLAGS_HIDDEN_REWARDS) $result[] = LOCALE_QUEST_FLAGS_HIDDEN_REWARDS;
		if ($data['QuestFlags'] & QUEST_FLAGS_AUTO_REWARDED)  $result[] = LOCALE_QUEST_FLAGS_AUTO_REWARDED;
		if ($data['QuestFlags'] & QUEST_FLAGS_TBC_RACES)      $result[] = LOCALE_QUEST_FLAGS_TBC_RACES;
		if ($data['QuestFlags'] & QUEST_FLAGS_DAILY)          $result[] = LOCALE_QUEST_FLAGS_DAILY;
		if ($data['QuestFlags'] & QUEST_FLAGS_UNK5)           $result[] = LOCALE_QUEST_FLAGS_UNK5;
	}

	// Неявно используемые доп. элементы (интересно, кто назвал эту константу "..._UNK2"?)
	if (($data['QuestFlags'] & QUEST_FLAGS_UNK2) || $srcitems)
	{
		$tmp = LOCALE_QUEST_FLAGS_UNK2;
		if ($srcitems) $tmp = $tmp . " (" . implode(", ", $srcitems) . ")";
		$result[] = $tmp;
	}

	// Специальные серверные флаги - повторяемость и завершение скриптом
	if ($data['SpecialFlags'])
	{
		if ($data['SpecialFlags'] & QUEST_SPECIALFLAGS_REPEATABLE) $result[] = LOCALE_QUEST_SPECIALFLAGS_REPEATABLE;
		if ($data['SpecialFlags'] & QUEST_SPECIALFLAGS_SCRIPTED)   $result[] = LOCALE_QUEST_SPECIALFLAGS_SCRIPTED;
	}

	// Наличие стартовых и финишных скриптов
	if ($data['PointX'] || $data['PointY']) $result[] = LOCALE_QUEST_HAS_POI;
	if ($data['StartScript'])               $result[] = LOCALE_QUEST_HAS_START_SCRIPT;
	if ($data['CompleteScript'])            $result[] = LOCALE_QUEST_HAS_COMPLETE_SCRIPT;

	return $result;
}

function GetQuestXpOrMoney($data)
{
	// From MaNGOS Sources
	$pLevel = $data['MinLevel'] + 1;
	$qLevel = $data['QuestLevel'];
	$RewMoneyMaxLevel = $data['RewMoneyMaxLevel'];
	if(!$RewMoneyMaxLevel)
		return 0;
	$fullxp = 0;
	if($qLevel >= 15)
		$fullxp = $RewMoneyMaxLevel / 6.0;
	elseif($qLevel == 14)
		$fullxp = $RewMoneyMaxLevel / 4.8;
	elseif($qLevel == 13)
		$fullxp = $RewMoneyMaxLevel / 3.666;
	elseif($qLevel == 12)
		$fullxp = $RewMoneyMaxLevel / 2.4;
	elseif($qLevel == 11)
		$fullxp = $RewMoneyMaxLevel / 1.2;
	elseif($qLevel >= 1 && $qLevel <= 10)
		$fullxp = $RewMoneyMaxLevel / 0.6;
	elseif($qLevel == 0)
		$fullxp = $RewMoneyMaxLevel;

	if( $pLevel <= $qLevel +  5 )
		return $fullxp;
	elseif( $pLevel == $qLevel +  6 )
		return ($fullxp * 0.8);
	elseif( $pLevel == $qLevel +  7 )
		return ($fullxp * 0.6);
	elseif( $pLevel == $qLevel +  8 )
		return ($fullxp * 0.4);
	elseif( $pLevel == $qLevel +  9 )
		return ($fullxp * 0.2);
	else
		return ($fullxp * 0.1);
}

// ????
function GetQuestTitle(&$data)
{
	$title = QuestReplaceStr(localizedName($data, 'Title'));
	$data['Title'] = $title;
	return $title;
}

function GetQuestStrings(&$data)
{
	$data['Title']				= QuestReplaceStr(				  (localizedName($data, 'Title')));
	$data['Objectives']			= QuestReplaceStr(htmlspecialchars(localizedName($data, 'Objectives')));
	$data['Details']			= QuestReplaceStr(htmlspecialchars(localizedName($data, 'Details')));
	$data['RequestItemsText']	= QuestReplaceStr(htmlspecialchars(localizedName($data, 'RequestItemsText')));
	$data['OfferRewardText']	= QuestReplaceStr(htmlspecialchars(localizedName($data, 'OfferRewardText')));
	$data['EndText']			= QuestReplaceStr(htmlspecialchars(localizedName($data, 'EndText')));

	for($j=0;$j<=3;++$j)
		$data['ObjectiveText'][$j] = QuestReplaceStr(htmlspecialchars(localizedName($data, 'ObjectiveText'.$j)));
}

function GetQuestReq($id, $count, $type)
{
	global $DB;
	global $AoWoWconf;
	switch($type)
	{
		case 1:
			$row = $DB->selectRow('
					SELECT name
						{, l.name_loc?d AS name_loc}
					FROM creature_template c
						{ LEFT JOIN (locales_creature l) ON l.entry=c.entry AND ? }
					WHERE
						c.entry = ?d
					LIMIT 1
				',
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$id
			);
			$name = localizedName($row);
			return $name.(($count>1)? (' x'.$count): '');
			break;
		case 2:
			$row = $DB->selectRow('
			SELECT a.* FROM 
			(
					SELECT name
						{, l.name_loc?d AS name_loc}
					FROM item_template c
						{ LEFT JOIN (locales_item l) ON l.entry=c.entry AND ? }
					WHERE
						c.entry = ?d
			) a
			INNER JOIN (
				SELECT *, MAX(patch) patchno
				FROM item_template
				WHERE patch <= ?d
				GROUP BY entry
			) b ON a.entry = b.entry AND a.patch = b.patchno
					LIMIT 1
				',
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$id,
				$AoWoWconf['patch']
			);
			$name = localizedName($row);
			return $name.(($count>1)? (' x'.$count): '');
			break;
	}
}

function GetQuestTooltip($row)
{
	$x = '';
	
	// Название квеста
	$x .= '<table><tr><td><b class="q">'.$row['Title'].'</b></td></tr></table>';

	$x .= '<table>';
	if($row['Objectives'])
	{
		$x .= '<tr><td><br>';
		$x .= $row['Objectives'];
		$x .= '</td></tr>';
	}

//	$x .= '<br>';

	if((($row['ReqCreatureOrGOId1']) and ($row['ReqCreatureOrGOCount1'])) or
			(($row['ReqCreatureOrGOId2']) and ($row['ReqCreatureOrGOCount2'])) or
			(($row['ReqCreatureOrGOId3']) and ($row['ReqCreatureOrGOCount3'])) or
			(($row['ReqCreatureOrGOId4']) and ($row['ReqCreatureOrGOCount4'])) or
			(($row['ReqItemId1']) and ($row['ReqItemCount1'])) or
			(($row['ReqItemId2']) and ($row['ReqItemCount2'])) or
			(($row['ReqItemId3']) and ($row['ReqItemCount3'])) or
			(($row['ReqItemId4']) and ($row['ReqItemCount4'])))
	{
		$x .= '<tr><td><br>';
		$x .= '<div class="q">'.LOCALE_REQUIREMENTS.':<br></div>';
		for($j=1;$j<=4;$j++)
			if($row['ReqCreatureOrGOId'.$j] and $row['ReqCreatureOrGOCount'.$j])
				$x .= '- '
					.(
						(!empty($row['ObjectiveText'][$j]))?
						$row['ObjectiveText'][$j]:
						GetQuestReq($row['ReqCreatureOrGOId'.$j], $row['ReqCreatureOrGOCount'.$j], 1)
					).'<br>';
		for($j=1;$j<=4;$j++)
			if($row['ReqItemId'.$j] and $row['ReqItemCount'.$j])
				$x .= '- '.GetQuestReq($row['ReqItemId'.$j], $row['ReqItemCount'.$j], 2).'<br>';
		$x .= '</td></tr>';
	}
	$x .= '</table>';

	return $x;
}

function GetQuestDBLocale($quest)
{
	global $DB;
	$data = array();
	$loc = $_SESSION['locale'];
	$row = $DB->selectRow('
			SELECT
				Title_loc?d AS Title_loc,
				Details_loc?d AS Details_loc,
				Objectives_loc?d AS Objectives_loc,
				OfferRewardText_loc?d AS OfferRewardText_loc,
				RequestItemsText_loc?d AS RequresItemsText_loc,
				EndText_loc?d AS EndText_loc,
				ObjectiveText1_loc?d AS ObjectiveText1_loc,
				ObjectiveText2_loc?d AS ObjectiveText2_loc,
				ObjectiveText3_loc?d AS ObjectiveText3_loc,
				ObjectiveText4_loc?d AS ObjectiveText4_loc
			FROM locales_quest
			WHERE entry = ?d
			LIMIT 1
		',
		$loc, $loc, $loc, $loc, $loc, $loc, $loc, $loc, $loc, $loc,
		$quest
	);
	if($row)
		foreach($row as $item => $itemContent)
			if(!empty($itemContent))
				$data[$item] = $itemContent;
	return $data;
}

function GetDBQuestInfo($id, $dataflag = QUEST_DATAFLAG_MINIMUM)
{
	global $DB, $questcols, $quest_class;
	$data = $DB->selectRow('
			SELECT
				1
				{, ?# } {, ?# } {, ?# } {, ?# } {, ?# }
			FROM quest_template
			WHERE entry=?d
			LIMIT 1
		',
		($dataflag & QUEST_DATAFLAG_MINIMUM)?$questcols[QUEST_DATAFLAG_MINIMUM]:DBSIMPLE_SKIP,
		($dataflag & QUEST_DATAFLAG_STRINGS)?$questcols[QUEST_DATAFLAG_STRINGS]:DBSIMPLE_SKIP,
		($dataflag & QUEST_DATAFLAG_SERIES) ?$questcols[QUEST_DATAFLAG_SERIES] :DBSIMPLE_SKIP,
		($dataflag & QUEST_DATAFLAG_PROPS)  ?$questcols[QUEST_DATAFLAG_PROPS]  :DBSIMPLE_SKIP,
		($dataflag & QUEST_DATAFLAG_REWARDS)?$questcols[QUEST_DATAFLAG_REWARDS]:DBSIMPLE_SKIP,
		$id
	);
	if(!$data)
		return false;
	else
		return GetQuestInfo($data, $dataflag);
}

/*
 * &$data - ссылка на массив с данными
 * $dataflag - флаг уровень:
 * QUEST_DATAFLAG_MINIMUN	- entry, Title
 * QUEST_DATAFLAG_STRINGS	- Objectives, Details, RequestItemsText, OfferRewardText, EndText, ObjectiveText1, ObjectiveText2, ObjectiveText3, ObjectiveText4
 * QUEST_DATAFLAG_SERIES	- PrevQuestID, NextQuestInChain, ExclusiveGroup, NextQuestID
 * QUEST_DATAFLAG_PROPS		- Daily, Type, side, etc.
 * QUEST_DATAFLAG_REWARDS	- RewChoiceItemId1, RewChoiceItemId2, RewChoiceItemId3, RewChoiceItemId4, RewChoiceItemId5, RewChoiceItemId6, RewChoiceItemCount1', 'RewChoiceItemCount2, RewChoiceItemCount3, 'RewChoiceItemCount4', 'RewChoiceItemCount5', 'RewChoiceItemCount6', 'RewItemId1', 'RewItemId2', 'RewItemId3', 'RewItemId4', 'RewItemCount1', 'RewItemCount2', 'RewItemCount3', 'RewItemCount4', 'RewMoneyMaxLevel', 'RewOrReqMoney', 'ReqSpellCast1', 'ReqSpellCast2', 'ReqSpellCast3', 'ReqSpellCast4', 'ReqCreatureOrGOId1', 'ReqCreatureOrGOId2', 'ReqCreatureOrGOId3', 'ReqCreatureOrGOId4', 'ReqItemId1', 'ReqItemId2', 'ReqItemId3', 'ReqItemId4', 'ReqItemCount1', 'ReqItemCount2', 'ReqItemCount3', 'ReqItemCount4', 'SrcItemId', 'ReqCreatureOrGOCount1', 'ReqCreatureOrGOCount2', 'ReqCreatureOrGOCount3', 'ReqCreatureOrGOCount4
 *
 */
function GetQuestInfo(&$data, $dataflag = QUEST_DATAFLAG_MINIMUM)
{
	global $DB, $quest_class;
	/*else
	{
		$data = $DB->selectRow('
				SELECT
					1
					{, ?# } {, ?# } {, ?# } {, ?# } {, ?# }
				FROM quest_template
				WHERE entry=?d
				LIMIT 1
			',
			($dataflag & QUEST_DATAFLAG_MINIMUM)?$questcols[QUEST_DATAFLAG_MINIMUM]:DBSIMPLE_SKIP,
			($dataflag & QUEST_DATAFLAG_STRINGS)?$questcols[QUEST_DATAFLAG_STRINGS]:DBSIMPLE_SKIP,
			($dataflag & QUEST_DATAFLAG_SERIES) ?$questcols[QUEST_DATAFLAG_SERIES] :DBSIMPLE_SKIP,
			($dataflag & QUEST_DATAFLAG_PROPS)  ?$questcols[QUEST_DATAFLAG_PROPS]  :DBSIMPLE_SKIP,
			($dataflag & QUEST_DATAFLAG_REWARDS)?$questcols[QUEST_DATAFLAG_REWARDS]:DBSIMPLE_SKIP,
			$data['entry']
		);
	}
	if(!$data)
	{
		return false;
	}*/

	// Локализация
	$loc = $_SESSION['locale'];
	if($dataflag & QUEST_DATAFLAG_LOCALE && $_SESSION['locale'] > 0)
		$data = array_merge($data, $DB->selectRow('
				SELECT
					Title_loc?d AS Title_loc,
					Details_loc?d AS Details_loc,
					Objectives_loc?d AS Objectives_loc,
					OfferRewardText_loc?d AS OfferRewardText_loc,
					RequestItemsText_loc?d AS RequresItemsText_loc,
					EndText_loc?d AS EndText_loc,
					ObjectiveText1_loc?d AS ObjectiveText1_loc,
					ObjectiveText2_loc?d AS ObjectiveText2_loc,
					ObjectiveText3_loc?d AS ObjectiveText3_loc,
					ObjectiveText4_loc?d AS ObjectiveText4_loc
				FROM locales_quest
				WHERE entry = ?d
				LIMIT 1
			',
			$loc, $loc, $loc, $loc, $loc, $loc, $loc, $loc, $loc, $loc,
			$data['entry']
		));
	// Минимальные данные
	// ID квеста
	$data['entry'] = $data['entry'];
	// Имя квеста
	$data['Title'] = GetQuestTitle($data);

	// Описания
	if($dataflag & QUEST_DATAFLAG_STRINGS)
		GetQuestStrings($data);

	// Свойства
	if($dataflag & QUEST_DATAFLAG_PROPS)
	{
		// Уровень квеста
		$data['QuestLevel'] = $data['QuestLevel'];
		// Требуемый уровень квеста
		$data['MinLevel'] = $data['MinLevel'];
		// Доступен расам
		$data['side'] = races($data['RequiredRaces']);
		// Флаги
		$data['QuestFlags'] = $data['QuestFlags'];
		// Ежедневный квест?
		if($data['QuestFlags'] & QUEST_FLAGS_DAILY)
			$data['Daily'] = true;
		// Тип квеста
		$data['type'] = $data['Type'];
		global $quest_type;
		if(isset($quest_type[$data['type']]))
			$data['typename'] = $quest_type[$data['type']];
		else
			$data['typename'] = $data['type'];
		// Путь к этому разделу (главная категория)
		foreach($quest_class as $i => $class)
			if(in_array($data['ZoneOrSort'], $class))
			{
				$data['maincat']=$i;
				break;
			}
		// Категория 1
		$data['category'] = $data['ZoneOrSort'];
		// Категория 2 ???
		$data['category2'] = $data['QuestFlags'];
		// Требуемое пати
		if($data['SuggestedPlayers']>1)
			$data['splayers'] = $data['SuggestedPlayers'];
		// Лимит времени
		if($data['LimitTime']>0)
			$data['LimitTime'] = sec_to_time($data['LimitTime']);
		else
			unset($data['LimitTime']);
		if($data['QuestFlags'] & QUEST_FLAGS_SHARABLE)
			$data['Sharable'] = true;
		if($data['SpecialFlags'] & QUEST_SPECIALFLAGS_REPEATABLE)
			$data['Repeatable'] = true;
		if($data['CharTitleId']>0)
			$data['titlereward'] = $DB->selectCell('SELECT name_loc'.$_SESSION['locale'].' FROM ?_char_titles WHERE id=?d LIMIT 1', $data['CharTitleId']);
	}

	// Награды и задания
	if($dataflag & QUEST_DATAFLAG_REWARDS)
	{
		// Опыт/деньги@70
		$data['xp'] = GetQuestXpOrMoney($data);
		// Награды вещей
		for($j=0;$j<=6;++$j)
			if(($data['RewChoiceItemId'.$j]!=0) and ($data['RewChoiceItemCount'.$j]!=0))
				$data['itemchoices'][] = array_merge(
					allitemsinfo($data['RewChoiceItemId'.$j], 0),
					array('count' => $data['RewChoiceItemCount'.$j])
				);
		for($j=0;$j<=4;++$j)
			if(($data['RewItemId'.$j]!=0) and ($data['RewItemCount'.$j]!=0))
				$data['itemrewards'][] = array_merge(
					allitemsinfo($data['RewItemId'.$j], 0),
					array('count' => $data['RewItemCount'.$j])
				);
		// Вознаграждение репутацией
		for($j=1;$j<=5;$j++)
			if(($data['RewRepFaction'.$j] != 0) && ($data['RewRepValue'.$j] != 0))
				$data['reprewards'][] = array_merge(factioninfo($data['RewRepFaction'.$j]), array('value' => $data['RewRepValue'.$j]));
		// Вознаграждение деньгами
		if($data['RewOrReqMoney']>0)
			$data['money'] = money2coins($data['RewOrReqMoney']);
		elseif($data['RewOrReqMoney']<0)
			$data['moneyreq'] = money2coins(-$data['RewOrReqMoney']);
		if ($data['RewMoneyMaxLevel'])
			$data['moneymaxlevel'] = money2coins($data['RewMoneyMaxLevel']);
	}

	// Последовательность квестов, требования, цепочки
	if($dataflag & QUEST_DATAFLAG_SERIES)
	{
		// не используется для вычисления самих сериесов, исключительно для внесения соответствующих полей в массив информации
	}
	
	// Все ОК. Это не обязательный return, но в некоторых функциях нужен.
	return $data;
}
?>