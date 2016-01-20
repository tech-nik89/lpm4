{foreach from=$article_box_entries item=entry}
<p>
	<strong>{$entry.category}:</strong><br />
	<a href="{$entry.url}">{$entry.title}</a> ({$entry.comments})
</p>
{/foreach}