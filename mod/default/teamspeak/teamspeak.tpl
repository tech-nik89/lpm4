<div class="headline">{$lang.teamspeak}</div>
<script type="text/javascript">
<!--{literal}
function enterChannel_0(channelName, serverprot, channelprot) {
	
	var tsadress = "teamspeak://localhost:8767/?channel=" + channelName + "?nickname=admin";
	
		if (serverprot == 1) {
			var serverpassphp = false;
				if (serverpassphp == false) { 
						var password=window.prompt('Teamspeak Server Passwort eingeben', '');
						if (password == null) {
							return;
						} else if (password == '') {
							window.alert('Kein Passwort eingegeben');
							return;
						}
						tsadress = tsadress + '?password=' + escape(password);
				}
				else {tsadress = tsadress + '?password=' + serverpassphp; }
			}
		if (channelprot == 1) {
				var channelpassword=window.prompt('Raum ' + channelName + ' verlangt ein Passwort', '');
				if (channelpassword == null) {
					return;
				} else if (channelpassword == '') {
					window.alert('Kein Passwort eingegeben');
					return;
				}
				tsadress = tsadress + '?channelpassword=' + escape(channelpassword);
		}
		
	window.location=tsadress;
}
{/literal}-->
</script>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
{section name=i loop=$server}
	<tr>
	<td>
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="33"><img src="{$servericon}" width="16" height="16" border="0" alt="{$server[i].server_welcomemessage}"></td><td class="teamspeak">{if $loggedin}<a href="{$server[i].serverurl}"> {$server[i].server_name}</a>{else}{$server[i].server_name}{/if} ({$server[i].address})</td>
	</tr>
	</table>
	{section name=j loop=$server[i].channel}
		<tr>
		<td>
		 <table border="0"  cellpadding="0" cellspacing="0">
		 	<tr height="16">
		     <td width="{$server[i].channel[j].gridwidth}">{section name="l" start=0 loop=$server[i].channel[j].gridicon step=1}<img src="{$gridpath}" width="16" height="16" border="0" alt="">{/section}<img width="16" height="16" src="{$grid2icon}" border="0" alt=""><img src="{$channelicon}" width="16" height="16" border="0" alt=""></td>
		     <td class="channel">&nbsp;{if $loggedin}
			<a class="channellink" id="{$server[i].id}-{$server[i].channel[j].id}" href="{$server[i].channel[j].channelurl}" {$server[i].channel[j].onclick} title="{$server[i].channel[j].topic}"> 
				{$server[i].channel[j].channelname}
			</a>{else}{$server[i].channel[j].channelname}{/if}&nbsp;{$server[i].channel[j].attribute}&nbsp;{$server[i].channel[j].topic}</td>
		     
		  </tr>
		 </table>
		</td>
		</tr>
		{section name=k loop=$server[i].channel[j].player}
			<tr height="16">
			<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<tr><td width="{$server[i].channel[j].player[k].gridwidth}" height="16">
				{section name="l" start=0 loop=$server[i].channel[j].player[k].gridicon step=1}<img src="{$gridpath}" width="16" height="16" border="0" alt="">{/section}<img src="{$grid2icon}" width="16" height="16" border="0" alt=""<img src="{$tsimagepath}{$server[i].channel[j].player[k].attribute}" width="16" height="16" border="0" alt="Time [online: {$server[i].channel[j].player[k].totaltime} | idle: {$server[i].channel[j].player[k].idletime}] Ping: {$server[i].channel[j].player[k].pingtime}ms">
			</td><td class="player" title="Time [online: {$server[i].channel[j].player[k].totaltime} | idle: {$server[i].channel[j].player[k].idletime}] Ping: {$server[i].channel[j].player[k].pingtime}ms">&nbsp;{$server[i].channel[j].player[k].playername} {$server[i].channel[j].player[k].flags}
			</td></tr>
			</table>
			</td>
			</tr>
		{/section}
	{/section}
	</td>
	</tr>
{/section}
</table>
