{foreach from=$list item=item}
	<p><strong>{$item.nickname}</strong> ({$item.timestamp_str})<br />
	{$item.text}
{/foreach}