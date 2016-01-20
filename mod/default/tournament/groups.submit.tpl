<div class="headline">{$lang.submit_results} {$lang.group} {$group.group}</div>

<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<th>{$lang.rank}</th>
			<th>{$lang.name}</th>
		</tr>
		
		{foreach from=$plist item=player}
			
			<tr>
				<td width="45"><input type="number" name="rank_{$player.participantid}" value="{$player.rank}" style="width:35px;" autocomplete="off" /></td>
				<td>{$player.name}</td>
			</tr>
			
		{/foreach}
		
	</table>
	<p>
		<input type="submit" name="submit" value="{$lang.submit_results}" />
	</p>
</form>