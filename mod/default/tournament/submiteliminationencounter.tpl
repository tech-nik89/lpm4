<div class="headline">{$lang.submit_form}</div>

<form method="post" action="">
	<table width="100%" border="0" cellpadding="10" cellspacing="2">
		<tr>
			<td colspan="2">
				<strong>{$lang.notice}:</strong><br />
				{$lang.submit_notice}
			</td>
		</tr>
		
		<tr>
			<td width="100">{$player1.name}:</td>
			<td>
				<input type="number" name="player1p" autocomplete="off"  />
			</td>
		</tr>
		
		<tr>
			<td>{$player2.name}:</td>
			<td>
				<input type="number" name="player2p"autocomplete="off" />
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
				<input type="submit" value="{$lang.submit_button}" name="submit" />
			</td>
		</tr>
	</table>
</form>