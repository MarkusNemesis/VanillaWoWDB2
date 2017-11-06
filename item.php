<?php

require_once('includes/game.php');
require_once('includes/allspells.php');
require_once('includes/allquests.php');
require_once('includes/allitems.php');
require_once('includes/allnpcs.php');
require_once('includes/allobjects.php');
require_once('includes/allcomments.php');

// Загружаем файл перевода для smarty
$smarty->config_load($conf_file, 'item');

$id = intval($podrazdel);

$cache_key = cache_key($id);

if(!$item = load_cache(5, $cache_key))
{
	unset($item);

	// Информация о вещи...
	$item = iteminfo($id, 1);

	// Поиск мобов с которых эта вещь лутится
	$drops_cr = drop('creature_loot_template', $item['entry']);
	if($drops_cr)
	{
		$item['droppedby'] = array();
		foreach($drops_cr as $lootid => $drop)
		{
			$rows = $DB->select('
				SELECT c.?#, c.entry
				{
					, l.name_loc?d AS name_loc
					, l.subname_loc?d AS subname_loc
				}
				FROM ?_factiontemplate, creature_template c
				{ LEFT JOIN (locales_creature l) ON l.entry=c.entry AND ? }
				WHERE
					lootid=?d
					AND factiontemplateID=faction_A
				',
				$npc_cols[0],
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$lootid
			);
			foreach($rows as $row)
				$item['droppedby'][] = array_merge(creatureinfo2($row), $drop);
		}
		unset($rows);
		unset($lootid);
		unset($drop);
	}
	unset($drops_cr);

	// Поиск объектов, из которых лутится эта вещь
	$drops_go = drop('gameobject_loot_template', $item['entry']);
	if($drops_go)
	{
		$item['containedinobject'] = array();
		$item['minedfromobject'] = array();
		$item['gatheredfromobject'] = array();
		foreach($drops_go as $lootid => $drop)
		{
			// Сундуки
			$rows = $DB->select('
					SELECT g.entry, g.name, g.type, a.lockproperties1 {, l.name_loc?d AS name_loc}
					FROM ?_lock a, gameobject_template g
					{ LEFT JOIN (locales_gameobject l) ON l.entry=g.entry AND ? }
					WHERE
						g.data1=?d
						AND g.type=?d
						AND a.lockID=g.data0
				',
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$lootid,
				GAMEOBJECT_TYPE_CHEST,
				LOCK_PROPERTIES_HERBALISM,
				LOCK_PROPERTIES_MINING
			);
			foreach($rows as $row)
			{
				// Залежи руды
				if($row['lockproperties1'] == LOCK_PROPERTIES_MINING)
					$item['minedfromobject'][] = array_merge(objectinfo2($row), $drop);
				// Собирается с трав
				elseif($row['lockproperties1'] == LOCK_PROPERTIES_HERBALISM)
					$item['gatheredfromobject'][] = array_merge(objectinfo2($row), $drop);
				// Сундуки
				else
					$item['containedinobject'][] = array_merge(objectinfo2($row), $drop);
			}
		}

		if(!$item['containedinobject'])
			unset($item['containedinobject']);
		if(!$item['minedfromobject'])
			unset($item['minedfromobject']);
		if(!$item['gatheredfromobject'])
			unset($item['gatheredfromobject']);

		unset($rows);
	}
	unset($drops_go);

	// Поиск вендеров, которые эту вещь продают
	$rows_soldby = $DB->select('
			SELECT ?#, c.entry, v.maxcount AS stock
			{
				, l.name_loc?d AS name_loc
				, l.subname_loc?d AS subname_loc
			}
			FROM npc_vendor v, ?_factiontemplate, creature_template c
			{ LEFT JOIN (locales_creature l) ON l.entry=c.entry AND ? }
			WHERE
				v.item=?d
				AND c.entry=v.entry
				AND factiontemplateID=faction_A
			ORDER BY 1 DESC, 2 DESC
		',
		$npc_cols['0'],
		($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
		$item['entry']
	);
	if($rows_soldby)
	{
		$item['soldby'] = array();
		foreach($rows_soldby as $i => $row)
		{
			$item['soldby'][$i] = array();
			$item['soldby'][$i] = creatureinfo2($row);
			$item['soldby'][$i]['stock'] = ($row['stock'] == 0 ? -1 : $row['stock']);
			$item['soldby'][$i]['cost']['money'] = $item['BuyPrice'];
		}
		unset($extcost);
	}
	unset($rows_soldby);

	// Поиск квестов, для выполнения которых нужен этот предмет
	$rows_qr = $DB->select('
			SELECT q.?# {, l.Title_loc?d AS Title_loc}
			FROM quest_template q
			{ LEFT JOIN (locales_quest l) ON l.entry=q.entry AND ? }
			WHERE
				ReqItemId1=?d
				OR ReqItemId2=?d
				OR ReqItemId3=?d
				OR ReqItemId4=?d
		',
		$quest_cols[2],
		$_SESSION['locale'] > 0 ? $_SESSION['locale'] : DBSIMPLE_SKIP,
		$_SESSION['locale'] > 0 ? 1 : DBSIMPLE_SKIP,
		$item['entry'], $item['entry'], $item['entry'], $item['entry']
	);
	if($rows_qr)
	{
		$item['objectiveof'] = array();
		foreach($rows_qr as $row)
			$item['objectiveof'][] = GetQuestInfo($row, 0xFFFFFF);
	}
	unset($rows_qr);

	// Поиск квестов, наградой за выполнение которых, является этот предмет
	$rows_qrw = $DB->select('
			SELECT q.?# {, l.Title_loc?d AS Title_loc}
			FROM quest_template q 
			{ LEFT JOIN (locales_quest l) ON l.entry=q.entry AND ? }
			WHERE
				RewItemId1=?d
				OR RewItemId2=?d
				OR RewItemId3=?d
				OR RewItemId4=?d
				OR RewChoiceItemId1=?d
				OR RewChoiceItemId2=?d
				OR RewChoiceItemId3=?d
				OR RewChoiceItemId4=?d
				OR RewChoiceItemId5=?d
				OR RewChoiceItemId6=?d
		',
		$quest_cols[2],
		($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
		$item['entry'], $item['entry'], $item['entry'], $item['entry'], $item['entry'],
		$item['entry'], $item['entry'], $item['entry'], $item['entry'], $item['entry']
	);
	if($rows_qrw)
	{
		$item['rewardof'] = array();
		foreach($rows_qrw as $row)
			$item['rewardof'][] = GetQuestInfo($row, 0xFFFFFF);
	}
	unset($rows_qrw);

	// Поиск вещей, в которых находятся эти вещи
	$drops_cii = drop('item_loot_template', $item['entry']);
	if($drops_cii)
	{
		$item['containedinitem'] = array();
		foreach($drops_cii as $lootid => $drop)
		{
			$rows = $DB->select('
					SELECT c.?#, c.entry, maxcount
					{ , l.name_loc?d AS name_loc }
					FROM ?_icons, item_template c
					{ LEFT JOIN (locales_item l) ON l.entry=c.entry AND ? }
					WHERE
						c.entry=?d
						AND id=displayid
				',
				$item_cols[2],
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$lootid
			);
			$rows = sanitiseitemrows($rows);
			foreach($rows as $row)
				$item['containedinitem'][] = array_merge(iteminfo2($row, 0), $drop);
		}
		unset($drops_cii);
		unset($rows);
		unset($lootid);
		unset($drop);
	}

	// Какие вещи содержатся в этой вещи
	if(!($item['contains'] = loot('item_loot_template', $item['entry'])))
		unset($item['contains']);

	// Поиск созданий, у которых воруется вещь
	$drops_pp = drop('pickpocketing_loot_template', $item['entry']);
	if($drops_pp)
	{
		$item['pickpocketingloot'] = array();
		foreach($drops_pp as $lootid => $drop)
		{
			$rows = $DB->select('
					SELECT c.?#, c.entry
					{
						, l.name_loc?d AS name_loc
						, l.subname_loc?d AS subname_loc
					}
					FROM ?_factiontemplate, creature_template c
					{ LEFT JOIN (locales_creature l) ON l.entry=c.entry AND ? }
					WHERE
						pickpocketloot=?d
						AND factiontemplateID=faction_A
				',
				$npc_cols[0],
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$lootid
			);
			foreach($rows as $row)
				$item['pickpocketingloot'][] = array_merge(creatureinfo2($row), $drop);
		}
		unset($rows);
		unset($lootid);
		unset($drop);
	}
	unset($drops_pp);

	// Поиск созданий, с которых сдираеццо эта шкура
	$drops_sk = drop('skinning_loot_template', $item['entry']);
	if($drops_sk)
	{
		$item['skinnedfrom'] = array();
		foreach($drops_sk as $lootid => $drop)
		{
			$rows = $DB->select('
					SELECT c.?#, c.entry
					{
						, l.name_loc?d AS name_loc
						, l.subname_loc?d AS subname_loc
					}
					FROM ?_factiontemplate, creature_template c
					{ LEFT JOIN (locales_creature l) ON l.entry=c.entry AND ? }
					WHERE
						skinloot=?d
						AND factiontemplateID=faction_A
				',
				$npc_cols[0],
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$lootid
			);
			foreach($rows as $row)
				$item['skinnedfrom'][] = array_merge(creatureinfo2($row), $drop);
		}
		unset($rows);
		unset($lootid);
		unset($drop);
	}
	unset($drops_sk);

	// Дизенчантитcя в:
	if(!($item['disenchanting'] = loot('disenchant_loot_template', $item['DisenchantID'])))
		unset($item['disenchanting']);

	// Получается дизэнчантом из..
	$drops_de = drop('disenchant_loot_template', $item['entry']);
	if($drops_de)
	{
		$item['disenchantedfrom'] = array();
		foreach($drops_de as $lootid => $drop)
		{
			$rows = $DB->select('
					SELECT c.?#, c.entry, maxcount
					{
						, l.name_loc?d AS name_loc
					}
					FROM ?_icons, item_template c
					{ LEFT JOIN (locales_item l) ON l.entry=c.entry AND ? }
					WHERE
						DisenchantID=?d
						AND id=displayid
				',
				$item_cols[2],
				($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
				($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
				$lootid
			);
			$rows = sanitiseitemrows($rows);
			foreach($rows as $row)
				$item['disenchantedfrom'][] = array_merge(iteminfo2($row, 0), $drop);
		}
		unset($rows);
		unset($lootid);
		unset($drop);
	}
	unset($drops_de);

	// Поиск сумок в которые эту вещь можно положить
	if($item['BagFamily'] == 256)
	{
		// Если это ключ
		$item['key'] = true;
	}
	elseif($item['BagFamily'] > 0 and $item['ContainerSlots'] == 0)
	{
		$rows_cpi = $DB->select('
				SELECT c.?#, c.entry, maxcount
				{
					, l.name_loc?d AS name_loc
				}
				FROM ?_icons, item_template c
				{ LEFT JOIN (locales_item l) ON l.entry=c.entry AND ? }
				WHERE
					BagFamily=?d
					AND ContainerSlots>0
					AND id=displayid
			',
			$item_cols[2],
			($_SESSION['locale']>0)? $_SESSION['locale']: DBSIMPLE_SKIP,
			($_SESSION['locale']>0)? 1: DBSIMPLE_SKIP,
			$item['BagFamily']
		);
		
		if($rows_cpi)
		{
			$rows_cpi = sanitiseitemrows($rows_cpi);
			$item['canbeplacedin'] = array();
			foreach($rows_cpi as $row)
				$item['canbeplacedin'][] = iteminfo2($row, 0);
		}
		unset($rows_cpi);
	}

	// Реагент для...
	$rows_r = $DB->select('
			SELECT ?#, spellID
			FROM ?_spell s, ?_spellicons i
			WHERE
				(( reagent1=?d
				OR reagent2=?d
				OR reagent3=?d
				OR reagent4=?d
				OR reagent5=?d
				OR reagent6=?d
				OR reagent7=?d
				OR reagent8=?d
				) AND ( i.id=s.spellicon))
		',
		$spell_cols[2],
		$item['entry'], $item['entry'], $item['entry'], $item['entry'],
		$item['entry'], $item['entry'], $item['entry'], $item['entry']
	);
	if($rows_r)
	{
		$item['reagentfor'] = array();
		$quality = 1;
		foreach($rows_r as $i=>$row)
		{
			$item['reagentfor'][$i] = array();
			$item['reagentfor'][$i]['entry'] = $row['spellID'];
			$item['reagentfor'][$i]['name'] = $row['spellname_loc'.$_SESSION['locale']];
			$item['reagentfor'][$i]['school'] = $row['resistancesID'];
			$item['reagentfor'][$i]['level'] = $row['levelspell'];
			$item['reagentfor'][$i]['quality'] = '@';
			for ($j=1;$j<=8;$j++)
				if($row['reagent'.$j])
				{
					$item['reagentfor'][$i]['reagents'][]['entry'] = $row['reagent'.$j];
					$item['reagentfor'][$i]['reagents'][count($item['reagentfor'][$i]['reagents'])-1]['count'] = $row['reagentcount'.$j];
					allitemsinfo($row['reagent'.$j], 0);
				}
			for ($j=1;$j<=3;$j++)
				if($row['effect'.$j.'itemtype'])
				{
					$item['reagentfor'][$i]['creates'][]['entry'] = $row['effect'.$j.'itemtype'];
					$item['reagentfor'][$i]['creates'][count($item['reagentfor'][$i]['creates'])-1]['count'] = 1 + $row['effect'.$j.'BasePoints'];
					allitemsinfo($row['effect'.$j.'itemtype'], 0);
					@$item['reagentfor'][$i]['quality'] = 7 - $allitems[$row['effect'.$j.'itemtype']]['quality'];
				}
			// Добавляем в таблицу спеллов
			allspellsinfo2($row);
		}
		unset($quality);
	}
	unset($rows_r);

	// Создается из...
	$rows_cf = $DB->select('
			SELECT ?#, s.spellID
			FROM ?_spell s, ?_spellicons i
			WHERE
				((s.effect1itemtype=?d
				OR s.effect2itemtype=?d
				OR s.effect3itemtype=?)
				AND (i.id = s.spellicon))
		',
		$spell_cols[2],
		$item['entry'], $item['entry'], $item['entry']
	);
	if($rows_cf)
	{
		$item['createdfrom'] = array();
		foreach($rows_cf as $row)
		{
			$skillrow = $DB->selectRow('
					SELECT skillID, min_value, max_value
					FROM ?_skill_line_ability
					WHERE spellID=?d
					LIMIT 1
				',
				$row['spellID']
			);
			$item['createdfrom'][] = spellinfo2(array_merge($row, $skillrow));
		}
		unset($skillrow);
	}
	unset($rows_cf);

	// Ловится в ...
	$drops_fi = drop('fishing_loot_template', $item['entry']);
	if($drops_fi)
	{
		$item['fishedin'] = array();
		foreach($drops_fi as $lootid => $drop)
		{
			// Обычные локации
			$row = $DB->selectRow('
					SELECT name_loc'.$_SESSION['locale'].' AS name, areatableID as id
					FROM ?_zones
					WHERE
						areatableID=?d
						AND (x_min!=0 AND x_max!=0 AND y_min!=0 AND y_max!=0)
					LIMIT 1
				',
				$lootid
			);
			if($row)
			{
				$item['fishedin'][] = array_merge($row, $drop);
			}
			else
			{
				// Инсты
				$row = $DB->selectRow('
						SELECT name_loc'.$_SESSION['locale'].' AS name, mapID as id
						FROM ?_zones
						WHERE
							areatableID=?d
						LIMIT 1
					',
					$lootid
				);
				if($row)
					$item['fishedin'][] = array_merge($row, $drop);
			}
		}
		unset($row);
		unset($num);
	}
	unset($drops_fi);

	save_cache(5, $cache_key, $item);
}
global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Title' => $item['name'].' - '.$smarty->get_config_vars('Items'),
	'tab' => 0,
	'type' => 3,
	'typeid' => $item['entry'],
	'path' => path(0, 0, $item['type'], $item['subclass'], $item['classs']),
);
$smarty->assign('page', $page);

// Комментарии
$smarty->assign('comments', getcomments($page['type'], $page['typeid']));

// Количество MySQL запросов
$smarty->assign('mysql', $DB->getStatistics());
$smarty->assign('allitems', $allitems);
$smarty->assign('allspells', $allspells);
$smarty->assign('item', $item);
$smarty->display('item.tpl');
?>