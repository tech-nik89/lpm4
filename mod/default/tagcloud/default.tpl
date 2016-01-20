{if $toptext != ''}
	<p>{$toptext}</p>
{/if}

<p{if $justify == '1'} align="justify"{/if}>
	{foreach from=$tags item=tag}
		<span style="font-size:{$tag.weight}px; line-height:{$tag.weight}px;" class="tagcloud">
			<a href="{$tag.url}">{$tag.title|upper}</a>
		</span>
	{/foreach}
</p>