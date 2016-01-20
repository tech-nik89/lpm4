{if $item.menuid != $entry.menuid}
	<option value="{$item.menuid}"{if $entry.parentid == $item.menuid} selected="selected"{/if}>
		{section name=i start=1 loop=$depth step=1}
			--
		{/section}
		{$item.title}
	</option>
{/if}
{foreach from=$item.children item=subitem}
	{include file='../mod/default/admin/menu.entry.item.tpl' item=$subitem entry=$entry depth=$depth + 1}
{/foreach}