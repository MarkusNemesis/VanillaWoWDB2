	<title>{if $page.Title}{$page.Title|escape:"html"} - {/if}{$app_name}</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="SHORTCUT ICON" href="templates/wowhead/images/favicon.ico">

	<link rel="stylesheet" type="text/css" href="templates/wowhead/css/global.css?{$AOWOW_REVISION}" />
	<link rel="stylesheet" type="text/css" href="templates/wowhead/css/locale_{$language}.css?{$AOWOW_REVISION}" />
	<!--[if IE]><link rel="stylesheet" type="text/css" href="templates/wowhead/css/global_ie.css?{$AOWOW_REVISION}" /><![endif]-->
	<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="templates/wowhead/css/global_ie6.css?{$AOWOW_REVISION}" /><![endif]-->
	<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="templates/wowhead/css/global_ie67.css?{$AOWOW_REVISION}" /><![endif]-->
{if $page.Book}
	<link rel="stylesheet" type="text/css" href="templates/wowhead/css/Book.css?{$AOWOW_REVISION}">
{/if}
{if $page.Mapper}
	<link href="templates/wowhead/css/Mapper.css?{$AOWOW_REVISION}" type="text/css" rel="stylesheet">
	<!--[if lte IE 6]><link href="templates/wowhead/css/Mapper_ie6.css?{$AOWOW_REVISION}" type="text/css" rel=stylesheet><![endif]-->
{/if}
{if $page.Talent}
		<link rel="stylesheet" type="text/css" href="templates/wowhead/css/TalentCalc.css?{$AOWOW_REVISION}" />
		<link rel="stylesheet" type="text/css" href="templates/wowhead/css/talent.css?{$AOWOW_REVISION}" />
		<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="templates/wowhead/css/TalentCalc_ie6.css?{$AOWOW_REVISION}" /><![endif]-->
		<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="templates/wowhead/css/TalentCalc_ie67.css?{$AOWOW_REVISION}" /><![endif]-->
{/if}

	<script src="templates/wowhead/js/locale_{$language}.js?{$AOWOW_REVISION}" type="text/javascript"></script>
	<script src="templates/wowhead/js/global.js?{$AOWOW_REVISION}" type="text/javascript"></script>
	<script src="templates/wowhead/js/Markup.js?{$AOWOW_REVISION}" type="text/javascript"></script>
{if $page.Mapper}
	<script src="templates/wowhead/js/Mapper.js?{$AOWOW_REVISION}" type="text/javascript"></script>
	<style type="text/css">{literal}.zone-picker { margin-left: 4px }{/literal}</style>
{/if}
{if $page.Book}
	<script src="templates/wowhead/js/Book.js?{$AOWOW_REVISION}" type="text/javascript"></script>
{/if}
{if $page.Talent}
	<script src="?data=glyphs" type="text/javascript"></script>
	<script src="templates/wowhead/js/TalentCalc_{$language}.js?{$AOWOW_REVISION}" type="text/javascript"></script>
	<script src="templates/wowhead/js/TalentCalc.js?{$AOWOW_REVISION}" type="text/javascript"></script>
	<script src="templates/wowhead/js/talent.js?{$AOWOW_REVISION}" type="text/javascript"></script>
{/if}
{if $page.maps}
	<script src="templates/wowhead/js/maps.js?{$AOWOW_REVISION}" type="text/javascript"></script>
{/if}

	<script type="text/javascript">
		var g_serverTime = new Date('{$smarty.now|date_format:"%Y/%m/%d %H:%M:%S"}');
		g_locale = {ldelim} id: {$locale}, name: '{$language}' {rdelim};
{if $user}g_user = {ldelim} id: {$user.id}, name: '{$user.name|escape:"quotes"}', roles: {$user.roles}, permissions: {$user.perms} {rdelim};{/if}
	</script>