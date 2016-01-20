
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
    	{$channel_name} ({$cid})
    </td>
</tr>
{if $channel_topic!=""}
<tr>
	<td>
    	<b>{$lang.topic}:</b> 
    </td>
    <td>
    	{$channel_topic}
    </td>
</tr>
{/if}
<tr>
	<td>
    	<b>{$lang.codec}:</b> 
    </td>
    <td>
    	{if $channel_codec==0}
        	{$lang.codec0}
    	{else if $channel_codec==1}
        	{$lang.codec1}
    	{else if $channel_codec==2}
        	{$lang.codec2}
    	{else if $channel_codec==3}
        	{$lang.codec3}
        {/if}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.codecquality}:</b> 
    </td>
    <td>
    	{$channel_codec_quality}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.type}:</b> 
    </td>
    <td>
    	{$channel_type}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.currentclients}:</b> 
    </td>
    <td>
    	{$channel_clients} /  {if $channel_flag_maxclients_unlimited==1}{$lang.unlimited} {else}{$channel_maxclients} {/if}
    </td>
</tr>
<tr>
	<td>
    	<b>{$lang.unencrypted}:</b> 
    </td>
    <td>
    	{if $channel_codec_is_unencrypted==1} {$lang.yes} {else} {$lang.no} {/if}
    </td>
</tr>
{if $channel_description!=""}
<tr>
	<td colspan="2">
    	<b>{$lang.description}:</b> 
    </td>
    <td></td>
</tr>
<tr>
	<td colspan="2" style="padding-left:10px;">
    	{$channel_description}
    </td>
</tr>
{/if}
</table>