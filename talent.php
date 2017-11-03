<?php

$smarty->config_load($conf_file, 'talent');

global $page;
$page = array(
	'Mapper' => false,
	'Book' => false,
	'Talent' => true,
	'Title' => $smarty->get_config_vars('Talent_calculator'),
	'tab' => 1,
	'type' => 0,
	'typeid' => 0,
	'path' => path(1, 0)
);
$smarty->assign('page', $page);
$smarty->assign('mysql', $DB->getStatistics());
$smarty->display('talent.tpl');

?>
