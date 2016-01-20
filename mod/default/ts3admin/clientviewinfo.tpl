
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

var servergroups = new Array();
{foreach from=$sgroups item=sg}
servergroups["{$sg.sgid}"] = new Array();
servergroups["{$sg.sgid}"]["sgid"]="{$sg.sgid}";
servergroups["{$sg.sgid}"]["name"]="{$sg.name}";
servergroups["{$sg.sgid}"]["iconid"]="{$sg.iconid}";
{/foreach}

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
    	<b>{$lang.nickname}:</b> 
    </td>
    <td>
    	{$client_nickname} ({$cid})
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.uniqueid}:</b> 
    </td>
    <td>
    	{$client_unique_identifier}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.dbid}:</b> 
    </td>
    <td>
         {$client_database_id}
    </td>
</tr>
{if $client_description!=""}
<tr>
	<td>
    	<b>{$lang.description}:</b> 
    </td>
    <td>
    	{$client_description}
    </td>
</tr>
{/if}
<tr>
	<td>
    	<b>{$lang.version}:</b> 
    </td>
    <td>
    	{$client_version} {$lang.on} {$client_platform}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.connections}:</b> 
    </td>
    <td>
    	{$client_totalconnections}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.createdon}:</b> 
    </td>
    <td>
    	{$client_created|date_format:"%A, %B %e, %Y %H:%M:%S"}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.lastconnected}:</b> 
    </td>
    <td>
    	{$client_lastconnected|date_format:"%A, %B %e, %Y %H:%M:%S"}
    </td>
</tr>

<tr>
	<td colspan="2">
    	<img src="{$imgsrc}/permissions_server_groups.png" /> <b>{$lang.servergroups}: </b>
    </td>
</tr>
<tr>
	<td clospan="2" >
    <table id="servergroups">
    </table>
		<script type="text/javascript">
        var tr;
		var table = document.getElementById("servergroups");
		{foreach from=$client_servergroups item=sg} 
			tr = document.createElement("tr");
			if(servergroups["{$sg.sgid}"]["iconid"]!="0") {
				tr.innerHTML += '<td width="20"><img src="{$imgsrc}/groupicons/group_'+servergroups["{$sg.sgid}"]["iconid"]+'.png" /></td>';	
			}else{
				tr.innerHTML += '<td width="20"></td>';
			}
			tr.innerHTML += '<td> '+servergroups["{$sg.sgid}"]["name"]+'</td>';
			table.appendChild(tr);
        {/foreach}
		</script>
    </td>
</tr>

<tr>
	<td colspan="2">
    	<img src="{$imgsrc}/permissions_channel_groups.png" /> <b>{$lang.channelgroups}: </b>
    </td>
</tr>
<tr>
	<td clospan="2">
    <table id="channelgroup">
    	<td width="20">
        	{if $channelgroup.iconid!=0}
            	<img src="{$imgsrc}/groupicons/group_{$channelgroup.iconid}.png" />
            {/if}
        </td>
        <td>
        	{$channelgroup.name}
        </td>
    </table>
    </td>
</tr>

<tr>
	<table>
    	<tr>
    	<td valign="top">
        	<img src="" id="clientimg" border="0" /> 
            <script type="text/javascript">
			document.getElementById("clientimg").src="ajax_request.php?mod=ts3admin&file=image.ts3.ajax&type=client&cid="+encodeURIComponent("{$cid}")+
														"&sid={$sid}&vsid={$vsid};resize";
			</script>
        </td>
        <td valign="top">
        	{if $client_output_muted}
            	<div valign="middle"><img src="{$imgsrc}/big_output_muted.png" /> {$lang.outputmuted}</div>
                <br />
            {/if}
            {if $client_input_muted}
            	<div><img src="{$imgsrc}/big_input_muted.png" /> {$lang.inputmuted}</div>
            {/if}
        </td>
    	</tr>
    </table>
</tr>
</table>

