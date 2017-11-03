<?php
$smarty->config_load($conf_file);

// Загружаем новости
$rows = @$DB->select('SELECT text_loc?d AS text FROM ?_news ORDER BY time DESC, id DESC LIMIT 5', $_SESSION['locale']);
if($rows)
	$smarty->assign('news', $rows);

global $page;
$smarty->assign('page', $page);
$smarty->display('main.tpl');
?>