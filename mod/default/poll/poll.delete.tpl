<div class="headline">{$headline}</div>
{if $deleted == 1}
	<div>
		{$info}
	</div>
{else}
	<table style="padding-top:5px;">
		<colgroup>
		<col width="50%">
		<col width="50%">
		</colgroup>
		<tr>
			<td colspan="2">
				{$confirm_delete}
			</td>
		</tr>
		<tr>
			<td style="text-align:center;">
				<form action="#" method="POST">
					<input type="hidden" name="pollID" value="{$poll_ID}"/>
					<input type="hidden" name="imsure" value="1"/>
					<input type="submit" value="{$delete_yes}"/>
				</form> 
			</td>
			<td>
				<form action="{$goto_umfrage}" method="POST">
					<input type="submit" value="{$delete_no}"/>
				</form> 
			</td>
		</tr>
	</table>
{/if}
