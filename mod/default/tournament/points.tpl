<form action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<th>{$lang.name}</th>
			<th><div align="right">{$lang.points}</div></th>
		</tr>
		{foreach from=$results item=result}
			<tr class="{cycle values=',highlight_row'}">
				<td>
					<a href="{$result.url}">{$result.name}</a>
				</td>
				<td align="right">
					{if $right.submit_results}
						<input type="text" name="points_{$result.participantid}" 
							value="{$result.points}" style="width:60px; text-align:right;" />
					{else}
						{$result.points}
					{/if}
				</td>
			</tr>
		{/foreach}
	</table>
	{if $right.submit_results}
		<p align="right">
			<input type="submit" name="save" value="{$lang.save}" />
		</p>
	{/if}
</form>