<div style="padding:2px;">
	<a href="{$menuitem.url}" class="menu">{$menuitem.title}</a>
</div>
<div style="margin-left:10px;">
	{foreach from=$menuitem.children item=item}
		{include file='../templates/mobile/mbf/menu.tpl' menuitem=$item}
	{/foreach}
</div>