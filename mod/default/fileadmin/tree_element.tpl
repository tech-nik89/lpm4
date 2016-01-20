<div>
	{if $element.dir}
		<img src="mod/default/fileadmin/icons/folder.png" alt="+" />
		{$element.name}
	{else}
		<img src="mod/default/fileadmin/icons/document.png" alt="+" />
		<a href="#" onclick="javascript:selectFile('{$element.path}');">{$element.name}</a>
	{/if}
</div>
{if count($element.children) > 0}
	<div style="margin-left:20px;">
		{foreach from=$element.children item=subelement}
			{include file='../mod/default/fileadmin/tree_element.tpl' element=$subelement}
		{/foreach}
	</div>
{/if}