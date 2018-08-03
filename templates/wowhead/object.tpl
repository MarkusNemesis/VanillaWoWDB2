{include file='header.tpl'}

	<div id="main">

		<div id="main-precontents"></div>
		<div id="main-contents" class="main-contents">

			<script type="text/javascript">
				{include file='bricks/allcomments.tpl'}
				{include file='bricks/allcommentswh.tpl'}
				var g_pageInfo = {ldelim}type: {$page.type}, typeId: {$page.typeid}, name: '{$object.name|escape:"quotes"}'{rdelim};
				g_initPath({$page.path});
			</script>

{if isset($object.key) or isset($object.lockpicking) or isset($object.mining) or isset($object.herbalism)}
			<table class="infobox">
				<tr><th>{#Quick_Facts#}</th></tr>
				<tr><td><div class="infobox-spacer"></div>
				<ul>
					{if isset($object.key)}<li><div>{#Key#}: <a class="q{$object.key.quality}" href="?item={$object.key.id}">[{$object.key.name}]</a></div></li>{/if}
					{if isset($object.lockpicking)}<li><div>{#Required_lockpicking_skill#} (<span class="tip" onmouseover="Tooltip.showAtCursor(event, '{#Required_lockpicking_skill#}', 0, 0, 'q')" onmousemove="Tooltip.cursorUpdate(event)" onmouseout="Tooltip.hide()">{$object.lockpicking}</span>)</div></li>{/if}
					{if isset($object.mining)}<li><div>{#Required_mining_skill#} (<span class="tip" onmouseover="Tooltip.showAtCursor(event, '{#Required_mining_skill#}', 0, 0, 'q')" onmousemove="Tooltip.cursorUpdate(event)" onmouseout="Tooltip.hide()">{$object.mining}</span>)</div></li>{/if}
					{if isset($object.herbalism)}<li><div>{#Required_herb_skill#} (<span class="tip" onmouseover="Tooltip.showAtCursor(event, '{#Required_herb_skill#}', 0, 0, 'q')" onmousemove="Tooltip.cursorUpdate(event)" onmouseout="Tooltip.hide()">{$object.herbalism}</span>)</div></li>{/if}
				</ul>
				</td></tr>
			</table>
{/if}

			<div class="text">

				<a href="http://www.wowhead.com/?{$query}" class="button-red"><em><b><i>Wowhead</i></b><span>Wowhead</span></em></a>
				<h1>{$object.name}</h1>

{if $object.position}
				<div>{#This_Object_can_be_found_in#}
{strip}
				<span id="locations">
					{foreach from=$object.position item=zone name=zone}
						<a href="javascript:;" onclick="
							myMapper.update(
								{ldelim}
								{if $zone.atid}
									zone:{$zone.atid}
									{if $zone.points}
										,
									{/if}
								{else}
									show:false
								{/if}
								{if $zone.points}
									coords:[
										{foreach from=$zone.points item=point name=point}
												[{$point.x},{$point.y},
												{ldelim}
													label:'$<br>
													<div class=q0>
														<small>{#Respawn#}:
															{if isset($point.r.h)} {$point.r.h}{#hr#}{/if}
															{if isset($point.r.m)} {$point.r.m}{#min#}{/if}
															{if isset($point.r.s)} {$point.r.s}{#sec#}{/if}
														</small>
													</div>',type:'0'
												{rdelim}]
												{if !$smarty.foreach.point.last},{/if}
										{/foreach}
									]
								{/if}
								{rdelim});
							g_setSelectedLink(this, 'mapper'); return false" onmousedown="return false">
							{$zone.name}</a>{if $zone.population > 1}&nbsp;({$zone.population}){/if}{if $smarty.foreach.zone.last}.{else}, {/if}
					{/foreach}
				</span></div>
{/strip}
				<div id="mapper-generic"></div>
				<div class="clear"></div>

				<script type="text/javascript">
					var myMapper = new Mapper({ldelim}parent: 'mapper-generic', zone: '{$object.position[0].atid}'{rdelim});
					gE(ge('locations'), 'a')[0].onclick();
				</script>

{else}
				{#This_Object_cant_be_found#}
{/if}

{if isset($object.pagetext)}
	<h3>Content</h3>
	<div id="book-generic"></div>
	{strip}
		<script>
			new Book({ldelim} parent: 'book-generic', pages: [
			{foreach from=$object.pagetext item=pagetext name=j}
				'{$pagetext|escape:"javascript"}'
				{if $smarty.foreach.j.last}{else},{/if}
			{/foreach}
			]{rdelim})
		</script>
	{/strip}
{/if}
				<h2>{#Related#}</h2>

			</div>

			<div id="tabs-generic"></div>
			<div id="listview-generic" class="listview"></div>
<script type="text/javascript">
{if isset($allitems)}{include			file='bricks/allitems_table.tpl'		data=$allitems			}{/if}
{if isset($allachievements)}{include	file='bricks/allachievements_table.tpl'	data=$allachievements	}{/if}
var tabsRelated = new Tabs({ldelim}parent: ge('tabs-generic'){rdelim});
{if isset($object.drop)}{include			file='bricks/item_table.tpl'		id='contains'		name='contains'		tabsid='tabsRelated'	data=$object.drop		}{/if}
{if isset($object.starts)}{include			file='bricks/quest_table.tpl'		id='starts'			name='starts'		tabsid='tabsRelated'	data=$object.starts		}{/if}
{if isset($object.ends)}{include			file='bricks/quest_table.tpl'		id='ends'			name='ends'			tabsid='tabsRelated'	data=$object.ends		}{/if}
{if isset($object.criteria_of)}{include		file='bricks/achievement_table.tpl'	id='criteria-of'	name='criteriaof'	tabsid='tabsRelated'	data=$object.criteria_of}{/if}
new Listview({ldelim}template: 'comment', id: 'comments', name: LANG.tab_comments, tabs: tabsRelated, parent: 'listview-generic', data: lv_comments{rdelim});
new Listview({ldelim}template: 'comment', id: 'commentswh', name: LANG.tab_commentswh, tabs: tabsRelated, parent: 'listview-generic', data: lv_commentswh{rdelim});
tabsRelated.flush();
</script>

{include file='bricks/contribute.tpl'}

			</div>
		</div>
	</div>

{include file='footer.tpl'}
