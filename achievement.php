<?php
require_once('includes/allutil.php');
require_once('includes/allachievements.php');
require_once('includes/allitems.php');
require_once('includes/allcomments.php');

$smarty->config_load($conf_file, 'achievement');

$id = intval($podrazdel);

$cache_key = cache_key($id);

if(!$achievement = load_cache(22, $cache_key))
{
	unset($achievement);

	$row = $DB->selectRow('
			SELECT
				a.id, a.faction, a.name_loc?d AS name, a.description_loc?d AS description,
				a.reward_loc?d AS reward, a.category, a.points, a.count, a.parent, s.iconname, z.areatableID
			FROM ?_spellicons s, ?_achievement a
			LEFT JOIN (?_zones z) ON a.map != -1 AND a.map = z.mapID
			WHERE
				a.icon = s.id
				AND a.id = ?
			GROUP BY a.id
		',
		$_SESSION['locale'],
		$_SESSION['locale'],
		$_SESSION['locale'],
		$id
	);

	if($row)
	{
		$achievement = array();
		$achievement = achievementinfo2($row);
		$achievement['side'] = $sides[$achievement['faction']];

		// Категории
		if($row['category'])
		{
			$catrow = $DB->selectRow('
					SELECT c1.id, c1.name_loc?d AS name, c2.id AS id2
					FROM ?_achievementcategory c1
					LEFT JOIN (?_achievementcategory c2) ON c1.parentAchievement != -1 AND c1.parentAchievement = c2.id
					WHERE
						c1.id = ?
				',
				$_SESSION['locale'],
				$row['category']
			);

			if($catrow)
			{
				$achievement['category1'] = $catrow['id'];
				$achievement['category2'] = $catrow['id2'];
				$achievement['category'] = $catrow['name'];
			}
		}

		// Дополнительные достижения
		$rows = $DB->select('
				SELECT a.id, a.faction, a.name_loc?d AS name, a.description_loc?d AS description, a.category, a.points, s.iconname, z.areatableID
				FROM ?_spellicons s, ?_achievement a
				LEFT JOIN (?_zones z) ON a.map != -1 AND a.map = z.mapID
				WHERE
					a.icon = s.id
					AND a.name_loc?d = ?
					AND a.id != ?
				GROUP BY a.id
				ORDER BY a.name_loc?d
			',
			$_SESSION['locale'],
			$_SESSION['locale'],
			$_SESSION['locale'],
			$achievement['name'],
			$achievement['id'],
			$_SESSION['locale']
		);
		if($rows)
		{
			$achievement['see_also'] = array();
			foreach($rows as $row)
				$achievement['see_also'][] = achievementinfo2($row);
		}
		// Достижения, у которых в критериях есть мы
		$rows = $DB->select('
				SELECT a.id, a.faction, a.name_loc?d AS name, a.description_loc?d AS description, a.category, a.points, s.iconname, z.areatableID
				FROM ?_spellicons s, ?_achievementcriteria c, ?_achievement a
				LEFT JOIN (?_zones z) ON a.map != -1 AND a.map = z.mapID
				WHERE
					a.icon = s.id
					AND a.id = c.refAchievement
					AND c.type = ?d
					AND c.value1 = ?d
				GROUP BY a.id
				ORDER BY a.name_loc?d
			',
			$_SESSION['locale'],
			$_SESSION['locale'],
			ACHIEVEMENT_CRITERIA_TYPE_COMPLETE_ACHIEVEMENT,
			$achievement['id'],
			$_SESSION['locale']
		);
		if($rows)
		{
			$achievement['criteria_of'] = array();
			foreach($rows as $row)
				$achievement['criteria_of'][] = achievementinfo2($row);
		}

		/*************** КРИТЕРИИ ДОСТИЖЕНИЯ ***************/

		$rows = $DB->select('
				SELECT id, type, value1, value2, value3, value4, value5, value6, name_loc?d AS name
				FROM ?_achievementcriteria
				WHERE refAchievement = ?
				ORDER BY `order` ASC
			',
			$_SESSION['locale'],
			$achievement['id']
		);
		$achievement['criterias'] = array();
		$i = 0;
		$iconId = 1;
		$tmp_arr = array();
		$achievement['icons'] = array();
		$achievement['total_criterias'] = count($rows);
		foreach($rows as $row)
		{
			$tmp = array(
				'id'	=> $row['id'],
				'name'	=> $row['name'],
				'type'	=> $row['type'],
			);
			switch($row['type'])
			{
				// Добавляем ссылку на НПС
				case ACHIEVEMENT_CRITERIA_TYPE_KILL_CREATURE:
					$tmp['link'] = array(
						'href'	=> '?npc='.$row['value1'],
						'text'	=> $row['name'],
					);
					$tmp['extra_text'] = $smarty->get_config_vars('slain');
					break;
				// Добавляем ссылку на зону, находим по карте
				case ACHIEVEMENT_CRITERIA_TYPE_WIN_BG:
				case ACHIEVEMENT_CRITERIA_TYPE_WIN_ARENA:
					if($zoneId = $DB->selectCell('SELECT areatableID FROM ?_zones WHERE mapID = ? LIMIT 1', $row['value1']))
						$tmp['link'] = array(
							'href'	=> '?zone='.$zoneId,
							'text'	=> $row['name'],
						);
					else
						$tmp['extra_text'] = $row['name'];
					break;
				// TODO
				/*ACHIEVEMENT_CRITERIA_TYPE_REACH_SKILL_LEVEL:
				ACHIEVEMENT_CRITERIA_TYPE_LEARN_SKILL_LEVEL:
					break;*/
				// Добавляем иконку и ссылку на ачив
				case ACHIEVEMENT_CRITERIA_TYPE_COMPLETE_ACHIEVEMENT:
					$tmp['link'] = array(
						'href'	=> '?achievement='.$row['value1'],
						'text'	=> $row['name'],
					);
					$tmp['icon'] = $iconId;
					$achievement['icons'][] = array(
						'itr'	=> $iconId++,
						'type'	=> 'g_achievements',
						'id'	=> $row['value1'],
					);
					allachievementsinfo($row['value1']);
					break;
				// Добавляем ссылку на зону
				case ACHIEVEMENT_CRITERIA_TYPE_COMPLETE_QUESTS_IN_ZONE:
				case ACHIEVEMENT_CRITERIA_TYPE_HONORABLE_KILL_AT_AREA:
					$tmp['link'] = array(
						'href'	=> '?zone='.$row['value1'],
						'text'	=> $row['name'],
					);
					break;
				// Добавляем ссылку на квест
				case ACHIEVEMENT_CRITERIA_TYPE_COMPLETE_QUEST:
					$tmp['link'] = array(
						'href'	=> '?quest='.$row['value1'],
						'text'	=> $row['name'],
					);
					break;
				// Добавляем иконку и ссылку на спелл
				case ACHIEVEMENT_CRITERIA_TYPE_BE_SPELL_TARGET:
				case ACHIEVEMENT_CRITERIA_TYPE_BE_SPELL_TARGET2:
				case ACHIEVEMENT_CRITERIA_TYPE_CAST_SPELL:
				case ACHIEVEMENT_CRITERIA_TYPE_LEARN_SPELL:
				case ACHIEVEMENT_CRITERIA_TYPE_CAST_SPELL2:
					$tmp['link'] = array(
						'href'	=> '?spell='.$row['value1'],
						'text'	=> $row['name'],
					);
					$tmp['icon'] = $iconId;
					$achievement['icons'][] = array(
						'itr'	=> $iconId++,
						'type'	=> 'g_spells',
						'id'	=> $row['value1'],
					);
					allspellsinfo($row['value1']);
					break;
				// Добавляем ссылку и иконку предмета
				case ACHIEVEMENT_CRITERIA_TYPE_OWN_ITEM:
				case ACHIEVEMENT_CRITERIA_TYPE_USE_ITEM:
				case ACHIEVEMENT_CRITERIA_TYPE_LOOT_ITEM:
				case ACHIEVEMENT_CRITERIA_TYPE_EQUIP_ITEM:
					$item = allitemsinfo($row['value1']);
					$tmp['link'] = array(
						'href'	=> '?item='.$row['value1'],
						'text'	=> $row['name'],
						'quality' => $item['quality'],
					);
					$tmp['icon'] = $iconId;
					$achievement['icons'][] = array(
						'itr'	=> $iconId++,
						'type'	=> 'g_items',
						'id'	=> $row['value1'],
						'count'	=> $row['value2']
					);
					break;
				// Добавляем ссылку на фракцию и требуемую репутацию
				case ACHIEVEMENT_CRITERIA_TYPE_GAIN_REPUTATION:
					$tmp['link'] = array(
						'href'	=> '?faction='.$row['value1'],
						'text'	=> $row['name'],
					);
					$tmp['extra_text'] = ' ('.reputations($row['value2']).')';
					break;
				// Добавляем иконку золотой монетки
				case ACHIEVEMENT_CRITERIA_TYPE_MONEY_FROM_QUEST_REWARD:
				case ACHIEVEMENT_CRITERIA_TYPE_LOOT_MONEY:
					$tmp['standard'] = true;
					$tmp['extra_text'] = '<span class="moneygold">'.number_format($row['value2' ] / 10000).'</span>';
					break;
				// Добавляем ссылку на ГО
				case ACHIEVEMENT_CRITERIA_TYPE_USE_GAMEOBJECT:
				case ACHIEVEMENT_CRITERIA_TYPE_FISH_IN_GAMEOBJECT:
					$tmp['link'] = array(
						'href'	=> '?object='.$row['value1'],
						'text'	=> $row['name'],
					);
					break;
				default:
					$tmp['standard'] = true;
					$tmp['extra_text'] = $row['name'];
					break;
			}
			// Если столбик правый
			if($i++ % 2)
				$tmp_arr[] = $tmp;
			else
				$achievement['criterias'][] = $tmp;

		}
		// Если найден второй столбик - суем данные из него в конец основного массива
		if($tmp_arr)
			$achievement['criterias'] = array_merge($achievement['criterias'], $tmp_arr);


		/*************** ЦЕПОЧКА ДОСТИЖЕНИЙ ***************/

		$achievement['series'] = array(array(
			'id' => $achievement['id'],
			'name' => $achievement['name'],
			'parent' => $achievement['parent']
		));
		$tmp = $achievement['series'][0];
		while($tmp)
		{
			$tmp = $DB->selectRow('
					SELECT id, name_loc?d AS name, parent
					FROM ?_achievement
					WHERE id = ?
				',
				$_SESSION['locale'],
				$achievement['series'][0]['parent']
			);
			if($tmp)
				array_unshift($achievement['series'], $tmp);
		}
		$tmp = end($achievement['series']);
		while($tmp)
		{
			$end = end($achievement['series']);
			$tmp = $DB->selectRow('
					SELECT id, name_loc?d AS name, parent
					FROM ?_achievement
					WHERE parent = ?
				',
				$_SESSION['locale'],
				$end['id']
			);
			if($tmp)
				array_push($achievement['series'], $tmp);
		}
		if(count($achievement['series']) <= 1)
			unset($achievement['series']);

		save_cache(22, $cache_key, $achievement);
	}
}
global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Title' => $achievement['name'].' - '.$smarty->get_config_vars('Achievement'),
	'tab' => 0,
	'type' => 9,
	'typeid' => $achievement['id'],
	'path' => path(0, 9, $achievement['category1'], $achievement['category2']),
);
$smarty->assign('page', $page);

// Комментарии
$smarty->assign('comments', getcomments($page['type'], $page['typeid']));
// Статистика выполнения mysql запросов
$smarty->assign('mysql', $DB->getStatistics());
$smarty->assign('allitems', $allitems);
$smarty->assign('allspells', $allspells);
$smarty->assign('allachievements', $allachievements);
$smarty->assign('achievement', $achievement);
// Загружаем страницу
$smarty->display('achievement.tpl');
?>