{include file='header.tpl'}

	<div id="main">

		<div id="main-precontents"></div>
		<div id="main-contents" class="main-contents">

			 <script type="text/javascript">
				g_initPath({$page.path});
			</script>
				<div id="lv-zones" class="listview"></div>

				<script type="text/javascript">
					{include file='bricks/zones_table.tpl' id='zones' data=$zones name='zones'}
				</script>
			
			</div>
		</div>

{include file='footer.tpl'}
