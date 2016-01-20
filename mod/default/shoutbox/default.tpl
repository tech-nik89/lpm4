<div class="headline">{$lang.shoutbox}</div>
{if $isallowed}
	<form action="" method="post">
		<p>
			<input type="submit" name="clear" value="{$lang.clearShoutbox}" 
				onclick="return window.confirm('{$lang.clearShoutboxAsk}');" />
			<input type="submit" name="clearOld" value="{$lang.clearShoutboxOld}" 
				onclick="return window.confirm('{$lang.clearShoutboxOldAsk}');" />
		</p>
	</form>
{/if}
<p>{$pages}</p>
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th>{$lang.nickname}</th>
		<th>{$lang.post}</th>
		<th>{$lang.timestamp}</th>
		<th>&nbsp;</th>
	</tr>
	
	{foreach from=$posts item=post}
	<tr id="post_{$post.shoutid}">
		<td valign="top"><a href="{$post.url}">{$post.nickname}</a></td>
		<td valign="top">{$post.text}</td>
		<td valign="top">{$post.timestamp_str}</td>
		<td valign="top">
			{if $isallowed}
				<form action="" method="post">
					<input type="hidden" name="shoutid" value="{$post.shoutid}" />
					<input type="submit" name="remove" value="{$lang.remove}" />
				</form>
			{else}
				&nbsp;
			{/if}
		</td>
	</tr>
	{/foreach}
</table>
<p>{$pages}</p>