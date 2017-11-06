<?php

require_once('includes/game.php');
require_once('includes/allitems.php');
require_once('includes/allcomments.php');
//require_once('includes/allscreenshots.php');
$smarty->config_load($conf_file, 'zone');

$id = $podrazdel;

if(!$zone = load_cache(16, $id))
{
	unset($zone);

	// Данные об объекте:
	$zone = $DB->selectRow('SELECT * FROM ?_zones WHERE areatableID = ?d', $id);
	
	$zone['name'] = $zone['name_loc0'];//localizedName($zone);

	if(!($zone['fishing'] = loot('fishing_loot_template', $id)))
		unset($zone['fishing']);

	// if($zone['parent'])
	// {
		// $row = $DB->selectRow('SELECT * FROM ?_zones WHERE areatableID = ?d', $zone['parent']);
		// if ($row)
			// $parentname = $row['name'];//localizedName($row);
		// unset($row);
		// if (!empty($parentname))
			// $zone['parentname'] = $parentname;
	// }

	// $rows = $DB->select('SELECT * FROM ?_aowow_zones WHERE parent = ?d', $id);
	// if ($rows)
	// {
		// $zone['subzones'] = array();
		// foreach($rows as $row)
			// $zone['subzones'][] = array(
				// 'id' => $row['areatableID'],
				// 'name' => $row['name']//localizedName($row)
			// );
	// }
	unset($rows);

	$zone['position'] = array();

	// Optimized version of position() + transform_coords() without map mask check
	if ($zone['x_min'] && $zone['y_min'] && $zone['x_max'] && $zone['y_max'])
	{
		// Flight masters
		$rows = $DB->select('
			SELECT ct.entry, ct.name, ct.subname, ct.faction_A, lc.name_loc?d, lc.subname_loc?d, ct.npcflag, position_x, position_y
			FROM creature c, creature_template ct
			LEFT JOIN locales_creature lc ON ct.entry = lc.entry
			WHERE c.id = ct.entry
			  AND (ct.npcflag = 11 OR ct.npcflag = 16388 OR ct.npcflag = 33 OR ct.npcflag= 135)
			  AND c.map = ?d
			  AND c.position_x > ?f
			  AND c.position_x < ?f
			  AND c.position_y > ?f
			  AND c.position_y < ?f
			',
			$_SESSION['locale'] > 0 ? $_SESSION['locale'] : 1,
			$_SESSION['locale'] > 0 ? $_SESSION['locale'] : 1,
			$zone['mapID'],
			$zone['x_min'],
			$zone['x_max'],
			$zone['y_min'],
			$zone['y_max']);
		if ($rows)
		{
			$taxies = array(
				'population' => 0,
				'name' => 'Flight Masters', // TODO: LOCALIZE!
				'atid' => $id,
				'points' => array()
			);
			$inns = array(
				'population' => 0,
				'name' => 'Innkeepers', // TODO: LOCALIZE!
				'atid' => $id,
				'points' => array()
			);
			$repairers = array(
				'population' => 0,
				'name' => 'Repairers', // TODO: LOCALIZE!
				'atid' => $id,
				'points' => array()
			);
			$spirithealers = array(
				'population' => 0,
				'name' => 'Spirit Healers', // TODO: LOCALIZE!
				'atid' => $id,
				'points' => array()
			);
			foreach($rows as $row)
			{
				$name = $row['name'];//localizedName($row);
				$subname = $row['subname'];//localizedName($row, "subname");
				if ($subname)
					$name = $name . " <" . $subname . ">";
				
				// Get Faction for Type (pin colour)
				$faction = $DB->selectRow('
					SELECT c.entry, factiontemplateID, A, H
					FROM ?_factiontemplate, creature_template c
					{
						LEFT JOIN (locales_creature l)
						ON l.entry=c.entry
					}
					WHERE
						c.entry=?d
						AND factiontemplateID=faction_A
					LIMIT 1 
				', $row['entry']
				);

				//echo $row['faction_A'];
				
				if ($faction['A'] == 1 && $faction['H'] == -1) {
					$type = 3; // 3 is blue
				} 
				
				if ($faction['A'] == -1 && $faction['H'] == 1) {
					$type = 2; // 2 is red
				}
				
				if ($faction['A'] == 1 && $faction['H'] == 1) {
					$type = 0; // 0 is yellow
				}
				
				$point = array(
					'name' => $name,
					'type' => $type, // affects pin color (style=pin-$type)
					'url' => '?npc='.$row['entry'],
					'x' => round(100 - ($row['position_y']-$zone['y_min']) / (($zone['y_max']-$zone['y_min']) / 100), 2),
					'y' => round(100 - ($row['position_x']-$zone['x_min']) / (($zone['x_max']-$zone['x_min']) / 100), 2)
				);

				if ($row['npcflag']==11)
				{	
					$taxies['population']++;
					$taxies['points'][] = $point;
				}
				if ($row['npcflag']==135)
				{
					$inns['population']++;
					$inns['points'][] = $point;
				}
				if ($row['npcflag']==16388)
				{
					$repairers['population']++;
					$repairers['points'][] = $point;
				}
				if ($row['npcflag']==33) //&16384 || $row['npcflag']&32768)
				{
					$spirithealers['population']++;
					$spirithealers['points'][] = $point;
				}
			}
			if ($taxies['population'])
				$zone['position'][] = $taxies;
			if ($inns['population'])
				$zone['position'][] = $inns;
			if ($repairers['population'])
				$zone['position'][] = $repairers;
			if ($spirithealers['population'])
				$zone['position'][] = $spirithealers;
		}

	}

	if (!$zone['position'])
	{
		$zone['position'] = array(
			array(
				'population' => 0,
				'name' => ""/*$zone['name']*/,
				'atid' => $id,
				'points' => array()
			)
		);
	}

/*	// Положения объектофф:
	$zone['position'] = position($object['entry'], 'gameobject');

	// Исправить type, чтобы подсвечивались event-овые объекты
	if ($object['position'])
		foreach ($object['position'] as $z => $zone)
			foreach ($zone['points'] as $p => $pos)
				if ($pos['type'] == 0 && ($events = event_find(array('object_guid' => $pos['guid']))))
				{
					$names = array_select_key(event_name($events), 'name');
					$object['position'][$z]['points'][$p]['type'] = 4;
					$object['position'][$z]['points'][$p]['events'] = implode(", ", $names);
				}
*/
	save_cache(16, $zone['areatableID'], $zone);
}

global $page;
$page = array(
	'Mapper' => true,
	'Book' => false,
	'Title' => $zone['name'].' - '.$smarty->get_config_vars('Zone'),
	'tab' => 0,
	'type' => 7,
	'typeid' => $zone['areatableID'],
	'path' => path(0, 6, $zone['areatableID']) //path(0, 6, $zone['map'])
);

$smarty->assign('page', $page);

// Комментарии
$smarty->assign('comments', getcomments($page['type'], $page['typeid']));

// Количество MySQL запросов
$smarty->assign('mysql', $DB->getStatistics());
$smarty->assign('zone', $zone);
$smarty->display('zone.tpl');
?>