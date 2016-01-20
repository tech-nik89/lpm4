<div>
	<a href="{$menuitem.url}" class="menu">{$menuitem.title}</a>
</div>
<div style="padding-left:10px;">
	{foreach from=$menuitem.children item=item}
		{include file='../templates/default/dodlan/menu.tpl' menuitem=$item}
	{/foreach}
</div>