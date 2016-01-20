<script type="text/javascript">
	{if $ts3server_rights.r_edit_server==1}
	
	var old_prot = "{$serverinstance_filetransfer_port}";
	var old_floodcmds = "{$serverinstance_serverquery_flood_commands}";
	var old_floodtime = "{$serverinstance_serverquery_flood_time}";
	var old_ban_time = "{$serverinstance_serverquery_ban_time}";
	var old_tpl_ac_grp = "{$serverinstance_template_channeladmin_group}";
	var old_tpl_cd_grp = "{$serverinstance_template_channeldefault_group}";
	var old_tpl_sd_grp = "{$serverinstance_template_serverdefault_group}";
	var old_tpl_sa_grp = "{$serverinstance_template_serveradmin_group}";
	var old_g_sq_grp = "{$serverinstance_guest_serverquery_group}";
	
	<!--{literal}
	function saveServerConfig() {
		var post = document.getElementById("serverinstance_filetransfer_port").value;
		var floodcmds = document.getElementById("serverinstance_serverquery_flood_commands").value;
		var floodtime = document.getElementById("serverinstance_serverquery_flood_time").value;
		var bantime = document.getElementById("serverinstance_serverquery_ban_time").value;
		var tpl_ac_grp = document.getElementById("serverinstance_template_channeladmin_group").value;
		var tpl_cd_grp = document.getElementById("serverinstance_template_channeldefault_group").value;
		var tpl_sd_grp = document.getElementById("serverinstance_template_serverdefault_group").value;
		var tpl_sa_grp = document.getElementById("serverinstance_template_serveradmin_group").value;
		var g_sq_grp = document.getElementById("serverinstance_guest_serverquery_group").value;
		
		var add_url = "&save=1";
		if (post!=old_prot) {
			add_url += "&port="+escape(post);
		}
		if (floodcmds!=old_floodcmds) {
			add_url += "&floodcmds="+escape(floodcmds);
		}
		if (floodtime!=old_floodtime) {
			add_url += "&floodtime="+escape(floodtime);
		}
		if (bantime!=old_ban_time) {
			add_url += "&bantime="+escape(bantime);
		}
		if (tpl_ac_grp!=old_tpl_ac_grp) {
			add_url += "&tplcagrp="+escape(tpl_ac_grp);
		}
		if (tpl_cd_grp!=old_tpl_cd_grp) {
			add_url += "&tplcdgrp="+escape(tpl_cd_grp);
		}
		if (tpl_sd_grp!=old_tpl_sd_grp) {
			add_url += "&tplsdgrp="+escape(tpl_sd_grp);
		}
		if (tpl_sa_grp!=old_tpl_sa_grp) {
			add_url += "&tplsagrp="+escape(tpl_sa_grp);
		}
		if (g_sq_grp!=old_g_sq_grp) {
			add_url += "&gsqgrp="+escape(g_sq_grp);
		}
		
		document.getElementById("vServerManageContent").innerHTML=loading;
		$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=serverConfig.ajax&sid="+sid+"&vsid="+vsid+add_url);
	}
	{/literal}-->
	
	{/if}
</script>

<div class="maxcategory">
    <div class="categoryHead">{$lang.info}
        <div class="toggleCategoryHoder"><a href="" cat="info" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="infotbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.uptime}:</td>
            <td>{$instance_uptime}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.servertime}:</td>
            <td>{$host_timestamp_utc|date_format:"%A, %B %e, %Y %H:%M:%S"}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.dbversion}:</td>
            <td>{$serverinstance_database_version}</td>
        </tr>
    </table>
</div>
<div class="maxcategory">
    <div class="categoryHead">{$lang.vservers}
        <div class="toggleCategoryHoder"><a href="" cat="vservers" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="vserverstbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.totalvservers}:</td>
            <td>{$virtualservers_running_total}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.totalmaxclients}:</td>
            <td>{$virtualservers_total_maxclients}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.totalclientsonline}:</td>
            <td>{$virtualservers_total_clients_online}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.totalchanelsonline}:</td>
            <td>{$virtualservers_total_channels_online}</td>
        </tr>
    </table>
