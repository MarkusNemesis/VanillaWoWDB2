<?php

require_once('includes/allspells.php');

$smarty->config_load($conf_file, 'spell');

@list($s1, $s2, $s3) = extract_values($podrazdel);

$cache_key = cache_key($s1, $s2, $s3);

if(!$spells = load_cache(15, $cache_key))
{
	unset($spells);

	$spells = array();
	if($s1 == 7)
	{
		// Классовые
		$title = $smarty->get_config_vars('Class_spells');
		$rows = $DB->select('
				SELECT ?#, s.`spellID`, sla.skillID
				FROM ?_spell s, ?_skill_line_ability sla, ?_spellicons i
				WHERE
					s.spellID = sla.spellID
					AND s.levelspell > 0
					AND i.id=s.spellicon
					{AND sla.classmask & ?d}
					{AND sla.skillID=?d}
				ORDER BY s.levelspell
				{LIMIT ?d}
			',
			$spell_cols[2],
			isset($s2) ? pow(2, $s2-1) : DBSIMPLE_SKIP,
			isset($s3) ? $s3 : DBSIMPLE_SKIP,
			($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
	}
	elseif($s1 > 0)
	{
		switch($s1)
		{
			case 6:
				$title = $smarty->get_config_vars('Weapon_spells');
				break;
			case 8:
				$title = $smarty->get_config_vars('Armor_spells');
				break;
			case 10:
				$title = $smarty->get_config_vars('Languages');
				break;
			case 9:
				$title = $smarty->get_config_vars('Secondary_spells');
				break;
			case 11:
				$title = $smarty->get_config_vars('Profession_spells');
				break;
			default:
				$title = '???';
				break;
		}
		$spells['sort'] = "'skill', 'name'";
		// Профессии & other
		$rows = $DB->select('
			SELECT
				?#, `s`.`spellID`,
				sla.skillID, sla.min_value, sla.max_value
			FROM ?_spell s, ?_skill_line_ability sla, ?_spellicons i, ?_skill sk
			WHERE
				s.spellID = sla.spellID
				AND i.id=s.spellicon
				{AND sk.categoryID=?d}
				{AND sla.skillID=?d}
				AND sla.skillID=sk.skillID
			{LIMIT ?d}
		',
		$spell_cols[2],
		$s1,
		isset($s2) ? $s2 : DBSIMPLE_SKIP,
		($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
	}
	elseif($s1 == -3)
	{
		$title = $smarty->get_config_vars('Pet_spells');
		// Петы
		$spells['sort'] = "'name'";
		$pets = isset($s2) ? array($s2) : $pet_skill_categories;
		$rows = $DB->select('
				SELECT
					?#, `s`.`spellID`, sla.skillID
				FROM ?_spell s, ?_skill_line_ability sla, ?_spellicons i
				WHERE
					s.spellID = sla.spellID
					AND s.levelspell > 0
					AND i.id=s.spellicon
					{AND sla.skillID IN (?a)}
				{LIMIT ?d}
			',
			$spell_cols[2],
			$pets,
			($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
	}
	elseif($s1 == -4)
	{
		$title = $smarty->get_config_vars('Racial_spells');
		$spells['sort'] = "'name'";
		// Racial Traits
		$spellids = $DB->selectCol('SELECT spellID FROM ?_skill_line_ability WHERE racemask > 0');
		$rows = $DB->select('
			SELECT
				?#, `s`.`spellID`
			FROM ?_spell s, ?_spellicons i
			WHERE
				s.spellID IN (?a)
				AND i.id=s.spellicon
			{LIMIT ?d}
			',
			$spell_cols[2],
			$spellids,
			($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
	}
	elseif ($s1 == -2)
	{
		// Talents
		$title = $smarty->get_config_vars('Talents');
		$spells['sort'] = "'name'";

		$rows = array();
		for($i=1; $i<=5; $i++)
		{
			$rows = array_merge($rows, $DB->select('
				SELECT DISTINCT ?#, `s`.`spellID`
				FROM ?_talenttab b, ?_talent t, ?_spell s, ?_spellicons i {, ?_skill_line_ability ?# }
				WHERE
					b.classes & ?d
					{ AND sla.spellID = s.spellID AND sla.skillID = ?d }
					AND b.id = t.tab
					AND t.rank?d = s.spellID
					AND i.id=s.spellicon
				{LIMIT ?d}
				',
				$spell_cols[2],
				isset($s3) ? 'sla' : DBSIMPLE_SKIP,
				isset($s2) ? pow(2, $s2-1) : -1,
				isset($s3) ? $s3 : DBSIMPLE_SKIP,
				$i,
				($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
			));
		}
	}
	elseif ($s1 == -7)
	{
		// Pet Talents
		$title = $smarty->get_config_vars('Pet_talents');
		$spells['sort'] = "'name'";

		$rows = array();
		for($i=1; $i<=5; $i++)
		{
			$rows = array_merge($rows, $DB->select('
				SELECT DISTINCT ?#, `s`.`spellID`
				FROM ?_talenttab b, ?_talent t, ?_spell s, ?_spellicons i
				WHERE
					b.classes = 0
					AND b.id = t.tab
					AND t.rank?d = s.spellID
					AND i.id=s.spellicon
				{LIMIT ?d}
				',
				$spell_cols[2],
				$i,
				($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
			));
		}
	}
	else
	{
		$spells['sort'] = "'name'";
		// просто спеллы
		$rows = $DB->select('
				SELECT
					?#, `s`.`spellID`
				FROM ?_spell s, ?_spellicons i
				WHERE
					i.id=s.spellicon
				{LIMIT ?d}
			',
			$spell_cols[2],
			($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
		);
	}

	foreach($rows as $i => $row)
		$spells['data'][] = spellinfo2($row);

	save_cache(15, $cache_key, $spells);
}
global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Title' => ($title?$title.' - ':'').$smarty->get_config_vars('Spells'),
	'tab' => 0,
	'type' => 6,
	'typeid' => 0,
	'path' => path(0, 1, $s3, $s2, $s1),
	'sort' => isset($spells['sort'])?$spells['sort']:"'level','name'"
);
$smarty->assign('page', $page);

// Статистика выполнения mysql запросов
$smarty->assign('mysql', $DB->getStatistics());
// Если хоть одна информация о вещи найдена - передаём массив с информацией о вещях шаблонизатору
$smarty->assign('allitems', $allitems);
$smarty->assign('allspells', $allspells);
$smarty->assign('spells', $spells['data']);
// Загружаем страницу
$smarty->display('spells.tpl');

?>