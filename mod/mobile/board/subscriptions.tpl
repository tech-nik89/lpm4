<div class="headline">{$lang.thread_subscriptions}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	{foreach from=$subscriptions item=subscription}
	<tr class="{cycle values=',highlight_row'}">
		<td>
			<a href="{$subscription.url}">
				{$subscription.thread}
			</a>
		</td>
		<td align="right">
			<form action="" method="post">
				<input type="hidden" name="threadid" value="{$subscription.threadid}" />
				<input type="submit" name="delete" value="{$lang.delete}" />
			</form>
		</td>
	</tr>
	{/foreach}
	
</table>