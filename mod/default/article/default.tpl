<div class="headline">{$lang.article}</div>
<table width="100%" border="0">
	<tr>
		{foreach from=$categories item=category}
			<td>
				<strong>
					{$category.title}
				</strong>
			</td>
		{/foreach}
	</tr>
	<tr>
		{foreach from=$categories item=category}
			<td valign="top">
				{foreach from=$category.subcategories item=subcategory}
					&raquo; <a href="{$subcategory.url}">{$subcategory.title}</a> ({$subcategory.articles})<br />
				{/foreach}
			</td>
		{/foreach}
	</tr>
</table>

{if count($articles.this_week) > 0}
	<div class="headline">{$lang.this_week}</div>
	{foreach from=$articles.this_week item=article}
		<p>
			<strong>
				<a href="{$article.url}">{$article.title}</a>
			</strong>
			({$article.comments})
			<br />
			{$article.timestamp|date_format}: {$article.preview}<br />
		</p>
	{/foreach}
{/if}

{if count($articles.last_week) > 0}
	<div class="headline">{$lang.last_week}</div>
	{foreach from=$articles.last_week item=article}
		<p>
			<strong>
				<a href="{$article.url}">{$article.title}</a>
			</strong>
			({$article.comments})
			<br />
			{$article.timestamp|date_format}: {$article.preview}
		</p>
	{/foreach}
{/if}

{if count($articles.older) > 0}
	<div class="headline">{$lang.older}</div>
	{foreach from=$articles.older item=article}
		<p>
			<strong>
				<a href="{$article.url}">{$article.title}</a>
			</strong>
			({$article.comments})
			<br />
			{$article.timestamp|date_format}: {$article.preview}
		</p>
	{/foreach}
{/if}