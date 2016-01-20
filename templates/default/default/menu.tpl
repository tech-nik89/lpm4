<div style="padding:2px;">
	{if !$menuitem.submenu}<strong>{/if}<a href="{$menuitem.url}" class="menu">{$menuitem.title}</a>{if !$menuitem.submenu}</strong>{/if}
</div>
<div style="margin-left:10px;">
	{foreach from=$menuitem.children item=item}
		{include file='../templates/default/default/menu.tpl' menuitem=$item}
	{/foreach}
</div>