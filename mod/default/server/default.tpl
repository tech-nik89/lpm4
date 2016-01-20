<div class="headline">{$lang.servers}</div>

<script type="text/javascript">
	{literal}
	$(document).ready(function() {
	{/literal}	
		
		{foreach from=$serverlist item=server}
			{if $server.gameq != ""}
				$("#server_{$server.serverid}").html('<img src="mod/default/server/working.gif" border="0" />');
				$("#server_{$server.serverid}").load("ajax_request.php?mod=server&file=online.ajax&ipadress={$server.ipadress}&port={$server.port}&gameq={$server.gameq}");
			{/if}
		{/foreach}
		
	{literal}
	});
	{/literal}
</script>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<th>{$lang.name}</th>
		<th>{$lang.game}</th>
		<th>{$lang.ipadress}</th>
		<th>{$lang.online}</th>
	</tr>
	
	{foreach from=$serverlist item=server}
		<tr>
			<td><a href="{$server.url}">{$server.name}</a></td>
			<td>{$server.game}</td>
			<td>{$server.ipadress}</td>
			<td><div id="server_{$server.serverid}"></div></td>
		</tr>
	{/foreach}
	
</table>