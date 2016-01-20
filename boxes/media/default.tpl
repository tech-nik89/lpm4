<ol>
	{foreach from=$media_box_entries item=entry}
		<li>
			<a href="{$entry.url}">{$entry.name}</a>
		</li>
	{/foreach}
</ol>