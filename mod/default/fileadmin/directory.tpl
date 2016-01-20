<div>
	<img src="mod/default/fileadmin/icons/folder.png" alt="+" />
	{if $dir.current}<strong><em>{/if}
		<a href="{$dir.url}">{$dir.name}</a>
	{if $dir.current}</em></strong>{/if}
</div>
{if count($dir.children) > 0}
	<div style="margin-left:20px;">
		{foreach from=$dir.children item=subdir}
			{include file='../mod/default/fileadmin/directory.tpl' dir=$subdir}
		{/foreach}
	</div>
{/if}