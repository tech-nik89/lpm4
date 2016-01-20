{foreach from=$posts item=post}
<p>
	<strong><a href="{$post.url}">{$post.nickname}</a></strong>:
	{$post.text}<br />
	<span style="color:#CCC;">({$post.timestamp_str})</span>
</p>
{/foreach}