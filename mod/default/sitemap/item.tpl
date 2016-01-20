{if !$subitem.submenu}
	<li>
		<a href="{$subitem.url}">{$subitem.title}</a>
		{if !empty($subitem.children)}
			<ul style="list-style-type:square;">
				{foreach from=$subitem.children item=item}
					{include file='../mod/default/sitemap/item.tpl' subitem=$item}
				{/foreach}
			</ul>
		{/if}
	</li>
{/if}