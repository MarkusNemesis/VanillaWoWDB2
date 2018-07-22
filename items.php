<?php

// Необходима функция iteminfo
require_once('includes/allitems.php');

$smarty->config_load($conf_file, 'items');

// Разделяем из запроса класс, подкласс и тип вещей
@list($class, $subclass, $type) = extract_values($podrazdel);

$cache_key = cache_key($class, $subclass, $type);

if(!$items = load_cache(7, $cache_key))
{
	unset($items);

	// Составляем запрос к БД, выполняющий поиск по заданным классу и подклассу
	$rows = $DB->select('
		SELECT i.`name`, i.`patch`, i.`quality`, `iconname`, i.`InventoryType`, i.`ItemLevel`, i.`RequiredLevel`, i.`class`, i.`subclass`, i.`stackable`, i.`BuyPrice`, 
			i.`armor`, i.`dmg_type1`, i.`dmg_min1`, i.`dmg_max1`, i.`delay`, i.`dmg_type2`, i.`dmg_min2`, i.`dmg_max2`, i.`ContainerSlots`, i.entry, i.maxcount
			{, l.name_loc?d AS name_loc}
		FROM (
			SELECT entry, MAX(patch) AS patch 
			FROM item_template
			WHERE patch <= ?d
			GROUP BY entry
		) AS p
		INNER JOIN item_template AS i ON i.entry = p.entry AND i.patch = p.patch
		LEFT JOIN ?_icons c ON c.id = i.displayid
		WHERE 1 = 1
			{ AND class = ? }
			{ AND subclass = ? }
			{ AND InventoryType = ? }
		ORDER BY quality DESC, name
		{ LIMIT ?d }
		',
		($_SESSION['locale'])? $_SESSION['locale']: DBSIMPLE_SKIP,
		$AoWoWconf['patch'],
		($_SESSION['locale'])? 1: DBSIMPLE_SKIP,
		isset($class) ? $class : DBSIMPLE_SKIP,
		isset($subclass) ? $subclass : DBSIMPLE_SKIP,
		isset($type) ? $type : DBSIMPLE_SKIP,
		($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
	);
	//$rows = sanitiseitemrows($rows);
	$items = array();
	foreach($rows as $row)
		$items[] = iteminfo2($row);

	save_cache(7, $cache_key, $items);
}

global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Title' => $smarty->get_config_vars('Items'),
	'tab' => 0,
	'type' => 0,
	'typeid' => 0,
	'path' => path(0, 0, $type, $subclass, $class),
);
$smarty->assign('page', $page);

// Статистика выполнения mysql запросов
$smarty->assign('mysql', $DB->getStatistics());
$smarty->assign('allitems', $allitems);
$smarty->assign('items', $items);
// Загружаем страницу
$smarty->display('items.tpl');
?>