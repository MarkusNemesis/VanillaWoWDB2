{strip}
	new Listview({ldelim}
		template:'zone',
		id:'{$id}',
		name:LANG.tab_{$name},
		{if $tabsid}tabs:tabsRelated,parent:'listview-generic',{/if}
		data:[
			{section name=i loop=$data}
				{ldelim}
					id:'{$data[i].area}',
					name:'{$data[i].name|escape:"quotes"}',
					instance:'{$data[i].instance|escape:"quotes"}'
				{rdelim}
				{if $smarty.section.i.last}{else},{/if}
			{/section}
		]
	{rdelim});
{/strip}

