
<script type="text/javascript">

{if $virtualserver_hostbanner_gfx_url!=""}
var hostbanner_gfx_url="{$virtualserver_hostbanner_gfx_url}";
function refreshHostBanner() {
	var newbannerurl = hostbanner_gfx_url;
	var now = new Date();
	if(hostbanner_gfx_url.indexOf("?")>=0)
		newbannerurl+="&"+now.getTime();
	else
		newbannerurl+="?"+now.getTime();
	document.getElementById("hostbanner").src=newbannerurl;
}
{if $virtualserver_hostbanner_gfx_interval>0}
window.setInterval('refreshHostBanner()',{$virtualserver_hostbanner_gfx_interval}*1000);
{/if}
{/if}

</script>


{if $virtualserver_hostbanner_gfx_url!=""}
	{if $virtualserver_hostbanner_url!=""}
    <a href="" target="_blank"><img id="hostbanner" src="{$virtualserver_hostbanner_gfx_url}" /></a>
    {else}
    <img id="hostbanner" src="{$virtualserver_hostbanner_gfx_url}" />
    {/if}
{/if}
<br  /><br  />
<table>
<tr>
	<td>
    	<b>{$lang.name}:</b> 
    </td>
    <td>
    	{$virtualserver_name}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.address}:</b> 
    </td>
    <td>
    	{$virtualserver_address}:{$virtualserver_port} 
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.version}:</b> 
    </td>
    <td>
    	{$virtualserver_version} {$lang.on} {$virtualserver_platform}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.uptime}:</b> 
    </td>
    <td>
    	{$virtualserver_uptime}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.currentchannels}:</b> 
    </td>
    <td>
    	{$virtualserver_channelsonline}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.currentclients}:</b> 
    </td>
    <td>
    	{$virtualserver_clientsonline} / {$virtualserver_maxclients}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.currentqueries}:</b> 
    </td>
    <td>
    	{$virtualserver_queryclientsonline} / {$virtualserver_maxclients}
    </td>
</tr>
</table>