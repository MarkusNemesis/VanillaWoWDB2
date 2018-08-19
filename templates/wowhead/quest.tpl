{include file='header.tpl'}

		<div id="main">

			<div id="main-precontents"></div>

			<div id="main-contents" class="main-contents">
				<script type="text/javascript">
					{include file='bricks/allcomments.tpl'}
					{include file='bricks/allcommentswh.tpl'}
					{include file='bricks/allcommentsalkz.tpl'}
					var g_pageInfo = {ldelim}type: 5, typeId: {$quest.entry}, name: '{$quest.Title|escape:"quotes"}'{rdelim};
					g_initPath([0,3,{$quest.maincat},{$quest.category}]);
				</script>

				<script type="text/javascript">
					{if isset($allitems)}{include			file='bricks/allitems_table.tpl'		data=$allitems			}{/if}
					{if isset($allspells)}{include			file='bricks/allspells_table.tpl'		data=$allspells			}{/if}
					{if isset($allachievements)}{include	file='bricks/allachievements_table.tpl'	data=$allachievements	}{/if}
				</script>

				<table class="infobox">
					<tr><th>{#Quick_Facts#}</th></tr>
					<tr><td>
						<div class="infobox-spacer"></div>
						<ul>
							{if $quest.QuestLevel>0}<li><div>{#Level#}: {$quest.QuestLevel}</div></li>{/if}
							{if $quest.MinLevel>0}<li><div>{#Requires_level#}: {$quest.MinLevel}</div></li>{/if}
							{if $quest.typename}<li><div>{#Type#}: {$quest.typename}</div></li>{/if}
							{if isset($quest.side)}<li><div>{#Side#}: <span class="{if ($quest.side.side==1)}alliance{elseif ($quest.side.side==2)}horde{else}both{/if}-icon">{$quest.side.name}</span></div></li>{/if}
							{strip}{if isset($quest.LimitTime)}
								<li><div>
									{#Timer#}:
									{if isset($quest.LimitTime.h)} {$quest.LimitTime.h} {#hr#}{/if}
									{if isset($quest.LimitTime.m)} {$quest.LimitTime.m} {#min#}{/if}
									{if isset($quest.LimitTime.s)} {$quest.LimitTime.s} {#sec#}{/if}
								</div></li>
							{/if}{/strip}
							{if isset($quest.start)}<li><div><img src="/images/icons/small/quest_start.gif">{#Start#}: {section loop=$quest.start name=i}{if $quest.start[i].side}<span class="{$quest.start[i].side}-icon">{/if}<a href="?{$quest.start[i].type}={$quest.start[i].entry}"{if $quest.start[i].type == 'item'} class="icontiny q{$quest.start[i].quality}" style="background-image: url(images/icons/tiny/{$quest.start[i].iconname}.gif);"{/if}>{$quest.start[i].name}</a>{if $quest.start[i].side}</span>{/if}{if $smarty.section.i.last}{else}, <br><span style="visibility: hidden;">{#Start#}: </span>{/if}{/section}</div></li>{/if}
							{if isset($quest.end)}<li><div><img src="/images/icons/small/quest_end.gif">{#End#}: {section loop=$quest.end name=i}{if $quest.end[i].side}<span class="{$quest.start[i].side}-icon">{/if}<a href="?{$quest.end[i].type}={$quest.end[i].entry}">{$quest.end[i].name}</a>{if $quest.end[i].side}</span>{/if}{if $smarty.section.i.last}{else}, <br><span style="visibility: hidden;">{#End#}: </span>{/if}{/section}</div></li>{/if}
							{if isset($quest.reqskill)}<li><div>{#Skill#}: {$quest.reqskill.name} ({$quest.reqskill.value})</div></li>{/if}
							{if isset($quest.reqclass)}<li><div>{#Class#}: {$quest.reqclass}</div></li>{/if}
							{if isset($quest.Sharable)}<li><div>{#Sharable#}</div></li>{/if}
							{if isset($quest.Daily)}<li><div>{#Daily#}</div></li>{elseif isset($quest.Repeatable)}<li><div>{#Repeatable#}</div></li>{/if}
						</ul>
					</td></tr>
{strip}		{* Серия квестов *}
					{if isset($quest.series)}
					<tr><th>{#Series#}</th></tr>
					<tr>
						<td>
							<div class="infobox-spacer"></div>
							<table class="series">
							{section name=i loop=$quest.series}
								<tr>
									<th>{$smarty.section.i.index+1}.</th>
									<td>
										{if ($quest.series[i].entry==$quest.entry)}
											<b>{$quest.series[i].Title}</b>
										{else}
											<div><a href="?quest={$quest.series[i].entry}">{$quest.series[i].Title}</a></div>
										{/if}
									</td>
								</tr>
							{/section}
							</table>
						</td>
					</tr>
					{/if}
{/strip}
{strip}		{* Требует выполнения всех этих квестов *}
					{if isset($quest.req)}
					<tr><th>
						<span class="tip" onmouseover="Tooltip.showAtCursor(event, '{#Prev_Quests_Desc#}', 0, 0, 'q')" onmousemove="Tooltip.cursorUpdate(event)" onmouseout="Tooltip.hide()">
							{#Prev_Quests#}
						</span>
					</th></tr>
					<tr><td>
							<div class="infobox-spacer"></div>
							<ul>
							{section name=i loop=$quest.req}
								<li>
									<div><a href="?quest={$quest.req[i].entry}">{$quest.req[i].Title}</a></div>
								</li>
							{/section}
							</ul>
					</td></tr>
					{/if}
{/strip}
{strip}		{* Требует выполнения одного из этих квестов *}
					{if isset($quest.reqone)}
					<tr><th>
						<span class="tip" onmouseover="Tooltip.showAtCursor(event, '{#ReqOne_Quests_Desc#}', 0, 0, 'q')" onmousemove="Tooltip.cursorUpdate(event)" onmouseout="Tooltip.hide()">
							{#ReqOne_Quests#}
						</span>
					</th></tr>
					<tr><td>
							<div class="infobox-spacer"></div>
							<ul>
							{section name=i loop=$quest.reqone}
								<li>
									<div><a href="?quest={$quest.reqone[i].entry}">{$quest.reqone[i].Title}</a></div>
								</li>
							{/section}
							</ul>
					</td></tr>
					{/if}
{/strip}
{strip}		{* Требует активного квеста *}
					{if isset($quest.enabledby)}
					<tr><th>
						<span class="tip" onmouseover="Tooltip.showAtCursor(event, '{#Enabledby_Quests_Desc#}', 0, 0, 'q')" onmousemove="Tooltip.cursorUpdate(event)" onmouseout="Tooltip.hide()">
							{#Enabledby_Quests#}
						</span>
					</th></tr>
					<tr><td>
							<div class="infobox-spacer"></div>
							<ul>
							{section name=i loop=$quest.enabledby}
								<li>
									<div><a href="?quest={$quest.enabledby[i].entry}">{$quest.enabledby[i].Title}</a></div>
								</li>
							{/section}
							</ul>
					</td></tr>
					{/if}
{/strip}
{strip}		{* Закрывает квесты *}
					{if isset($quest.closes)}
					<tr><th>
						<span class="tip" onmouseover="Tooltip.showAtCursor(event, '{#Closes_Quests_Desc#}', 0, 0, 'q')" onmousemove="Tooltip.cursorUpdate(event)" onmouseout="Tooltip.hide()">
							{#Closes_Quests#}
						</span>
					</th></tr>
					<tr><td>
							<div class="infobox-spacer"></div>
							<ul>
							{section name=i loop=$quest.closes}
								<li>
									<div><a href="?quest={$quest.closes[i].entry}">{$quest.closes[i].Title}</a></div>
								</li>
							{/section}
							</ul>
					</td></tr>
					{/if}
{/strip}
{strip}		{* Во время выполнения квеста доступны эти квесты *}
					{if isset($quest.enables)}
					<tr><th>
						<span class="tip" onmouseover="Tooltip.showAtCursor(event, '{#Enables_Quests_Desc#}', 0, 0, 'q')" onmousemove="Tooltip.cursorUpdate(event)" onmouseout="Tooltip.hide()">
							{#Enables_Quests#}
						</span>
					</th></tr>
					<tr><td>
							<div class="infobox-spacer"></div>
							<ul>
							{section name=i loop=$quest.enables}
								<li>
									<div><a href="?quest={$quest.enables[i].entry}">{$quest.enables[i].Title}</a></div>
								</li>
							{/section}
							</ul>
					</td></tr>
					{/if}
{/strip}
{strip}		{* Открывает квесты *}
					{if isset($quest.open)}
					<tr><th>
						<span class="tip" onmouseover="Tooltip.showAtCursor(event, '{#Open_Quests_Desc#}', 0, 0, 'q')" onmousemove="Tooltip.cursorUpdate(event)" onmouseout="Tooltip.hide()">
							{#Open_Quests#}
						</span>
					</th></tr>
					<tr><td>
							<div class="infobox-spacer"></div>
							<ul>
							{section name=i loop=$quest.open}
								<li>
										{if ($quest.open[i].entry==$quest.entry)}
											<b>{$quest.open[i].Title}</b>
										{else}
											<div><a href="?quest={$quest.open[i].entry}">{$quest.open[i].Title}</a></div>
										{/if}
								</li>
							{/section}
							</ul>
					</td></tr>
					{/if}
{/strip}
{* Дополнительная информация о квесте *}
{if isset($quest.flagsdetails)}
					<tr><td>
						<div class="infobox-spacer"></div>
						<span class="tip" id="infobox-details"
							onmouseover="Tooltip.showAtCursor(event, '{foreach from=$quest.flagsdetails item=str}- {$str|escape:"html"}<br>{/foreach}', 0, 0, 0)"
							onmousemove="Tooltip.cursorUpdate(event)"
							onmouseout="Tooltip.hide()">{#Related#}</span>
					</td></tr>
{/if}
				</table>
				<script type="text/javascript">ss_appendSticky()</script>

				<div class="text">
					<a href="http://www.wowhead.com/?{$query}" class="button-red"><em><b><i>Wowhead</i></b><span>Wowhead</span></em></a>
					<h1>{$quest.Title}</h1>

					{if isset($quest.RequiredMinRep) or isset($quest.RequiredMaxRep)}
						{#Additional_requirements_to_obtain_this_quest#}:<br>
						{strip}
							<ul>
							{if isset($quest.RequiredMinRep)}<li><div>{#Your_reputation_with#} <a href="?faction={$quest.RequiredMinRep.entry}">{$quest.RequiredMinRep.name}</a> {#must_be#} {#higher_than#} {$quest.RequiredMinRep.value}</div></li>{/if}
							{if isset($quest.RequiredMaxRep)}<li><div>{#Your_reputation_with#} <a href="?faction={$quest.RequiredMaxRep.entry}">{$quest.RequiredMaxRep.name}</a> {#must_be#} {#lower_than#} {$quest.RequiredMaxRep.value}</div></li>{/if}
							</ul>
							<div class="line"></div>
						{/strip}
					{/if}

					{if $quest.Objectives}
						{$quest.Objectives}
					{else $quest.RequestItemsText}
						<h3>{#Progress#}</h3>
						{$quest.RequestItemsText}
					{/if}


{if isset($quest.itemreqs) or isset($quest.coreqs) or isset($quest.factionreq) or isset($quest.splayers) or isset($quest.moneyreq) or !empty($quest.EndText) or isset($quest.PlayersSlain) and $quest.PlayersSlain}
<table class="iconlist">
{strip}
	{* Перечень созданий, требуемых для квеста *}
	{if isset($quest.coreqs)}
		{foreach from=$quest.coreqs key=i item=req}
			<tr>
				<th><ul><li><var>&nbsp;</var></li></ul></th>
				<td>
					<a href="?{$req.req_type}={$req.entry}">
					{if !empty($quest.EndText)}
						{$quest.EndText}
					</a>
					{else}
						{if !empty($quest.ObjectiveText[$i])}
							{$quest.ObjectiveText[$i]}
						{else}
							{$req.name}
						{/if}
					</a>
						{if empty($quest.ObjectiveText[$i]) && $req.req_type == 'npc'} {#slain#}{/if}
						{if $req.count>1} ({$req.count}){/if}
					{/if}
					{if $req.spell}<span class='q0'> [<a href='?spell={$req.spell.entry}'>{$req.spell.name}</a>]</div>{/if}
				</td>
			</tr>
		{/foreach}
	{elseif !empty($quest.EndText)}
			<tr>
				<th><ul><li><var>&nbsp;</var></li></ul></th>
				<td>
					{$quest.EndText}
				</td>
			</tr>
	{/if}
{/strip}

{strip}
	{if isset($quest.itemreqs)}
		{foreach from=$quest.itemreqs key=i item=item}
			<tr>
				<th align="right" id="iconlist-icon{$i}"></th>
				<td>
					<span class="q{$item.quality}"><a href="?item={$item.entry}">{$item.name}</a></span>{if $item.count>1} ({$item.count}){/if}
				</td>
			</tr>
		{/foreach}
	{/if}
{/strip}

{strip}
	{if isset($quest.objectreqs)}
		{foreach from=$quest.objectreqs key=i item=object}
			<tr>
				<th><ul><li><var>&nbsp;</var></li></ul></th>
				<td>
					<a href="?object={$object.entry}">
						{if isset($quest.ObjectiveText[$i])}{$quest.ObjectiveText[$i]}{else}{$object.name}{/if}
					</a>
					{if $object.count>1}({$object.count}){/if}
				</td>
			</tr>
		{/foreach}
	{/if}
{/strip}

{strip}
	{if isset($quest.factionreq)}
			<tr>
				<th><ul><li><var>&nbsp;</var></li></ul></th>
				<td>
					<a href="?faction={$quest.factionreq.entry}">{$quest.factionreq.name}</a> ({$quest.factionreq.value})
				</td>
			</tr>
	{/if}
{/strip}
{strip}
	{if isset($quest.PlayersSlain) and $quest.PlayersSlain}
			<tr>
				<th><ul><li><var>&nbsp;</var></li></ul></th>
				<td>
					{$quest.PlayersSlain} {#Players_Slain#}
				</td>
			</tr>
	{/if}
{/strip}

{if isset($quest.splayers)}<tr><th><ul><li><var>&nbsp;</var></li></ul></th><td>{#Suggested_Players#} [{$quest.splayers}]</td></tr>{/if}
{if isset($quest.moneyreq)}
	<tr>
		<th><ul><li><var>&nbsp;</var></li></ul></th>
		<td>
			{#Required_Money#}: 
			{if isset($quest.moneyreq.moneygold)}<span class="moneygold">{$quest.moneyreq.moneygold}</span>{/if}
			{if isset($quest.moneyreq.moneysilver)}<span class="moneysilver">{$quest.moneyreq.moneysilver}</span>{/if}
			{if isset($quest.moneyreq.moneycopper)}<span class="moneycopper">{$quest.moneyreq.moneycopper}</span>{/if}
		</td>
	</tr>
{/if}
</table>
{if isset($quest.itemreqs)}
	<script type="text/javascript">
	{foreach from=$quest.itemreqs key=i item=item}
		ge('iconlist-icon{$i}').appendChild(g_items.createIcon({$item.entry}, 0, {$item.count}));
	{/foreach}
	</script>
{/if}
{/if}

{if isset($quest.SrcItem)}
<div class="pad"></div>
{#Provided_Item#}:
<table class="iconlist">
	<tr>
		<th align="right" id="iconlist-icon-src"></th>
		<td><span class="q1"><a href="?item={$quest.SrcItem.entry}">{$quest.SrcItem.name}</a></span></td>
	</tr>
</table>
<script type="text/javascript">ge('iconlist-icon-src').appendChild(g_items.createIcon({$quest.SrcItem.entry}, 0, {$quest.SrcItem.count}));</script>
{/if}

{if isset($quest.SrcSpell) and $quest.SrcSpell}
<div class="pad"></div>
{#The_following_spell_will_be_cast_on_you#}:
<table class="icontab">
	<tr>
		<th align="right" id="icontab-icon-spl"></th>
		<td><span class="q1"><a href="?spell={$quest.SrcSpell.entry}">{$quest.SrcSpell.name}</a></span></td>
	</tr>
</table>
<script type="text/javascript">ge('icontab-icon-spl').appendChild(g_spells.createIcon({$quest.SrcSpell.entry}, 0, 0));</script>
{/if}

{if $quest.Details}
						<h3>{#Description#}</h3>
						{$quest.Details}
{/if}

{if (isset($quest.itemchoices) or isset($quest.itemrewards) or isset($quest.money) or isset($quest.spellreward)) or isset($quest.BonusTalents) and $quest.BonusTalents}
					<h3>{#Rewards#}</h3>

{if isset($quest.itemchoices)}
						<div class="pad"></div>
						{#You_will_be_able_to_choose_one_of_these_rewards#}:
						<div class="pad"></div>
						<table class="icontab">
						<tr>
{section name=j loop=$quest.itemchoices}
								<th id="icontab-icon{$smarty.section.j.index}"></th>
								<td>
									<span class="q{$quest.itemchoices[j].quality}">
										<a href="?item={$quest.itemchoices[j].entry}">
											{$quest.itemchoices[j].name}
										</a>
									</span>
								</td>
{/section}
						</tr>
						</table>
						<script type="text/javascript">
						{section name=j loop=$quest.itemchoices}
							ge('icontab-icon{$smarty.section.j.index}').appendChild(g_items.createIcon({$quest.itemchoices[j].entry}, 1, {$quest.itemchoices[j].count}));
						{/section}
						</script>
{/if}

{if isset($quest.itemrewards)}
						<div class="pad"></div>
						{#You_will_receive#}:
						<div class="pad"></div>
						<table class="icontab">
						<tr>{strip}
{section name=j loop=$quest.itemrewards}
								<th id="icontab-icon{$smarty.section.j.index+4}"></th>
								<td>
									<span class="q{$quest.itemrewards[j].quality}">
										<a href="?item={$quest.itemrewards[j].entry}">
											{$quest.itemrewards[j].name}
										</a>
									</span>
								</td>
{/section}
						</tr>{/strip}
						</table>
						<script type="text/javascript">
						{section name=j loop=$quest.itemrewards}
							ge('icontab-icon{$smarty.section.j.index+4}').appendChild(g_items.createIcon({$quest.itemrewards[j].entry}, 1, {$quest.itemrewards[j].count}));
						{/section}
						</script>
{/if}

{if isset($quest.spellreward)}
						<div class="pad"></div>
{if $quest.spellreward.entry==$quest.spellreward.realentry}
						{#The_following_spell_will_be_cast_on_you#}:
{else}
						{#You_will_learn#}:
{/if}
						<table class="icontab">{strip}
						<tr>
						<th id="icontab-icon20"></th><td><a href="?spell={$quest.spellreward.realentry}">{$quest.spellreward.name}</a></td>
						<th></th><td></td>
						</tr>
						</table>{/strip}

						<script type="text/javascript">
							ge('icontab-icon20').appendChild(g_spells.createIcon({$quest.spellreward.entry}, 1, 0));
						</script>
{/if}

{if isset($quest.money)}
						<div class="pad"></div>
						{strip}
						{#You_will_also_receive#}:
						{if isset($quest.money.moneygold)} <span class="moneygold">{$quest.money.moneygold}</span>{/if}
						{if isset($quest.money.moneysilver)} <span class="moneysilver">{$quest.money.moneysilver}</span>{/if}
						{if isset($quest.money.moneycopper)} <span class="moneycopper">{$quest.money.moneycopper}</span>{/if}
						{/strip}
{/if}
{if isset($quest.BonusTalents) and $quest.BonusTalents}
						<div class="pad"></div>
						<div>{#Bonus_Talents#}: {$quest.BonusTalents}</div>
{/if}
{/if}

{if ($quest.RequestItemsText and $quest.Objectives)}
						<h3>{#Progress#}</h3>
						{$quest.RequestItemsText}
{/if}

{if $quest.OfferRewardText}
						<h3>{#Completion#}</h3>
						{$quest.OfferRewardText}
{/if}

{if isset($quest.xp) and $quest.xp or isset($quest.reprewards) or isset($quest.titlereward) or isset($quest.mailrewards) or isset($quest.RewHonorableKills) and $quest.RewHonorableKills}
					<h3>{#Gains#}</h3>
					{#Upon_completion_of_this_quest_you_will_gain#}:
					<ul>{strip}
{if isset($quest.xp) and $quest.xp}
						<li><div>{$quest.xp} {#experience#}
							{if isset($quest.moneymaxlevel)} (
								{if isset($quest.moneymaxlevel.moneygold)}<span class="moneygold">{$quest.moneymaxlevel.moneygold}</span> {/if}
								{if isset($quest.moneymaxlevel.moneysilver)}<span class="moneysilver">{$quest.moneymaxlevel.moneysilver}</span> {/if}
								{if isset($quest.moneymaxlevel.moneycopper)}<span class="moneycopper">{$quest.moneymaxlevel.moneycopper}</span> {/if}
							{#at_max_level#}){/if}
						</div></li>
{/if}
{if isset($quest.reprewards)}
{section name=j loop=$quest.reprewards}
						<li><div>{$quest.reprewards[j].value} {#reputationwith#} <a href="?faction={$quest.reprewards[j].entry}">{$quest.reprewards[j].name}</a></div></li>
{/section}
{/if}						
{if isset($quest.titlereward)}
						<li><div>{#the_title#} "{$quest.titlereward|replace:"%s":"&lt;name&gt;"}"</div></li>
{/if}
{if isset($quest.RewHonorableKills) and $quest.RewHonorableKills}
						<li><div>{$quest.RewHonorableKills} {#Honorable_Kills#}</div></li>
{/if}
{if isset($quest.mailrewards)}
						<li><div>{#Mail_delivery#}
						{if isset($quest.maildelay)} {#in_time#}
							{if isset($quest.maildelay.h)} {$quest.maildelay.h} {#hr#}{/if}
							{if isset($quest.maildelay.m)} {$quest.maildelay.m} {#min#}{/if}
							{if isset($quest.maildelay.s)} {$quest.maildelay.s} {#sec#}{/if}
						{/if}
						</div></li>
{/if}
					</ul>{/strip}
{/if}

				<h2>{#Related#}</h2>

			</div>

			<div id="tabs-generic"></div>
			<div id="listview-generic" class="listview"></div>
			<script type="text/javascript">
				var tabsRelated = new Tabs({ldelim}parent: ge('tabs-generic'){rdelim});
				{if isset($quest.mailrewards)}{include file='bricks/item_table.tpl' id='mail-rewards' tabsid='tabsRelated' data=$quest.mailrewards name='questrewards'}{/if}
				{if isset($quest.criteria_of)}{include	file='bricks/achievement_table.tpl'	id='criteria-of'	tabsid='tabsRelated'	data=$quest.criteria_of	name='criteriaof'}{/if}
				new Listview({ldelim}template: 'comment', id: 'comments', name: LANG.tab_comments, tabs: tabsRelated, parent: 'listview-generic', data: lv_comments{rdelim});
				new Listview({ldelim}template: 'comment', id: 'commentswh', name: LANG.tab_commentswh, tabs: tabsRelated, parent: 'listview-generic', data: lv_commentswh{rdelim});
				new Listview({ldelim}template: 'comment', id: 'commentsalkz', name: LANG.tab_commentsalkz, tabs: tabsRelated, parent: 'listview-generic', data: lv_commentsalkz{rdelim});
				tabsRelated.flush();
			</script>

			{include file='bricks/contribute.tpl'}

			</div>
		</div>
	</div>
{include file='footer.tpl'}
