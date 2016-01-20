<div class="headline">{$lang.sitemap}</div>

<ul style="list-style-type:square;">
	{foreach from=$menu item=item}
		{include file='../mod/default/sitemap/item.tpl' subitem=$item}
	{/foreach}
</ul>