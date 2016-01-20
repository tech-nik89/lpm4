<div class="headline">{$category.title}</div>

{if $category.description != ''}
	<p>{$category.description}</p>
{/if}

{foreach from=$subcategories item=category}
	&raquo; <a href="{$category.url}">{$category.title}</a><br />
{/foreach}