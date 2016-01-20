<script type="text/javascript">
	{if $ts3vserver_rights.r_edit_clientdetails==1}
	var cuid = "{$cuid}";
	
	//old values
	var old_client_description = "{$client_description|replace:"\n":"\\n"}";
	var old_client_nickname = "{$client_nickname}";
	var old_client_is_talker = "{$client_is_talker}";
	
	<!--{literal}
	function saveClientInfo() {
		var add_url = "";
		
		var client_nickname = document.getElementById("client_nickname").value;
		var client_is_talker = document.getElementById("client_is_talker").checked;
		if(client_is_talker){client_is_talker=1;}else{client_is_talker=0;}
		var client_description = document.getElementById("client_description").value;
		
		if (client_nickname!=old_client_nickname) add_url += "&nickname="+escape(client_nickname);
		if (client_is_talker!=old_client_is_talker) add_url += "&istalker="+escape(client_is_talker);
		if (client_description!=old_client_description) add_url += "&description="+escape(client_description);
		
		if(add_url!="") {
			add_url += "&save=1";
			document.getElementById("clientinfocontent").innerHTML=loading;
			$("#clientinfocontent").load("ajax_request.php?mod=ts3admin&file=ClientInfo.ajax&sid="+sid+"&vsid="+vsid+"&cid="+encodeURIComponent(cuid)+add_url);
		}
	}
	{/literal}-->
	{/if}
</script>




<div class="maxcategory">
    <div class="categoryHead">{$lang.info}
        <div class="toggleCategoryHoder"><a href="" cat="info" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="infotbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.id}:</td>
            <td>{$client_unique_identifier}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.dbid}:</td>
            <td>{$client_database_id}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.creationdate}:</td>
            <td>{$client_created|date_format:"%A, %B %e, %Y %H:%M:%S"}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.version}:</td>
            <td>{$client_version}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.os}:</td>
            <td>{$client_platform}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.loginname}:</td>
            <td>{$client_login_name}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.phoneticname}:</td>
            <td>{$client_nickname_phonetic}</td>
        </tr>
         <tr class="row">
            <td style="width:200px;">{$lang.name}:</td>
            <td>{$client_nickname}<!--<input type="text" id="client_nickname" value="{$client_nickname}" style="width:100%;" />--></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.description}:</td>
            <td><textarea id="client_description" rows="4" style="width:100%;" {if $ts3vserver_rights.r_edit_clientdetails!=1}disabled{/if}>{$client_description}</textarea></td>
        </tr>
        
    </table>
</div>
        	
<div class="maxcategory">
    <div class="categoryHead">{$lang.connection}
        <div class="toggleCategoryHoder"><a href="" cat="conn" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="conntbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.connectionstotal}:</td>
            <td>{$client_totalconnections}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.lastconnected}:</td>
            <td>{$client_lastconnected|date_format:"%A, %B %e, %Y %H:%M:%S"}</td>
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
    <div class="categoryHead">{$lang.audio}
        <div class="toggleCategoryHoder"><a href="" cat="audio" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="audiotbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.inputmuted}:</td>
            <td><input type="checkbox" {if $client_input_muted==1} checked {/if} disabled /></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.inputmuted}:</td>
            <td><input type="checkbox" {if $client_output_muted==1} checked {/if} disabled /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.inputhwactive}:</td>
            <td><input type="checkbox" {if $client_input_hardware==1} checked {/if} disabled /></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.outputhwactive}:</td>
            <td><input type="checkbox" {if $client_output_hardware==1} checked {/if} disabled /></td>
        </tr>
    </table>
</div>
            
<div class="maxcategory">
    <div class="categoryHead">{$lang.status}
        <div class="toggleCategoryHoder"><a href="" cat="status" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="statustbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.away}:</td>
            <td><input type="checkbox" {if $client_away==1} checked {/if} disabled /></td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.awaymsg}:</td>
            <td>{$client_away_message}</td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.talkpower}:</td>
            <td>{$client_talk_power}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.talkpowerrequest}:</td>
            <td><input type="checkbox" {if $client_talk_request==1} checked {/if} disabled /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.talkpowerrequestmsg}:</td>
            <td>{$client_talk_request_msg}</td>
        </tr>
        <tr class="row">
            <td style="width:200px;">{$lang.proispeaker}:</td>
            <td><input type="checkbox" {if $client_is_priority_speaker==1} checked {/if} disabled /></td>
        </tr>
        <tr class="highlight_row">
            <td style="width:200px;">{$lang.queryclient}:</td>
            <td><input type="checkbox" {if $client_needed_serverquery_view_power==1} checked {/if} disabled /></td>
        </tr>
        <!--
        <tr class="row">
            <td style="width:200px;">{$lang.cantalk}:</td>
            <td><input type="checkbox" id="client_is_talker" {if $client_is_talker==1} checked {/if} /> </td>
        </tr>
        -->
        <tr class="row">
            <td style="width:200px;">{$lang.unreadmsgs}:</td>
            <td>{$client_unread_messages}</td>
        </tr>
    </table>
</div>

{if $ts3vserver_rights.r_edit_clientdetails==1}
{if not $queryclient}
<input type="submit" name="save" value="{$lang.save}" onclick="javascript:saveClientInfo(); return false;" />
{/if}
{/if}
<script type="text/javascript">
	toggleCategoryByCookie();
</script>