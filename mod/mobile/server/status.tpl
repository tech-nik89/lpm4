<div class="headline">{$lang.state}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<td width="25%">Online:</td>
		<td>{if $status.gq_online}<font color="#00AA00">{$lang.yes}</font>{else}<font color="#AA0000">{$lang.no}</font>{/if}</td>
	</tr>
	<tr>
		<td>Player:</td>
		<td>{$status.players}</td>
	</tr>
	{if $status.bots > 0}
	<tr>
		<td>Bots:</td>
		<td>{$status.bots}</td>
	</tr>
	{/if}
	<tr>
		<td>Port:</td>
		<td>{$status.gq_port}</td>
	</tr>
</table>