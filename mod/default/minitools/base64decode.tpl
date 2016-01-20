<div class="headline">
	{$lang.base64} - {$lang.base64decode}
</div>

<form action="" method="POST">
	<table style="width:100%;">
		<tr>
			<th>
				{$lang.text}
			</th>
		</tr>
		<tr>
			<td>
				<textarea name="text" style="width:100%; height:100px;"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="hidden" name="send" value="1" />
				<input type="submit" value="{$lang.base64convert}" />
			</td>
		</tr>
	</table>
</form>

{if !empty($base64decoded)}
	<table style="width:100%; table-layout:fixed;">
		<tr>
			<th>
				{$lang.base64decoded}
			</th>
		</tr>
		<tr>
			<td>
				<textarea style="width:100%; height:100px;">{$base64decoded}</textarea>
			</td>
		</tr>
	</table>
{/if}
