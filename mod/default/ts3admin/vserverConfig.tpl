<script type="text/javascript">
	
	{if $ts3vserver_rights.r_edit_vserver==1}
	//old values
	var old_vserver_name = "{$virtualserver_name}";
	var old_vserver_autostart = "{$virtualserver_autostart}";
	var old_vserver_phoneticname = "{$virtualserver_name_phonetic}";
	var old_vserver_bannerurl = "{$virtualserver_hostbanner_url}";
	var old_vserver_bannergfx = "{$virtualserver_hostbanner_gfx_url}";
	var old_vserver_bannerinterval = "{$virtualserver_hostbanner_gfx_interval}";
	var old_vserver_passw = "{$virtualserver_password}";
	var old_vserver_hbuttontooltip = "{$virtualserver_hostbutton_tooltip}";
	var old_vserver_hbuttongfx = "{$virtualserver_hostbutton_gfx_url}";
	var old_vserver_hbuttonurl = "{$virtualserver_hostbutton_url}";
	var old_vserver_port = "{$virtualserver_port}";
	var old_vserver_dlquota = "{$virtualserver_download_quota}";
	var old_vserver_ulquota = "{$virtualserver_upload_quota}";
	var old_vserver_wkmsg = "{$virtualserver_welcomemessage|replace:"\n":"\\n"}";
	var old_vserver_hostmsg = "{$virtualserver_hostmessage|replace:"\n":"\\n"}";
	var old_vserver_hostmsgmode = "{$virtualserver_hostmessage_mode}";
	var old_vserver_maxclients = "{$virtualserver_maxclients}";
	var old_vserver_dimmmod = "{$virtualserver_priority_speaker_dimm_modificator}";
	var old_vserver_minclientversion = "{$virtualserver_min_client_version}";
	var old_vserver_securitylevel = "{$virtualserver_needed_identity_security_level}";
	var old_vserver_reservedslots = "{$virtualserver_reserved_slots}";
	var old_vserver_forcesilence = "{$virtualserver_min_clients_in_channel_before_forced_silence}";
	var old_vserver_complaincount = "{$virtualserver_complain_autoban_count}";
	var old_vserver_complaintime = "{$virtualserver_complain_autoban_time}";
	var old_vserver_complainremove = "{$virtualserver_complain_remove_time}";
	var old_vserver_pointsreduce = "{$virtualserver_antiflood_points_tick_reduce}";
	var old_vserver_pointswarning = "{$virtualserver_antiflood_points_needed_warning}";
	var old_vserver_pointskick = "{$virtualserver_antiflood_points_needed_kick}";
	var old_vserver_pointsban = "{$virtualserver_antiflood_points_needed_ban}";
	var old_vserver_bantime = "{$virtualserver_antiflood_ban_time}";
	var old_vserver_logclient = "{$virtualserver_log_client}";
	var old_vserver_logquery = "{$virtualserver_log_query}";
	var old_vserver_logchannel = "{$virtualserver_log_channel}";
	var old_vserver_logperm = "{$virtualserver_log_permissions}";
	var old_vserver_logserver = "{$virtualserver_log_server}";
	var old_vserver_logft = "{$virtualserver_log_filetransfer}";
	
	<!--{literal}
	function saveVServerConfig() {
		var add_url = "";
		
		var name = document.getElementById("virtualserver_name").value;
		var autostart = document.getElementById("virtualserver_autostart").checked;
		if(autostart){autostart=1;}else{autostart=0;}
		var phoneticname = document.getElementById("virtualserver_name_phonetic").value;
		var bannerurl = document.getElementById("virtualserver_hostbanner_url").value;
		var bannergfx = document.getElementById("virtualserver_hostbanner_gfx_url").value;
		var bannerinterval = document.getElementById("virtualserver_hostbanner_gfx_interval").value;
		var passwf = document.getElementById("virtualserver_flag_password").checked;
		if(passwf){passwf=1;}else{passwf=0;}
		var passw = document.getElementById("virtualserver_password").value;
		var hbuttontooltip = document.getElementById("virtualserver_hostbutton_tooltip").value;
		var hbuttongfx = document.getElementById("virtualserver_hostbutton_gfx_url").value;
		var buttonurl = document.getElementById("virtualserver_hostbutton_url").value;
		var port = document.getElementById("virtualserver_port").value;
		var dlquota = document.getElementById("virtualserver_download_quota").value;
		var ulquota = document.getElementById("virtualserver_upload_quota").value;
		var wkmsg = document.getElementById("virtualserver_welcomemessage").value;
		var hostmsg = document.getElementById("virtualserver_hostmessage").value;
		var hostmsgmode = document.getElementById("virtualserver_hostmessage_mode").value;
		var maxclients = document.getElementById("virtualserver_maxclients").value;
		var dimmmod = document.getElementById("virtualserver_priority_speaker_dimm_modificator").value;
		var minclientversion = document.getElementById("virtualserver_min_client_version").value;
		var securitylevel = document.getElementById("virtualserver_needed_identity_security_level").value;
		var reservedslots = document.getElementById("virtualserver_reserved_slots").value;
		var forcesilence = document.getElementById("virtualserver_min_clients_in_channel_before_forced_silence").value;
		var complaincount = document.getElementById("virtualserver_complain_autoban_count").value;
		var complaintime = document.getElementById("virtualserver_complain_autoban_time").value;
		var complainremove = document.getElementById("virtualserver_complain_remove_time").value;
		var pointsreduce = document.getElementById("virtualserver_antiflood_points_tick_reduce").value;
		var pointswarning = document.getElementById("virtualserver_antiflood_points_needed_warning").value;
		var pointskick = document.getElementById("virtualserver_antiflood_points_needed_kick").value;
		var pointsban = document.getElementById("virtualserver_antiflood_points_needed_ban").value;
		var bantime = document.getElementById("virtualserver_antiflood_ban_time").value;
		var logclient = document.getElementById("virtualserver_log_client").checked;
		if(logclient){logclient=1;}else{logclient=0;}
		var logquery = document.getElementById("virtualserver_log_query").checked;
		if(logquery){logquery=1;}else{logquery=0;}
		var logchannel = document.getElementById("virtualserver_log_channel").checked;
		if(logchannel){logchannel=1;}else{logchannel=0;}
		var logperm = document.getElementById("virtualserver_log_permissions").checked;
		if(logperm){logperm=1;}else{logperm=0;}
		var logserver = document.getElementById("virtualserver_log_server").checked;
		if(logserver){logserver=1;}else{logserver=0;}
		var logft = document.getElementById("virtualserver_log_filetransfer").checked;
		if(logft){logft=1;}else{logft=0;}
		
		if (name!=old_vserver_name) add_url += "&vservername="+encodeURIComponent(name);
		if (port!=old_vserver_port) add_url += "&vserverport="+encodeURIComponent(port);
		if (maxclients!=old_vserver_maxclients) add_url += "&vservermaxclients="+encodeURIComponent(maxclients);
		if (autostart!=old_vserver_autostart) add_url += "&vserverautostart="+encodeURIComponent(autostart);
		if (phoneticname!=old_vserver_phoneticname) add_url += "&vserverphoneticname="+encodeURIComponent(phoneticname);
		if (bannerurl!=old_vserver_bannerurl) add_url += "&vserverbannerurl="+encodeURIComponent(bannerurl);
		if (bannergfx!=old_vserver_bannergfx) add_url += "&vserverbannergfx="+encodeURIComponent(bannergfx);
		if (bannerinterval!=old_vserver_bannerinterval) add_url += "&vserverbannerinterval="+encodeURIComponent(bannerinterval);
		if (passw!=old_vserver_passw) add_url += "&vserverpassw="+encodeURIComponent(passw);
		if (hbuttontooltip!=old_vserver_hbuttontooltip) add_url += "&vserverhbuttontooltip="+encodeURIComponent(hbuttontooltip);
		if (hbuttongfx!=old_vserver_hbuttongfx) add_url += "&vserverhbuttongfx="+encodeURIComponent(hbuttongfx);
		if (buttonurl!=old_vserver_hbuttonurl) add_url += "&vserverbuttonurl="+encodeURIComponent(buttonurl);
		if (dlquota!=old_vserver_dlquota) add_url += "&vserverdlquota="+encodeURIComponent(dlquota);
		if (ulquota!=old_vserver_ulquota) add_url += "&vserverulquota="+encodeURIComponent(ulquota);
		if (wkmsg!=old_vserver_wkmsg) add_url += "&vserverwkmsg="+encodeURIComponent(wkmsg);
		if (hostmsg!=old_vserver_hostmsg) add_url += "&vserverhostmsg="+encodeURIComponent(hostmsg);
		if (hostmsgmode!=old_vserver_hostmsgmode) add_url += "&vserverhostmsgmode="+encodeURIComponent(hostmsgmode);
		if (dimmmod!=old_vserver_dimmmod) add_url += "&vserverdimmmod="+encodeURIComponent(dimmmod);
		if (minclientversion!=old_vserver_minclientversion) add_url += "&vserverminclientversion="+encodeURIComponent(minclientversion);
		if (securitylevel!=old_vserver_securitylevel) add_url += "&vserversecuritylevel="+encodeURIComponent(securitylevel);
		if (reservedslots!=old_vserver_reservedslots) add_url += "&vserverreservedslots="+encodeURIComponent(reservedslots);
		if (forcesilence!=old_vserver_forcesilence) add_url += "&vserverforcesilence="+encodeURIComponent(forcesilence);
		if (complaincount!=old_vserver_complaincount) add_url += "&vservercomplaincount="+encodeURIComponent(complaincount);
		if (complaintime!=old_vserver_complaintime) add_url += "&vservercomplaintime="+encodeURIComponent(complaintime);
		if (complainremove!=old_vserver_complainremove) add_url += "&vservercomplainremove="+encodeURIComponent(complainremove);
		if (pointsreduce!=old_vserver_pointsreduce) add_url += "&vserverpointsreduce="+encodeURIComponent(pointsreduce);
		if (pointswarning!=old_vserver_pointswarning) add_url += "&vserverpointswarning="+encodeURIComponent(pointswarning);
		if (pointskick!=old_vserver_pointskick) add_url += "&vserverpointskick="+encodeURIComponent(pointskick);
		if (pointsban!=old_vserver_pointsban) add_url += "&vserverpointsban="+encodeURIComponent(pointsban);
		if (bantime!=old_vserver_bantime) add_url += "&vserverbantime="+encodeURIComponent(bantime);
		if (logclient!=old_vserver_logclient) add_url += "&vserverlogclient="+encodeURIComponent(logclient);
		if (logquery!=old_vserver_logquery) add_url += "&vserverlogquery="+encodeURIComponent(logquery);
		if (logchannel!=old_vserver_logchannel) add_url += "&vserverlogchannel="+encodeURIComponent(logchannel);
		if (logperm!=old_vserver_logperm) add_url += "&vserverlogperm="+encodeURIComponent(logperm);
		if (logserver!=old_vserver_logserver) add_url += "&vserverlogserver="+encodeURIComponent(logserver);
		if (logft!=old_vserver_logft) add_url += "&vserverlogft="+encodeURIComponent(logft);
		
		if(add_url!="") {
			add_url += "&save=1";
			document.getElementById("vServerManageContent").innerHTML=loading;
			$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=vserverConfig.ajax&sid="+sid+"&vsid="+vsid+add_url);
		}
	}
	{/literal}-->
	{/if}
	
	{if $ts3vserver_rights.r_control_vserver==1}
	<!--{literal}
	function stopVServer() {
		document.getElementById("vServerManageContent").innerHTML=loading;
		$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=vserverConfig.ajax&sid="+sid+"&vsid="+vsid+"&stop=1");
	}
	
	function startVServer() {
		document.getElementById("vServerManageContent").innerHTML=loading;
		$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=vserverConfig.ajax&sid="+sid+"&vsid="+vsid+"&start=1");
	}
	{/literal}-->
	{/if}
	<!--{literal}
	function togglePWEditable() {
		var pwfield = document.getElementById("virtualserver_password");
		if(document.getElementById("virtualserver_flag_password").checked) {
			pwfield.removeAttribute("readonly");
		}else{
			pwfield.value="";
			pwfield.setAttribute("readonly","1");
		}
	}
	
	function refreshBannerPreview() {
		var imgelement = document.getElementById("virtualserver_hostbanner_preview_img");
		var _url = document.getElementById("virtualserver_hostbanner_gfx_url").value;
		if (_url==""){imgelement.style.visibility="hidden";imgelement.style.position="absolute";return;}
		$("#virtualserver_hostbanner_preview_img").error(function(){imgelement.style.visibility="hidden";imgelement.style.position="absolute";});
		$("#virtualserver_hostbanner_preview_img").ready(function(){imgelement.style.visibility="visible";imgelement.style.position="relative";});
		imgelement.src=_url;
 	}
	
	function refreshHostImgPreview() {
		var imgelement = document.getElementById("virtualserver_hostbutton_preview_img");
		var _url = document.getElementById("virtualserver_hostbutton_gfx_url").value;
		if (_url==""){imgelement.style.visibility="hidden";imgelement.style.position="absolute";return;}
		$("#virtualserver_hostbanner_preview_img").error(function(){imgelement.style.visibility="hidden";imgelement.style.position="absolute";});
		$("#virtualserver_hostbanner_preview_img").ready(function(){imgelement.style.visibility="visible";imgelement.style.position="relative";});
		imgelement.src=_url;
 	}
	{/literal}-->
