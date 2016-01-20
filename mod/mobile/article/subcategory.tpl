<div class="headline">{$category.title}</div>

{if $category.description != ''}
	<p>{$category.description}</p>
{/if}

{foreach from=$articles item=article}
	<p>
		<strong>
			<a href="{$article.url}">{$article.title}</a>
		</strong>
		({$article.comments})
		<br />
		{$article.timestamp|date_format}: {$article.preview}
	</p>
{/foreach}