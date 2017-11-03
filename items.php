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
		SELECT ?#, i.entry, maxcount
			{, l.name_loc?d AS name_loc}
		FROM ?_icons, item_template i
			{LEFT JOIN (locales_item l) ON l.entry=i.entry AND ?d}
		WHERE
			id=displayid
			{ AND class = ? }
			{ AND subclass = ? }
			{ AND InventoryType = ? }
		ORDER BY quality DESC, name
		{ LIMIT ?d }
		',
		$item_cols[2],
		($_SESSION['locale'])? $_SESSION['locale']: DBSIMPLE_SKIP,
		($_SESSION['locale'])? 1: DBSIMPLE_SKIP,
		isset($class) ? $class : DBSIMPLE_SKIP,
		isset($subclass) ? $subclass : DBSIMPLE_SKIP,
		isset($type) ? $type : DBSIMPLE_SKIP,
		($AoWoWconf['limit']!=0)? $AoWoWconf['limit']: DBSIMPLE_SKIP
	);
	$rows = sanitiseitemrows($rows);
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