</script>

{if $ts3vserver_rights.r_control_vserver==1}
<input type="submit" value="{$lang.stop}" onclick="javascript: stopVServer(); return false;" />
<input type="submit" value="{$lang.start}" onclick="javascript: startVServer(); return false;" />
{/if}


<div class="maxcategory">
    <div class="categoryHead">{$lang.info}
        <div class="toggleCategoryHoder"><a href="" cat="info" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="infotbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.id}:</td>
            <td>{$virtualserver_id}</td>
        </tr>
        <tr class="highlight_row">
            <td>{$lang.status}:</td>
            {if $virtualserver_status|contains:"virtual" or $virtualserver_status|contains:"offline"}
            <td> <img src="{$imgsrc}/offline.gif" /> {$virtualserver_status}</td>
            {else}
            <td> <img src="{$imgsrc}/online.gif" /> {$virtualserver_status}</td>
            {/if}
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.uptime}:</td>
            <td>{$virtualserver_uptime}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.os}:</td>
            <td>{$virtualserver_platform}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.version}:</td>
            <td>{$virtualserver_version}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.uniqueid}:</td>
            <td>{$virtualserver_unique_identifier}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.createdon}:</td>
            <td>{$virtualserver_created|date_format:"%A, %B %e, %Y %H:%M:%S"}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.autostart}:</td>
            <td><input type="checkbox" id="virtualserver_autostart" {if $virtualserver_autostart eq 1} checked {/if} {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.servername}:</td>
            <td><input type="text" id="virtualserver_name" value="{$virtualserver_name}" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.phoneticname}:</td>
            <td><input type="text" id="virtualserver_name_phonetic" value="{$virtualserver_name_phonetic}" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.servericon}:</td>
            <td>{$virtualserver_icon_id}</td>
        </tr>
    </table>
