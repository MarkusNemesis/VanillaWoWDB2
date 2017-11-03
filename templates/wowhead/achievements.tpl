{include file='header.tpl'}

	<div id="main">
		<div id="main-precontents"></div>
		<div id="main-contents" class="main-contents">
			<script type="text/javascript">
				g_initPath({$page.path});
			</script>

			<div id="lv-achievements" class="listview"></div>

			<script type="text/javascript">
				{include file='bricks/allachievements_table.tpl' data=$allachievements}
				{include file='bricks/achievement_table.tpl' data=$achievements.data id='achievements'}
			</script>

			<div class="clear"></div>
		</div>
	</div>

{include file='footer.tpl'}