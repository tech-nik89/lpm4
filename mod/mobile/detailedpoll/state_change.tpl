<div class="headline">{$lang.poll_state_change}</div>
<form action="" method="POST">
	<table style="border-collapse:collapse;">
		{section name=status start=0 loop=4 step=1}
		<tr>
			<td>
				<input type="radio" name="state" value="{$smarty.section.status.index}" {if $poll.state == $smarty.section.status.index}checked="checked"{/if} />
			</td>
			<td>
				{$lang.state.{$smarty.section.status.index}}
			</td>
		</tr>
		{/section}
		<tr>
			<td colspan="2">
				<input type="hidden" name="send" value="1" />
				<input type="submit" value="{$lang.save}" />
			</td>
		</tr>
	</table>
</form>