</div>
        	
<div class="maxcategory">
    <div class="categoryHead">{$lang.password}
        <div class="toggleCategoryHoder"><a href="" cat="passw" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="passwtbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.passwordneeded}:</td>
            <td><input type="checkbox" id="virtualserver_flag_password" {if $virtualserver_flag_password eq 1} checked {/if} onchange="javascript: togglePWEditable();" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.password}:</td>
            <td><input type="password" id="virtualserver_password" value="{$virtualserver_password}" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
    </table>
</div>
        	
<div class="maxcategory">
    <div class="categoryHead">{$lang.banner}
        <div class="toggleCategoryHoder"><a href="" cat="banner" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="bannertbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.targeturl}:</td>
            <td><input type="text" id="virtualserver_hostbanner_url" value="{$virtualserver_hostbanner_url}" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.gfxurl}: </td>
            <td>
                <table width="100%"><tr><td>
                    <input type="text" id="virtualserver_hostbanner_gfx_url" value="{$virtualserver_hostbanner_gfx_url}" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} />
                </td><td>
                    <a href="" onclick="javascript: refreshBannerPreview(); return false;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} ><img src="{$imgsrc}/refresh.png" /></a>
                </td></tr></table>
            </td>
        </tr>
        <tr style="height:0px;">
            <td colspan="2"><img id="virtualserver_hostbanner_preview_img" src=""/></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.gfxinterval}:</td>
            <td><input type="text" id="virtualserver_hostbanner_gfx_interval" value="{$virtualserver_hostbanner_gfx_interval}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /> {$lang.secs}</td>
        </tr>
    </table>
