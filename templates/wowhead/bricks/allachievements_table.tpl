{*
		ШАБЛОН ИНФОРМАЦИИ О ВЕЩЯХ
	Переменные, передаваемые шаблону:
	data   - данные для табл

	Пример вставки модуля в текст:
		{include file='bricks/allachievements_table.tpl' data=$allachievements}
*}
var _ = g_achievements;
{strip}
	{foreach from=$data key=id item=item}
		_[{$id}]={ldelim}icon:'{$item.icon|escape:"javascript"}'{rdelim};
	{/foreach}
{/strip}

