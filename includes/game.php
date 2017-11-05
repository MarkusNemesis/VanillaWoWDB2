<?php

require_once('includes/alllocales.php');

// Функция информации о фракции
function factioninfo($id)
{
	global $DB;
	$faction['name'] = $DB->selectCell('SELECT name_loc'.$_SESSION['locale'].' FROM ?_factions WHERE factionID = ?d LIMIT 1', $id);
	$faction['entry'] = $id;
	return $faction;
}

function add_loot(&$loot, $newloot)
{
	// Записываем все существующие в луте итемы в массив
	$exist = array();
	foreach($loot as $offset => $item)
		$exist[$item['entry']] = $offset;

	foreach($newloot as $newitem)
	{
		// MUST NOT HAPPEN
		if(!is_array($newitem))
			return;

		// Если в луте есть такая вещь
		if(isset($exist[$newitem['entry']]))
		{
			$loot[$exist[$item['entry']]]['mincount'] = min($loot[$exist[$item['entry']]]['mincount'], $newitem['mincount']);
			$loot[$exist[$item['entry']]]['maxcount'] = max($loot[$exist[$item['entry']]]['maxcount'], $newitem['maxcount']);
			$loot[$exist[$item['entry']]]['percent'] += $newitem['percent'];
			//$loot[$exist[$item['entry']]]['group'] = 0;
		}
		else
			$loot[] = $newitem;
	}
}