</div>
        	
<div class="maxcategory">
    <div class="categoryHead">{$lang.hostbutton}
        <div class="toggleCategoryHoder"><a href="" cat="hostbutton" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="hostbuttontbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.tooltip}:</td>
            <td><input type="text" id="virtualserver_hostbutton_tooltip" value="{$virtualserver_hostbutton_tooltip}" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.gfxurl}:</td>
            <td>
                <table width="100%"><tr><td>
                    <input type="text" id="virtualserver_hostbutton_gfx_url" value="{$virtualserver_hostbutton_gfx_url}" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} />
                </td><td>
                    <a href="" onclick="javascript: refreshHostImgPreview(); return false;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} ><img src="{$imgsrc}/refresh.png" /></a>
                </td></tr></table>
            </td>
        </tr>
        <tr style="height:0px;">
            <td colspan="2"><img id="virtualserver_hostbutton_preview_img" src=""/></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.targeturl}:</td>
            <td><input type="text" id="virtualserver_hostbutton_url" value="{$virtualserver_hostbutton_url}" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
    </table>
</div>
            
<div class="maxcategory">
    <div class="categoryHead">{$lang.connection}
        <div class="toggleCategoryHoder"><a href="" cat="conn" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="conntbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.serverport}:</td>
            <td><input type="text" id="virtualserver_port" value="{$virtualserver_port}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.downloadquota}:</td>
            <td><input type="text" id="virtualserver_download_quota" value="{$virtualserver_download_quota}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.uploadquota}:</td>
            <td><input type="text" id="virtualserver_upload_quota" value="{$virtualserver_upload_quota}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.bytes_downloaded_thismonth}:</td>
            <td>{$virtualserver_month_bytes_downloaded}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.bytes_uploaded_thismonth}:</td>
            <td>{$virtualserver_month_bytes_uploaded}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.bytes_downloaded_total}:</td>
            <td>{$virtualserver_total_bytes_downloaded}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.bytes_uploaded_total}:</td>
            <td>{$virtualserver_total_bytes_uploaded}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.ft_bw_send}:</td>
            <td>{$connection_filetransfer_bandwidth_sent} Bytes/s</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.ft_bw_received}:</td>
            <td>{$connection_filetransfer_bandwidth_received} Bytes/s</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.packets_send}:</td>
            <td>{$connection_packets_sent_total}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.packets_received}:</td>
            <td>{$connection_packets_received_total}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.bytes_send}:</td>
            <td>{$connection_bytes_sent_total}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.bytes_received}:</td>
            <td>{$connection_bytes_received_total}</td>
        </tr>
    </table>
