<?php
$smarty->config_load($conf_file, 'factions');

@list($c1, $c2) = extract_values($podrazdel);

$category = $c2 ? $c2 : $c1;

$cache_key = cache_key($category);

if(!$factions = load_cache(19, $cache_key))
{
	unset($factions);

	$factions = $DB->select('
			SELECT f1.factionID AS entry, f1.team, TRIM(f1.name_loc?d) AS name, f1.side, { f2.?# }{ ?d } AS category2, ?d AS category
			FROM ?_factions f1
			{ LEFT JOIN (?_factions f2) ON f1.team <> ?d }
			WHERE
				f1.reputationListID != -1
				{ AND f1.team = f2.?# }
				{ AND f1.team = ? }
			ORDER BY name
		',
		$_SESSION['locale'],
		!isset($category) ? 'factionID' : DBSIMPLE_SKIP,
		isset($category) ? intval($c1) : DBSIMPLE_SKIP,
		intval($c2),
		!isset($category) ? 0 : DBSIMPLE_SKIP,
		!isset($category) ? 'factionID' : DBSIMPLE_SKIP,
		isset($category) ? $category : DBSIMPLE_SKIP
	);
	if($c1 && !$c2)
	{
		$entrys = array();
		foreach($factions as $f)
			$entrys[] = $f['entry'];

		$factions = array_merge($factions, $DB->select('
				SELECT f1.factionID AS entry, f1.team, f1.name_loc?d AS name, f1.side, f1.team AS category2, ?d AS category
				FROM ?_factions f1
				WHERE
					f1.reputationListID != -1
					AND f1.team IN (0)
			',
			$_SESSION['locale'],
			intval($c2),
			$entrys
		));
	}

	save_cache(19, $cache_key, $factions);
}

global $page;
$page = array(
	'Title' => $smarty->get_config_vars('Factions'),
	'tab' => 0,
	'type' => 0,
	'typeid' => 0,
	// path will be 0,8,... when zones are implemented
	'path' => path(0, 7, $c2, $c1)
);
$smarty->assign('page', $page);

$smarty->assign('factions', $factions);
// Статистика выполнения mysql запросов
$smarty->assign('mysql', $DB->getStatistics());
// Загружаем страницу
$smarty->display('factions.tpl');
?>