<div style="padding:2px;">
	<strong><a href="{$menuitem.url}" class="menu">{$menuitem.title}</a></strong>
</div>
<div style="margin-left:10px;">
	{foreach from=$menuitem.children item=item}
		{include file='../templates/default/mikroschuft/menu.tpl' menuitem=$item}
	{/foreach}
</div>