</div>
            
<div class="maxcategory">
    <div class="categoryHead">{$lang.messages}
        <div class="toggleCategoryHoder"><a href="" cat="messages" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="messagestbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.welcomemessage}:</td>
            <td><textarea id="virtualserver_welcomemessage" rows="4" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} >{$virtualserver_welcomemessage}</textarea></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.hostmessage}:</td>
            <td><textarea id="virtualserver_hostmessage" rows="4" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} >{$virtualserver_hostmessage}</textarea></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.hostmessagemode}:</td>
            <td><select id="virtualserver_hostmessage_mode" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} >
                <option value="0" {if $virtualserver_hostmessage_mode  eq 0} selected {/if}>{$lang.off}</option>
                <option value="1" {if $virtualserver_hostmessage_mode  eq 1} selected {/if}>{$lang.inchat}</option>
                <option value="2" {if $virtualserver_hostmessage_mode  eq 2} selected {/if}>{$lang.modalwindow}</option>
                <option value="3" {if $virtualserver_hostmessage_mode  eq 3} selected {/if}>{$lang.modalwindowandquit}</option>
            </select></td>
        </tr>
        
    </table>
</div>
        	
<div class="maxcategory">
    <div class="categoryHead">{$lang.clients}
        <div class="toggleCategoryHoder"><a href="" cat="clients" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="clientstbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.maxclients}:</td>
            <td><input type="text" id="virtualserver_maxclients" value="{$virtualserver_maxclients}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.clientsonline}:</td>
            <td>{$virtualserver_clientsonline}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.queryclientsonline}:</td>
            <td>{$virtualserver_queryclientsonline}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.dimmmodificator}:</td>
            <td><input type="text" id="virtualserver_priority_speaker_dimm_modificator" value="{$virtualserver_priority_speaker_dimm_modificator}" style="width:100px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /> 
                {$lang.whenproirityspeaker}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.clientssincestart}:</td>
            <td>{$virtualserver_client_connections}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.queryclientssincestart}:</td>
            <td>{$virtualserver_query_client_connections}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.minclientversion}:</td>
            <td><input type="text" id="virtualserver_min_client_version" value="{$virtualserver_min_client_version}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.neededsecuritylevel}:</td>
            <td><input type="text" id="virtualserver_needed_identity_security_level" value="{$virtualserver_needed_identity_security_level}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.reservedslots}:</td>
            <td><input type="text" id="virtualserver_reserved_slots" value="{$virtualserver_reserved_slots}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        
    </table>