</div>
<div class="maxcategory">
    <div class="categoryHead">{$lang.server}
        <div class="toggleCategoryHoder"><a href="" cat="servers" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;">
            <img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="serverstbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.ft_port}:</td>
            <td>
                <input type="text" id="serverinstance_filetransfer_port" value="{$serverinstance_filetransfer_port}" style="width:50px;" {if $ts3server_rights.r_edit_server!=1}disabled{/if}/>
            </td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.floodprotection}:</td>
            <td>
                <input type="text" id="serverinstance_serverquery_flood_commands" value="{$serverinstance_serverquery_flood_commands}" style="width:50px;" {if $ts3server_rights.r_edit_server!=1}disabled{/if}/> {$lang.cmds} in 
                <input type="text" id="serverinstance_serverquery_flood_time" value="{$serverinstance_serverquery_flood_time}" style="width:50px;" {if $ts3server_rights.r_edit_server!=1}disabled{/if}/> {$lang.secs}
            </td>
        </tr>
         <tr class="row">
            <td style="width:200px;">{$lang.bantime}:</td>
            <td>
                <input type="text" id="serverinstance_serverquery_ban_time" value="{$serverinstance_serverquery_ban_time}" style="width:50px;" {if $ts3server_rights.r_edit_server!=1}disabled{/if}/> {$lang.secs}
            </td>
        </tr>
        
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.guestsquerygroup}:</td>
            <td>
                <select id="serverinstance_guest_serverquery_group" style="width:100%;" {if $ts3server_rights.r_edit_server!=1}disabled{/if}>
                    {foreach from=$groups item=group}
                    <option value="{$group.sgid}" {if $serverinstance_guest_serverquery_group eq $group.sgid}selected{/if}>
                        {$group.sgid} | {$group.name} [{$grouptypes[$group.type]}]
                    </option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.templatesadmingroup}:</td>
            <td>
                <select id="serverinstance_template_serveradmin_group" style="width:100%;" {if $ts3server_rights.r_edit_server!=1}disabled{/if}>
                    {foreach from=$groups item=group}
                    <option value="{$group.sgid}" {if $serverinstance_template_serveradmin_group eq $group.sgid}selected{/if}>
                        {$group.sgid} | {$group.name} [{$grouptypes[$group.type]}]
                    </option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.templatesdefaultgroup}:</td>
            <td>
                <select id="serverinstance_template_serverdefault_group" style="width:100%;" {if $ts3server_rights.r_edit_server!=1}disabled{/if}>
                    {foreach from=$groups item=group}
                    <option value="{$group.sgid}" {if $serverinstance_template_serverdefault_group eq $group.sgid}selected{/if}{if $group.iconid!=0}style="background-image: url('{$imgsrc}/groupicons/group_{$group.iconid}.png');"{/if}>
                        {$group.sgid} | {$group.name} [{$grouptypes[$group.type]}]
                    </option>
                    {/foreach}
                </select></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.templatecdefaultgroup}:</td>
            <td>
                <select id="serverinstance_template_channeldefault_group" style="width:100%;" {if $ts3server_rights.r_edit_server!=1}disabled{/if}>
                    {foreach from=$groups item=group}
                    <option value="{$group.sgid}" {if $serverinstance_template_channeldefault_group eq $group.sgid}selected{/if}{if $group.iconid!=0}style="background-image: url('{$imgsrc}/groupicons/group_{$group.iconid}.png');"{/if}>
                        {$group.sgid} | {$group.name} [{$grouptypes[$group.type]}]
                    </option>
                    {/foreach}
                </select></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.templatecadmingroup}:</td>
            <td>
                <select id="serverinstance_template_channeladmin_group" style="width:100%;" {if $ts3server_rights.r_edit_server!=1}disabled{/if}>
                    {foreach from=$groups item=group}
                    <option value="{$group.sgid}" {if $serverinstance_template_channeladmin_group eq $group.sgid}selected{/if}{if $group.iconid!=0}style="background-image: url('{$imgsrc}/groupicons/group_{$group.iconid}.png');"{/if}>
                        {$group.sgid} | {$group.name} [{$grouptypes[$group.type]}]
                    </option>
                    {/foreach}
                </select></td>
        </tr>
        
    </table>
</div>       
<div class="maxcategory">
    <div class="categoryHead">{$lang.connection}
        <div class="toggleCategoryHoder"><a href="" cat="con" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="contbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.ft_bw_send}:</td>
            <td>{$connection_filetransfer_bandwidth_sent} Bytes/s</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.ft_bw_received}:</td>
            <td>{$connection_filetransfer_bandwidth_received} Bytes/s</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.packets_send}:</td>
            <td>{$connection_packets_sent_total}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.packets_received}:</td>
            <td>{$connection_packets_received_total}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.bytes_send}:</td>
            <td>{$connection_bytes_sent_total}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.bytes_received}:</td>
            <td>{$connection_bytes_received_total}</td>
        </tr>
    </table>
</div>
{if $ts3server_rights.r_edit_server==1}
<input type="submit" name="save" value="{$lang.save}" onclick="javascript:saveServerConfig(); return false;" />
{/if}