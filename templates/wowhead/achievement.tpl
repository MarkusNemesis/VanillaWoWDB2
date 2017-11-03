{include file='header.tpl'}

		<div id="main">

			<div id="main-precontents"></div>
			<div id="main-contents" class="main-contents">

				<script type="text/javascript">
					{include file='bricks/allcomments.tpl'}
					var g_pageInfo = {ldelim}type: {$page.type}, typeId: {$page.typeid}, name: '{$achievement.name|escape:"quotes"}'{rdelim};
					g_initPath({$page.path});
					{if isset($allitems)}{include			file='bricks/allitems_table.tpl'		data=$allitems}{/if}
					{if isset($allspells)}{include			file='bricks/allspells_table.tpl'		data=$allspells}{/if}
					{if isset($allachievements)}{include	file='bricks/allachievements_table.tpl'	data=$allachievements}{/if}
				</script>

				<table class="infobox">
					<tr><th>{#Quick_Facts#}</th></tr>
					<tr><td>
						<div class="infobox-spacer"></div>
						<ul>
							<li><div>{#Points#}: <span class="moneyachievement tip" onmouseover="Listview.funcBox.moneyAchievementOver(event)" onmousemove="Tooltip.cursorUpdate(event)" onmouseout="Tooltip.hide()">{$achievement.points}</span></div></li>
							<li><div>{#Side#}: {$achievement.side}</div></li>
						</ul>
					</td></tr>
{strip}{*************** ЦЕПОЧКА ДОСТИЖЕНИЙ ***************}
					{if $achievement.series}
					<tr><th>{#Series#}</th></tr>
					<tr>
						<td>
							<div class="infobox-spacer"></div>
							<table class="series">
							{section name=i loop=$achievement.series}
								<tr>
									<th>{$smarty.section.i.index+1}.</th>
									<td>
										{if ($achievement.series[i].id == $achievement.id)}
											<b>{$achievement.series[i].name}</b>
										{else}
											<div><a href="?achievement={$achievement.series[i].id}">{$achievement.series[i].name}</a></div>
										{/if}
									</td>
								</tr>
							{/section}
							</table>
						</td>
					</tr>
					{/if}
{/strip}{*************** / ЦЕПОЧКА ДОСТИЖЕНИЙ ***************}
				</table>

				<div class="text">

					<div id="h1-icon-generic" class="h1-icon"></div>
					<a href="http://www.wowhead.com/?{$query}" class="button-red"><em><b><i>Wowhead</i></b><span>Wowhead</span></em></a>
					<h1 class="h1-icon">{$achievement.name}</h1>
					<script type="text/javascript">
					ge('h1-icon-generic').appendChild(Icon.create('{$achievement.iconname|escape:"javascript"}', 1));
					</script>

					{$achievement.description}

					<h3>{#Criteria#}{if $achievement.count} &ndash; <small><b>{#Requires#} {$achievement.count} {#out_of#} {$achievement.total_criterias}</b></small>{/if}</h3>

					<div style="float: left; margin-right: 25px">
					<table class="iconlist">
					{strip}
					{foreach from=$achievement.criterias item=cr name=criterias}
						<tr>
							<th{if $cr.icon} align="right" id="iconlist-icon{$cr.icon}"{/if}>
							{* для ссылок и стандартных записей *}
							{if !$cr.icon && ($cr.link || $cr.standard)}
								<ul><li><var>&nbsp;</var></li></ul>
							{/if}
							</th>
							<td>
								{if $cr.link}<a href="{$cr.link.href}"{if $cr.link.quality} class="q{$cr.link.quality}"{/if}>{$cr.link.text|escape:"html"}</a> {/if}

								{* СТАНДАРТНЫЙ ТЕКСТ *}
								{$cr.extra_text}
								{if $user.roles > 0} <small title="{#Criteria_id_type_id#} {$cr.type}" class="q0">[{$cr.id}]</small>{/if}
							</td>
						</tr>
						{* если первый столбик закончился (в нем может быть на 1 больше элементов) *}
						{if $smarty.foreach.criterias.index+1 == round(count($achievement.criterias) / 2)}
							</table>
							</div>
							<div style="float: left">
							<table class="iconlist">
						{/if}
					{/foreach}
					{/strip}
					</table>
					</div>

					{strip}
					<script type="text/javascript">
					{foreach from=$achievement.icons item=ic}
						ge('iconlist-icon{$ic.itr}').appendChild({$ic.type}.createIcon({$ic.id}, 0, {if $ic.count > 0}{$ic.count}{else}0{/if}));
					{/foreach}
					</script>
					{/strip}


					<div style="clear: left"></div>

{if $achievement.reward}
					<h3>{#Rewards#}</h3>

					<ul>
					<li><div>{$achievement.reward}</div></li>
					</ul>
{/if}

					<h2>{#Related#}</h2>

				</div>

				<div id="tabs-generic"></div>
				<div id="listview-generic" class="listview"></div>
<script type="text/javascript">
var tabsRelated = new Tabs({ldelim}parent: ge('tabs-generic'){rdelim});
{if $achievement.see_also}{include		file='bricks/achievement_table.tpl'	id='see-also'		tabsid='tabsRelated'	data=$achievement.see_also		name='seealso'}{/if}
{if $achievement.criteria_of}{include	file='bricks/achievement_table.tpl'	id='criteria-of'	tabsid='tabsRelated'	data=$achievement.criteria_of	name='criteriaof'}{/if}
new Listview({ldelim}template: 'comment', id: 'comments', name: LANG.tab_comments, tabs: tabsRelated, parent: 'listview-generic', data: lv_comments{rdelim});
tabsRelated.flush();
</script>

				{include file='bricks/contribute.tpl'}
			</div>
		</div>

{include file='footer.tpl'}