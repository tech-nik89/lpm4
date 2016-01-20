<div class="headline">{$lang.server}</div>

<script type="text/javascript">
	{literal}
	$(document).ready(function() {
	{/literal}
		$("#online").html('<img src="mod/default/server/working.gif" border="0" />');
		$("#online").load("ajax_request.php?mod=server&file=status.ajax&ipadress={$server.ipadress}&port={$server.port}&gameq={$server.gameq}");
	{literal}
	});
	{/literal}
</script>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<td width="25%">{$lang.name}:</td>
		<td>{$server.name}</td>
	</tr>
	
	<tr>
		<td>{$lang.description}:</td>
		<td>{$server.description}</td>
	</tr>
	
	<tr>
		<td>{$lang.game}:</td>
		<td>{$server.game}</td>
	</tr>
	
	<tr>
		<td>{$lang.ipadress}:</td>
		<td>{$server.ipadress}:{$server.port}</td>
	</tr>
	
	<tr>
		<td>X-Fire:</td>
		<td><a href="xfire:join?game={$server.game}&server={$server.ipadress}:{$server.port}">{$lang.join}</a></td>
	</tr>
	
	<tr>
		<td>&nbsp;</td>
		<td><a href="xfire:add_server?game={$server.game}&server={$server.ipadress}:{$server.port}">{$lang.add_favorites}</a></td>
	</tr>
	
	<tr>
		<td colspan="2"><div id="online"></div></td>
	</tr>
	
</table>