</div>
        	
<div class="maxcategory">
    <div class="categoryHead">{$lang.channels}
        <div class="toggleCategoryHoder"><a href="" cat="channels" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="channelstbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.channelsonline}:</td>
            <td>{$virtualserver_channelsonline}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.forcesilenceminclients}:</td>
            <td>
                <input type="text" id="virtualserver_min_clients_in_channel_before_forced_silence" value="{$virtualserver_min_clients_in_channel_before_forced_silence}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /> {$lang.clientsinchannel}</td>
        </tr>
        
        
    </table>
</div>
            
<div class="maxcategory">
    <div class="categoryHead">{$lang.autoban}
        <div class="toggleCategoryHoder"><a href="" cat="autoban" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="autobantbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.complaincount}:</td>
            <td><input type="text" id="virtualserver_complain_autoban_count" value="{$virtualserver_complain_autoban_count}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.banduration}:</td>
            <td><input type="text" id="virtualserver_complain_autoban_time" value="{$virtualserver_complain_autoban_time}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /> {$lang.secs}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.complainremovetime}:</td>
            <td><input type="text" id="virtualserver_complain_remove_time" value="{$virtualserver_complain_remove_time}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /> {$lang.secs}</td>
        </tr>
    </table>
</div>
        
<div class="maxcategory">
    <div class="categoryHead">{$lang.antiflood}
        <div class="toggleCategoryHoder"><a href="" cat="antiflood" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="antifloodtbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.pointsremovedforgood}:</td>
            <td><input type="text" id="virtualserver_antiflood_points_tick_reduce" value="{$virtualserver_antiflood_points_tick_reduce}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /> {$lang.perinterval}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.pointstillwarning}:</td>
            <td><input type="text" id="virtualserver_antiflood_points_needed_warning" value="{$virtualserver_antiflood_points_needed_warning}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.pointstillkick}:</td>
            <td><input type="text" id="virtualserver_antiflood_points_needed_kick" value="{$virtualserver_antiflood_points_needed_kick}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.pointstillban}:</td>
            <td><input type="text" id="virtualserver_antiflood_points_needed_ban" value="{$virtualserver_antiflood_points_needed_ban}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.bantime}:</td>
            <td><input type="text" id="virtualserver_antiflood_ban_time" value="{$virtualserver_antiflood_ban_time}" style="width:50px;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} /> {$lang.secs}</td>
        </tr>
    </table>
</div>
            
<div class="maxcategory">
<div class="categoryHead">{$lang.logging}
    <div class="toggleCategoryHoder"><a href="" cat="logging" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
