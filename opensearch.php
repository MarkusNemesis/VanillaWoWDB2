<?php
require_once('configs/config.php');
require_once('includes/allutil.php');

// Для Ajax отключаем debug
$AoWoWconf['debug'] = false;
// Для Ajax ненужен реалм
$AoWoWconf['realmd'] = false;
// Настройка БД
global $DB;
require_once('includes/db.php');

// Принимаются запросы минимум из 2 символов (хотя на вовхеде и на 1 символ)
$search_query = $_GET['search'];
if(strlen($search_query) < 2)
	exit('["", []]');
$search_query = '%'.str_replace('%', '\%', $search_query).'%';

echo '["'.str_replace('"', '\"', $_GET['search']).'", [';

$rows = array();

// Ищем вещи:

$rows = $DB->select('
	SELECT i.entry, ?# as name, a.iconname, i.quality
	FROM ?_icons a, item_template i{, ?# l}
	WHERE
		?# LIKE ?
		AND a.id = i.displayid
		{ AND i.entry = l.?# }
	ORDER BY i.quality DESC, ?#
	LIMIT 3
	',
	$_SESSION['locale'] == 0 ? 'name' : 'name_loc'.$_SESSION['locale'],	// SELECT
	$_SESSION['locale'] == 0 ? DBSIMPLE_SKIP : 'locales_item',			// FROM
	$_SESSION['locale'] == 0 ? 'name' : 'name_loc'.$_SESSION['locale'],	// WHERE1
	$search_query,
	$_SESSION['locale'] == 0 ? DBSIMPLE_SKIP : 'entry',					// WHERE2
	$_SESSION['locale'] == 0 ? 'name' : 'name_loc'.$_SESSION['locale']	// ORDER
);

foreach($rows as $i => $row)
	$found[$row['name'].' (Item)'] = array(
		'type'		=> 3,
		'entry'		=> $row['entry'],
		'iconname'	=> $row['iconname'],
		'quality'	=> $row['quality']
	);

// Ищем объекты:
$rows = $DB->select('
	SELECT entry, ?# as name
	FROM ?#
	WHERE ?# LIKE ?
	ORDER BY ?#
	LIMIT 3
	',
	$_SESSION['locale'] == 0 ? 'name' : 'name_loc'.$_SESSION['locale'],			// SELECT
	$_SESSION['locale'] == 0 ? 'gameobject_template' : 'locales_gameobject',	// FROM
	$_SESSION['locale'] == 0 ? 'name' : 'name_loc'.$_SESSION['locale'],			// WHERE1
	$search_query,
	$_SESSION['locale'] == 0 ? 'name' : 'name_loc'.$_SESSION['locale']			// ORDER
);

foreach($rows as $i => $row)
	$found[$row['name'].' (Object)'] = array(
		'type' => 2,
		'entry'=>$row['entry'],
	);

// Ищем квесты:
$rows = $DB->select('
	SELECT q.entry, ?# as Title, q.RequiredRaces
	FROM quest_template q {, ?# l}
	WHERE
		(?# LIKE ?)
		{AND (q.entry=l.?#)}
	ORDER BY ?#
	LIMIT 3
	',
	$_SESSION['locale'] == 0 ? 'Title' : 'Title_loc'.$_SESSION['locale'],	// SELECT
	$_SESSION['locale'] == 0 ? DBSIMPLE_SKIP : 'locales_quest',				// FROM
	$_SESSION['locale'] == 0 ? 'Title' : 'Title_loc'.$_SESSION['locale'],	// WHERE1
	$search_query,
	$_SESSION['locale'] == 0 ? DBSIMPLE_SKIP : 'entry',						// WHERE2
	$_SESSION['locale'] == 0 ? 'Title' : 'Title_loc'.$_SESSION['locale']	// ORDER
);

foreach($rows as $i => $row)
	$found[$row['Title'].' (Quest)'] = array(
		'type' => 5,
		'entry'=> $row['entry'],
		'side' => SideByRace($row['RequiredRaces'])
	);

// Ищем creature:
$rows = $DB->select('
	SELECT entry, ?# as name
	FROM ?#
	WHERE ?# LIKE ?
	ORDER BY ?#
	LIMIT 3
	',
	$_SESSION['locale'] == 0 ? 'name' : 'name_loc'.$_SESSION['locale'],		// SELECT
	$_SESSION['locale'] == 0 ? 'creature_template' : 'locales_creature',	// FROM
	$_SESSION['locale'] == 0 ? 'name' : 'name_loc'.$_SESSION['locale'],		// WHERE1
	$search_query,
	$_SESSION['locale'] == 0 ? 'name' : 'name_loc'.$_SESSION['locale']		// ORDER
);

foreach($rows as $i => $row)
	$found[$row['name'].' (NPC)'] = array(
		'type' => 1,
		'entry' => $row['entry']
	);

// Если ничего не найдено...
if(!isset($found))
{
	echo ']]';
	exit;
}

//ksort($found);

$found = array_slice($found, 0, 10);

$i=0;
foreach($found as $name => $fitem)
{
	echo '"'.str_replace('"', '\"', $name).'"';
	if($i<count($found)-1)
		echo ', ';
	$i++;
}

echo '], [], [], [], [], [], [';

$i=0;
foreach($found as $name => $fitem)
{
	echo '['.$fitem['type'].', '.$fitem['entry'];
	if(isset($fitem['iconname'])) echo ', "'.$fitem['iconname'].'"';
	if(isset($fitem['quality'])) echo ", ".$fitem['quality'];
	if(isset($fitem['side'])) echo ", ".$fitem['side'];
	echo ']';
	if($i<count($found)-1)
		echo ', ';
	$i++;
}

echo ']]';

?>