// Что дропает
function loot($table, $lootid, $mod = 1)
{
	// Все элементы
	global $DB, $item_cols;
	$loot = array();
	$groups = array();
	// Мего запрос :)
	$rows = $DB->select('
		SELECT l.ChanceOrQuestChance, l.mincountOrRef, l.maxcount, l.groupid, ?#, i.entry
			{, loc.name_loc?d AS name_loc}
		FROM ?# l
			LEFT JOIN (?_icons a, item_template i) ON l.item=i.entry AND a.id=i.displayid
			{LEFT JOIN (locales_item loc) ON loc.entry=i.entry AND ?d}
		WHERE
			l.entry=?d
		ORDER BY groupid ASC, ChanceOrQuestChance DESC
		{LIMIT ?d}
		',
		$item_cols[2],
		($_SESSION['locale'])? $_SESSION['locale']: DBSIMPLE_SKIP,
		$table,
		($_SESSION['locale'])? 1: DBSIMPLE_SKIP,
		$lootid,
		($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
	);
	// Check patch validity
	$rows = sanitiseitemrows($rows);
	
	$last_group = 0;
	$last_group_equal_chance = 100;
	
	// Перебираем
	foreach($rows as $row)
	{
		// Не группа
		if($row['groupid'] == 0)
		{
			// Ссылка
			if($row['mincountOrRef'] < 0)
				add_loot($loot, loot('reference_loot_template', -$row['mincountOrRef'], abs($row['ChanceOrQuestChance']) / 100 * $row['maxcount'] * $mod));
			else
				// Обыкновенный дроп
				add_loot($loot, array(array_merge(array(
						'percent'  => max(abs($row['ChanceOrQuestChance']) * $mod, 0)*sign($row['ChanceOrQuestChance']),
						'mincount' => $row['mincountOrRef'],
						'maxcount' => $row['maxcount'],
						'group' => $row['groupid']
					),
					iteminfo2($row, 0)
				)));
		}
		// Группа
		else
		{
			$chance = abs($row['ChanceOrQuestChance']);
			// Новая группа?
			if($row['groupid'] <> $last_group)
			{
				$last_group = $row['groupid'];
				$last_group_equal_chance = 100;
			}

			// Шанс лута задан
			if($chance > 0)
			{
				$last_group_equal_chance -= $chance;
				$last_group_equal_chance = max($last_group_equal_chance, 0);

				// Ссылка
				if($row['mincountOrRef'] < 0)
				{
					add_loot($loot, loot('reference_loot_template', -$row['mincountOrRef'], $chance / 100 * $row['maxcount'] * $mod));
				}
				else
					add_loot($loot, array(array_merge(array(
							'percent'  => $chance * $mod,
							'mincount' => $row['mincountOrRef'],
							'maxcount' => $row['maxcount'],
							'group' => $row['groupid']
						),
						iteminfo2($row, 0)
					)));
			}
			// Шанс не задан, добавляем эту группу в группы
			else
			{
				$groups[$last_group][] = array_merge(array(
						'mincount' => $row['mincountOrRef'],
						'maxcount' => $row['maxcount'],
						'groupchance'=>$last_group_equal_chance * $mod,
						'group' => $row['groupid']
					),
					iteminfo2($row, 0)
				);
			}
		}
	}

	// Перебираем и добавляем группы
	foreach($groups as $group)
	{
		$num = count($group);
		foreach($group as $item)
		{
			$item['percent'] = $item['groupchance'] / $num;
			add_loot($loot, array($item));
		}
	}
	return $loot;
}

// Кто дропает
function drop($table, $item)
{
	global $DB, $AoWoWconf;

	$total = 0;

	// Реверсный поиск лута начиная с референсной таблицы
	// Ищем в группах
	$drop = array();
	$curtable = 'reference_loot_template';
	$rows = $DB->select('
			SELECT entry, groupid, ChanceOrQuestChance, mincountOrRef, maxcount
			FROM ?#
			WHERE
				item = ?
				AND mincountOrRef > 0
		',
		$curtable,
		$item
	);
	while(true)
	{
		foreach($rows as $i => $row)
		{
			$chance = abs($row['ChanceOrQuestChance']);
			if($chance == 0)
			{
				// Запись из группы с равным шансом дропа, считаем реальную вероятность
				$zerocount = 0;
				$chancesum = 0;
				$subrows = $DB->select('
						SELECT ChanceOrQuestChance, mincountOrRef, maxcount
						FROM ?#
						WHERE entry = ? AND groupid = ?
					',
					$curtable,
					$row['entry'],
					$row['groupid']
				);
				foreach($subrows as $i => $subrow)
				{
					if($subrow['ChanceOrQuestChance'] == 0)
						$zerocount++;
					else
						$chancesum += abs($subrow['ChanceOrQuestChance']);
				}
				$chance = (100 - $chancesum) / $zerocount;
			}
			$chance = max($chance, 0);
			$chance = min($chance, 100);
			$mincount = $row['mincountOrRef'];
			$maxcount = $row['maxcount'];

			if($mincount < 0)
			{
				// Референсная ссылка. Вероятность основывается на уже подсчитанной.
				$num = $mincount;
				$mincount = $drop[$num]['mincount'];
				$chance = $chance * (1 - pow(1 - $drop[$num]['percent']/100, $maxcount));
				$maxcount = $drop[$num]['maxcount']*$maxcount;
			}

			// Сохраняем подсчитанные для этих групп вероятности
			//(референсные записи хранятся с отрицательными номерами)
			$num = ($curtable <> $table) ? -$row['entry'] : $row['entry'];
			if(isset($drop[$num]))
			{
				// Этот же элемент уже падал в другой подгруппе - считаем общую вероятность.
				$newmin =($drop[$num]['mincount'] < $mincount) ? $drop[$num]['mincount'] : $mincount;
				$newmax = $drop[$num]['maxcount'] + $maxcount;
				$newchance = 100 - (100 - $drop[$num]['percent'])*(100-$chance)/100;
				$drop[$num]['percent'] = $newchance;
				$drop[$num]['mincount'] = $newmin;
				$drop[$num]['maxcount'] = $newmax;
			}
			else
			{
				$drop[$num] = array();
				$drop[$num]['percent'] = $chance;
				$drop[$num]['mincount'] = $mincount;
				$drop[$num]['maxcount'] = $maxcount;
				$drop[$num]['checked'] = false;

				if($AoWoWconf['limit'] > 0 && $num > 0 && ++$total > $AoWoWconf['limit'])
					break;
			}
		}

		// Ищем хоть одну непроверенную reference-ную запись
		$num = 0;
		foreach($drop as $i => $value)
		{
			if($i < 0 && !$value['checked'])
			{
				$num = $i;
				break;
			}
		}

		// Нашли?
		if($num == 0)
		{
			// Все элементы проверены
			if($curtable != $table)
			{
				// но это была reference-ная таблица - надо поискать в основной
				$curtable = $table;

				foreach($drop as $i => $value)
					$drop[$i]['checked'] = false;

				$rows = $DB->select('
						SELECT entry, groupid, ChanceOrQuestChance, mincountOrRef, maxcount
						FROM ?#
						WHERE
							item = ?
							AND mincountOrRef > 0
					',
					$curtable,
					$item
				);
			}
			else
				// Если ничего не нашли и в основной таблице, то поиск закончен
				break;
		}
		else
		{
			// Есть непроверенный элемент, надо его проверить
			$drop[$num]['checked'] = true;
			$rows = $DB->select('
					SELECT entry, groupid, ChanceOrQuestChance, mincountOrRef, maxcount
					FROM ?#
					WHERE mincountOrRef = ?
				',
				$curtable,
				$num
			);
		}
	}

	// Чистим reference-ные ссылки
	foreach($drop as $i => $value)
		if($i < 0)
			unset($drop[$i]);

	return $drop;
}



// Функция преобразует координаты из серверных в игровые
function transform_point($at, $point)
{
	$result = $point;

	$result['x'] = round(100 - ($point['y']-$at['y_min']) / (($at['y_max']-$at['y_min']) / 100), 2);
	$result['y'] = round(100 - ($point['x']-$at['x_min']) / (($at['x_max']-$at['x_min']) / 100), 2);
	$result['r'] = sec_to_time($point['spawntimesecs']);
	unset($result['spawntimesecs']);

	return $result;
}

// Функция выбирает подходящую зону для точки из списка
function select_zone($at_data, $point)
{
	global $cached_images, $at_dataoffsets;

	$chosen_area = 0;
	// Сначала ищем в закешированных локациях
	$matching_locations = array();
	foreach($at_data as $at)
	{
		if($at['mapID'] == $point['m'] && $at['x_min'] <= $point['x'] && $at['x_max'] >= $point['x'] && $at['y_min'] <= $point['y'] && $at['y_max'] >= $point['y'])
		{
			// Если нам не сказано игнорировать негативы
			if(!$point['n'])
			{
				if(!$cached_images[$at['areatableID']])
				{
					$filename = 'images/tmp/'.$at['areatableID'].'.png';
					if(!file_exists($filename))
						continue; // Не существует - пропускаем зону

					$cached_images[$at['areatableID']] = imagecreatefrompng($filename);
				}

				$game_x = 100 - ($point['y']-$at['y_min']) / (($at['y_max']-$at['y_min']) / 100);
				$game_y = 100 - ($point['x']-$at['x_min']) / (($at['x_max']-$at['x_min']) / 100);

				if(imagecolorat($cached_images[$at['areatableID']], round($game_x * 10), round($game_y * 10)) !== 0)
					continue;
			}

			$matching_locations[] = $at['areatableID'];
		}
	}
	if($matching_locations)
	{
		if(count($matching_locations) > 1)
		{
			// TODO: а такое бывает? поидеи да, со столицами
			// Из нескольких локаций выбираем самую маленькую
			$chosen_area = $matching_locations[0];
			foreach($matching_locations as $loc)
			{
				$our = $at_data[$at_dataoffsets[$chosen_area]];
				$chk = $at_data[$at_dataoffsets[$loc]];
				// Если эта карта меньше выбранной вначале
				if(abs($our['x_max']-$our['x_min']) > abs($chk['x_max']-$chk['x_min']) || abs($our['y_max']-$our['y_min']) > abs($chk['y_max']-$chk['y_min']))
					$chosen_area = $loc;
			}
		}
		else
			$chosen_area = $matching_locations[0];
	}
	return $chosen_area;
}

// Функция преобразовывает массив серверных координат
// в массив координат и локаций клиента
function transform_coords($recv)
{
	global $DB, $at_dataoffsets;
	// Cached data
	$map_data = array();
	$at_data = array();
	$map_dataoffsets = array();
	$zone_dataoffsets = array();
	$at_dataoffsets = array();
	$at_dataoffsets_offset = 0;
	$loaded_areas = array();

	$data = array();
	$i = -1; // на самом деле в этой переменной хранится id последнего элемента в $data

	// Собираем номера всех карт, где находятся точки
	$mapids = array();
	foreach($recv as $point)
	{
		if(!in_array($point['m'], $mapids))
			$mapids[] = $point['m'];
	}
	// Считываем сколько всего карт существует
	$result = $DB->select('SELECT mapID, areatableID, name_loc?d AS name, x_min, COUNT(*) AS c FROM ?_zones WHERE mapID IN (?a) GROUP BY mapID', $_SESSION['locale'], $mapids);

	if(!$result)
		return false;

	foreach($result as $record)
	{
		$mapid = $record['mapID'];
		$atid = $record['areatableID'];
		$map_data[$mapid] = array(
			'name' => $record['name']
		);
		// Для этой карты существует всего одна локация,
		// причем неизвестно, есть для нее карта или нет.
		if($record['c'] == 1)
		{
			if(file_exists('images/maps/enus/normal/'.$atid.'.jpg'))
			{
				$map_data[$mapid]['atid'] = $atid;
				// Это хак, но пусть так
				if($record['x_min'] == 0)
					$map_data[$mapid]['coords_not_available'] = true;
				else
					// Задаем директиву не использовать негативы
					$map_data[$mapid]['ignore_negatives'] = true;
			}
			else
				// Карта не доступна - записываем, что бы потом не жрать ресурсы.
				$map_data[$mapid]['map_not_available'] = true;
		}
		else
			$map_data[$mapid]['multiple_areas'] = true;
	}

	foreach($recv as $point)
	{
		$mapid = $point['m'];
		$md = $map_data[$mapid];
		$point['n'] = $md['ignore_negatives'];
		// Карта не доступна
		if($md['map_not_available'] || $md['coords_not_available'])
		{
			if(isset($map_dataoffsets[$mapid]))
			{
				
				// Если у нас уже была точка с такой картой,
				// то увеличиваем население на ней.
				if ($point['type'] <> 3)
					$data[$map_dataoffsets[$mapid]]['population']++;
			}
			else
			{
				// Если нет - создаем новую.
				if ($point['type'] <> 3)
					$data[++$i] = array(
						'population' => 1,
						'name' => $md['name'],
						'atid' => $md['map_not_available'] ? 0 : $md['atid']
					);
				$map_dataoffsets[$mapid] = $i;
			}
			continue;
		}
		// Если всего одна зона на карту,
		// и для нее есть изображение и координаты,
		// сразу же указываем номер зоны
		if(!$md['multiple_areas'])
			$chosen_area = $md['atid'];
		else
			$chosen_area = select_zone($at_data, $point);

		// Если зон на карту много и/или нужная нам не загружена
		if(!$chosen_area || !in_array($chosen_area, $loaded_areas))
		{
			// Загружаем зоны
			$result = $DB->select('
					SELECT mapID, areatableID, x_min, x_max, y_min, y_max, name_loc?d AS name
					FROM ?_zones
					WHERE
						(? BETWEEN x_min AND x_max)
						AND (? BETWEEN y_min AND y_max)
						AND mapID = ?
				',
				$_SESSION['locale'],
				$point['x'],
				$point['y'],
				$point['m']
			);
			foreach($result as $area)
			{
				$loaded_areas[] = $area['areatableID'];
				$at_dataoffsets[$area['areatableID']] = $at_dataoffsets_offset++;
			}
			$at_data = array_merge($at_data, $result);
			$chosen_area = select_zone($at_data, $point);
		}
		// Если зона так и не найдена (исключительный случай)
		// просто этой точки не будет на карте.

		// Если мы в финальном массиве уже имеем запись на
		// данный номер зоны
		if(isset($zone_dataoffsets[$chosen_area]))
		{
			$offset = $zone_dataoffsets[$chosen_area];
			// Если у нас уже была точка с такой картой,
			// то увеличиваем население на ней, записываем новую точку
			if ($point['type'] <> 3)
				$data[$offset]['population']++;

			if($chosen_area)
				$data[$offset]['points'][] = transform_point($at_data[$at_dataoffsets[$chosen_area]], $point);
		}
		else
		{
			$points_array = $chosen_area ? array(transform_point($at_data[$at_dataoffsets[$chosen_area]], $point)) : array();
			// Если нет - создаем новую.
			if ($point['type'] <> 3)
				$data[++$i] = array(
					'population' => 1,
					'name' => $at_data[$at_dataoffsets[$chosen_area]]['name'],
					'atid' => $chosen_area,
					'points' => $points_array
				);
			$zone_dataoffsets[$chosen_area] = $i;
		}
	}

	return $data;
}

// Функция создает полный и окончательный массив информации о местоположении объектов или НПС
function position($id, $type, $spawnMask = 0)
{
	global $smarty, $exdata, $zonedata, $DB, $AoWoWconf, $cached_images;

	$data = $DB->select('
			SELECT guid, map AS m, position_x AS x, position_y AS y, spawntimesecs, {MovementType AS ?#, }"0" AS `type`
			FROM '.$type.'
			WHERE id = ?d
			{ GROUP BY ROUND(x,?d), ROUND(y,?d) }
			ORDER BY x,y
		',
		($type == 'gameobject' ? DBSIMPLE_SKIP : 'mt'),
		$id,
		$spawnMask ? $spawnMask : DBSIMPLE_SKIP,
		$AoWoWconf['map_grouping'] > 0 ? -$AoWoWconf['map_grouping'] : DBSIMPLE_SKIP,
		$AoWoWconf['map_grouping'] > 0 ? -$AoWoWconf['map_grouping'] : DBSIMPLE_SKIP
	);
	
	if($type <> 'gameobject')
	{
		$wpWalkingCreaturesGuids = array();
		foreach($data as $spawnid => $spawn)
		{
			if($spawn['mt'] == 2)
				$wpWalkingCreaturesGuids[] = $spawn['guid'];
		}
		if($wpWalkingCreaturesGuids)
		{
			$wps = $DB->select('
					SELECT c.map AS m, m.position_x AS x, m.position_y AS y, "3" AS `type`
					FROM creature_movement m, creature c
					WHERE
						m.id = c.guid
						AND m.id IN (?a)
					{ GROUP BY ROUND(x,?d), ROUND(y,?d) }
					ORDER BY x,y
				',
				$wpWalkingCreaturesGuids,
				$AoWoWconf['map_grouping'] > 0 ? -$AoWoWconf['map_grouping'] : DBSIMPLE_SKIP,
				$AoWoWconf['map_grouping'] > 0 ? -$AoWoWconf['map_grouping'] : DBSIMPLE_SKIP
			);
			$data = array_merge($data, $wps);
		}
	}
	
	foreach ($data as $zone) {
		//echo $zone['type'] . ', (' . $zone['x'] . ','  . $zone['y'] . ')<br>';
		foreach ($zone as $spawn) {
			// echo $spawn['type'];
		}
	}
	
	if($data)
	{
		$data = transform_coords($data);

		// Сортируем массив
		do
		{
			$changed = false;
			for($i = 0; $i < count($data); $i++)
			{
				// $l - предыдущий элемент массива
				if(isset($l) && $data[$l]['population'] < $data[$i]['population'])
				{
					$tmp = $data[$l];
					$data[$l] = $data[$i];
					$data[$i] = $tmp;
					$changed = true;
				}
				$l = $i;
			}
			unset($l);
		} while($changed);

		// Удаляем карты
		if($cached_images)
			foreach($cached_images as $img)
				imagedestroy($img);

		return $data;
	}
}


//// Location support

// Get NPC Location - I have no idea how this works, but all I know is that it does...
function coord_tozone($mapid, $x, $y, $global) {

    global $map_images;

    global $DB;

    $rows = $DB->select("SELECT * FROM aowow_zones WHERE (mapID=? and x_min<? and x_max>? and y_min<? and y_max>?)", $mapid, $x, $x, $y, $y);

    foreach ($rows as $numRow => $row) {
        $wow['zone'] = $row['areatableID'];
        $wow['name'] = $row['name_loc' . $_SESSION['locale']];

        $tx = 100 - ($y - $row["y_min"]) / (($row["y_max"] - $row["y_min"]) / 100);
        $ty = 100 - ($x - $row["x_min"]) / (($row["x_max"] - $row["x_min"]) / 100);

        if (!isset($map_images[$wow['zone']])) {
            $mapname = str_replace("\\", "/", getcwd()) . '/images/tmp/' . $row['areatableID'] . '.png';
            if (file_exists($mapname)) {
                $map_images[$wow['zone']] = @ImageCreateFromPNG($mapname);
            }
        }

        if ($map_images[$wow['zone']]) {
            if (@ImageColorAt($map_images[$wow['zone']], round($tx * 10), round($ty * 10)) === 0) {
                break;
            }
        }
    }

    if (count($rows) == 0) 
	{
        $row = $DB->selectRow("SELECT * FROM ?_zones WHERE (mapID=? and x_min=0 and x_max=0 and y_min=0 and y_max=0)", $mapid);
        if ($row) {
            $wow['zone'] = $row['areatableID'];
            $wow['name'] = $row['name_loc' . $_SESSION['locale']];
        }
    }

    $wow['x'] = round($tx, 4);
    $wow['y'] = round($ty, 4);

    return $wow;
}

function resolve_coord(&$data) {
    global $map_images;

    $guids = array();
    $xdata = array();

    foreach ($data as $ndata) {
        if (isset($ndata['spawntimesecs']))
        {
            $coord_tozone =  coord_tozone($ndata['m'], $ndata['x'], $ndata['y'], false) ?: array();
            $tmp = array_merge($coord_tozone, array('r' => sec_to_time($ndata['spawntimesecs'])));
        }
        // else
        // {
            // $tmp = coord_tozone($ndata['m'], $ndata['x'], $ndata['y'], false);
        // }

        $tmp['t'] = $ndata['type'];
        $xdata[] = $tmp;
    }

    return $xdata;
}

function getLocation($id, $type) {
    global $smarty, $exdata, $zonedata, $DB, $UDWBaseconf;

    $data = $DB->select('
		SELECT guid, map AS m, position_x AS x, position_y AS y, spawntimesecs, {MovementType AS ?#, }"0" AS `type` FROM ' . $type . ' 
		WHERE id = ?d 
		GROUP BY ROUND(x,?d), ROUND(y,?d) 
		ORDER BY x,y
		', 
		($type == 'gameobject' ? DBSIMPLE_SKIP : 'mt'), 
		$id,
		3,
		3
	);

	$locations = '';
    if (count($data) > 0) {
        $data = resolve_coord($data);

        if ($data)
            sort($data);

		$arr_loc = array();
		foreach ($data as $loc)
		{
			$arr_loc[] = $loc['zone'];
			//echo $loc['name'];
		}
		
		foreach (array_unique($arr_loc) as $loc)
		{
			
			if ($locations == '') {
				$locations = $loc;
			} else {
				$locations = $locations . ',' . $loc;
			}
		}
    }
	
	return $locations;
}

///// END LOCATION //////


?>