</div>
    <table id="loggingtbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="row">
            <td style="width:200px;">{$lang.clients}:</td>
            <td>
                <select id="virtualserver_log_client" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} >
                    <option value="0" {if $virtualserver_log_client eq 0} selected {/if}>{$lang.log_off}</option>
                    <option value="1" {if $virtualserver_log_client eq 1} selected {/if}>{$lang.log_error}</option>
                    <option value="2" {if $virtualserver_log_client eq 2} selected {/if}>{$lang.log_warning}</option>
                    <option value="3" {if $virtualserver_log_client eq 3} selected {/if}>{$lang.log_debug}</option>
                    <option value="4" {if $virtualserver_log_client eq 4} selected {/if}>{$lang.log_info}</option>
                </select>
            </td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.queryclients}:</td>
            <td>
                <select id="virtualserver_log_query" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} >
                    <option value="0" {if $virtualserver_log_query eq 0} selected {/if}>{$lang.log_off}</option>
                    <option value="1" {if $virtualserver_log_query eq 1} selected {/if}>{$lang.log_error}</option>
                    <option value="2" {if $virtualserver_log_query eq 2} selected {/if}>{$lang.log_warning}</option>
                    <option value="3" {if $virtualserver_log_query eq 3} selected {/if}>{$lang.log_debug}</option>
                    <option value="4" {if $virtualserver_log_query eq 4} selected {/if}>{$lang.log_info}</option>
                </select>
            </td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.channels}:</td>
            <td>
                <select id="virtualserver_log_channel" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} >
                    <option value="0" {if $virtualserver_log_channel eq 0} selected {/if}>{$lang.log_off}</option>
                    <option value="1" {if $virtualserver_log_channel eq 1} selected {/if}>{$lang.log_error}</option>
                    <option value="2" {if $virtualserver_log_channel eq 2} selected {/if}>{$lang.log_warning}</option>
                    <option value="3" {if $virtualserver_log_channel eq 3} selected {/if}>{$lang.log_debug}</option>
                    <option value="4" {if $virtualserver_log_channel eq 4} selected {/if}>{$lang.log_info}</option>
                </select>
            </td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.permissions}:</td>
            <td>
                <select id="virtualserver_log_permissions" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} >
                    <option value="0" {if $virtualserver_log_permissions eq 0} selected {/if}>{$lang.log_off}</option>
                    <option value="1" {if $virtualserver_log_permissions eq 1} selected {/if}>{$lang.log_error}</option>
                    <option value="2" {if $virtualserver_log_permissions eq 2} selected {/if}>{$lang.log_warning}</option>
                    <option value="3" {if $virtualserver_log_permissions eq 3} selected {/if}>{$lang.log_debug}</option>
                    <option value="4" {if $virtualserver_log_permissions eq 4} selected {/if}>{$lang.log_info}</option>
                </select>
            </td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.servers}:</td>
            <td>
                <select id="virtualserver_log_server" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} >
                    <option value="0" {if $virtualserver_log_server eq 0} selected {/if}>{$lang.log_off}</option>
                    <option value="1" {if $virtualserver_log_server eq 1} selected {/if}>{$lang.log_error}</option>
                    <option value="2" {if $virtualserver_log_server eq 2} selected {/if}>{$lang.log_warning}</option>
                    <option value="3" {if $virtualserver_log_server eq 3} selected {/if}>{$lang.log_debug}</option>
                    <option value="4" {if $virtualserver_log_server eq 4} selected {/if}>{$lang.log_info}</option>
                </select>
            </td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.filetransfers}:</td>
            <td>
                <select id="virtualserver_log_filetransfer" style="width:100%;" {if $ts3vserver_rights.r_edit_vserver!=1}disabled{/if} >
                    <option value="0" {if $virtualserver_log_filetransfer eq 0} selected {/if}>{$lang.log_off}</option>
                    <option value="1" {if $virtualserver_log_filetransfer eq 1} selected {/if}>{$lang.log_error}</option>
                    <option value="2" {if $virtualserver_log_filetransfer eq 2} selected {/if}>{$lang.log_warning}</option>
                    <option value="3" {if $virtualserver_log_filetransfer eq 3} selected {/if}>{$lang.log_debug}</option>
                    <option value="4" {if $virtualserver_log_filetransfer eq 4} selected {/if}>{$lang.log_info}</option>
                </select>
            </td>
        </tr>
    </table>
</div>
{if $ts3vserver_rights.r_edit_vserver==1}
<input type="submit" name="save" value="{$lang.save}" onclick="javascript:saveVServerConfig(); return false;" />
{/if} 
<script type="">
	togglePWEditable();
	refreshBannerPreview();
	refreshHostImgPreview();
</script>