{strip}
new Listview({ldelim}template:'faction', id:'factions', data:[
{section name=i loop=$data}
{ldelim}
	id:{$data[i].entry},
	name:'{$data[i].name|escape:"quotes"}'
	{if $data[i].group},group:'{$data[i].group|escape:"quotes"}'{/if}
	{if $data[i].side},side:'{$data[i].side|escape:"quotes"}'{/if}
	,category:{$data[i].category}
	,category2:{$data[i].category2}
{rdelim}{if $smarty.section.i.last}{else},{/if}
{/section}
]{rdelim});
{/strip}