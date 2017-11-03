{strip}

{assign var="category" value=false}

{foreach from=$data item=curr}
	{if $curr.category}{assign var="category" value=true}{/if}
{/foreach}

	new Listview({ldelim}
		template:'achievement',
		id:'{$id}',
		{if isset($name)}name:LANG.tab_{$name},{/if}
		{if isset($tabsid)}tabs:{$tabsid}, parent:'listview-generic',{/if}
		{if $category}visibleCols:['category'],{/if}
		data:[
			{section name=i loop=$data}
				{ldelim}
					id:{$data[i].id},
					name:'{$data[i].name|escape:"javascript"}',
					description:'{$data[i].description|escape:"javascript"}',
					side:{$data[i].faction},
					points:{$data[i].points},
					{if $data[i].areatableID}
						zone:{$data[i].areatableID},
					{/if}
					category:{$data[i].category}					
				{rdelim}
				{if $smarty.section.i.last}{else},{/if}
			{/section}
		]
	{rdelim});
{/strip}