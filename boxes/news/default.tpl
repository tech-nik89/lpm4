{foreach from=$news_box_entries item=entry}
<p>
	&raquo; <a href="{$entry.url}">{$entry.title}</a><br />
</p>
{/foreach}