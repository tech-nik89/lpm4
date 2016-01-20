{if $action=="askremove"}
<script type="text/javascript">
	function removeright() {
		$("#askdelete").load("ajax_request.php?mod=ts3admin&file=RightOption.ajax&action=remove&type={$type}&sid={$sid}&vsid={$vsid}&uid="+encodeURIComponent("{$uid}") );
	}
</script>
<div id="askdelete">
	<table style="width:300px;">
        <tr>
            <td colspan="3">{$lang.removerights}</td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: removeright(); return false;"/></td>
            <td width="100%"></td>
            <td align="right"><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
        </tr>
    </table>
</div>

{else if $action=="refresh"}
<script type="text/javascript"> window.location.reload() </script>


{else if $edit}
<script type="text/javascript">
	function addrights() {
		{if $vsid==-1}
		var rights = "&r0="+document.getElementById("r_view_server").checked+
					"&r1="+document.getElementById("r_add_vservers").checked+
					"&r2="+document.getElementById("r_remove_vservers").checked+
					"&r3="+document.getElementById("r_edit_server").checked;
		{else}
		var rights = "&r0="+document.getElementById("r_view_vserver").checked+
					"&r1="+document.getElementById("r_control_vserver").checked+
					"&r2="+document.getElementById("r_edit_vserver").checked+
					"&r3="+document.getElementById("r_view_grouprights").checked+
					"&r4="+document.getElementById("r_edit_grouprights").checked+
					"&r5="+document.getElementById("r_rename_group").checked+
					"&r6="+document.getElementById("r_add_group").checked+
					"&r7="+document.getElementById("r_remove_group").checked+
					"&r8="+document.getElementById("r_view_clients").checked+
					"&r9="+document.getElementById("r_msg_client").checked+
					"&r10="+document.getElementById("r_kick_client").checked+
					"&r11="+document.getElementById("r_ban_client").checked+
					"&r12="+document.getElementById("r_change_servergroup").checked+
					"&r13="+document.getElementById("r_change_channelgroup").checked+
					"&r14="+document.getElementById("r_view_clientdetails").checked+
					"&r15="+document.getElementById("r_edit_clientdetails").checked+
					"&r16="+document.getElementById("r_view_bans").checked+
					"&r17="+document.getElementById("r_remove_bans").checked+
					"&r18="+document.getElementById("r_view_complaints").checked+
					"&r19="+document.getElementById("r_remove_complaints").checked+
					"&r20="+document.getElementById("r_view_log").checked+
					"&r21="+document.getElementById("r_view").checked+
					"&r22="+document.getElementById("r_edit_channel").checked+
					"&r23="+document.getElementById("r_remove_channel").checked+
					"&r24="+document.getElementById("r_add_channel").checked+
					"&r25="+document.getElementById("r_move_client").checked;
		{/if}
		
		$("#editcontent").load("ajax_request.php?mod=ts3admin&file=RightOption.ajax&action={$action}&type={$type}&sid={$sid}&vsid={$vsid}&uid="+encodeURIComponent("{$uid}")+rights );
	}
</script>
<div id="editcontent">
	<table style="width:300px;" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="3">{$lang.setrightsfor}: {$name} </td>
        </tr>
        <tr>
        	<td colspan="2">
            	<hr />
            </td>
        </tr>
        {if $vsid==-1}
        <tr class="highlight_row">
        	<td>{$lang.r_view_server}</td>
            <td><input type="checkbox" id="r_view_server" {if $rights.r_view_server}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_edit_server}</td>
            <td><input type="checkbox" id="r_edit_server" {if $rights.r_edit_server}checked{/if} /></td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_add_vservers}</td>
            <td><input type="checkbox" id="r_add_vservers" {if $rights.r_add_vservers}checked{/if} /></td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_vservers}</td>
            <td><input type="checkbox" id="r_remove_vservers" {if $rights.r_remove_vservers}checked{/if} /></td>
        </tr>
        {else}
        <tr class="highlight_row">
        	<td>{$lang.r_view_vserver}</td>
            <td><input type="checkbox" id="r_view_vserver" {if $rights.r_view_vserver}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_control_vserver}</td>
            <td><input type="checkbox" id="r_control_vserver" {if $rights.r_control_vserver}checked{/if} /></td>
        </tr>	
        <tr class="highlight_row">
        	<td>{$lang.r_edit_vserver}</td>
            <td><input type="checkbox" id="r_edit_vserver" {if $rights.r_edit_vserver}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_view_grouprights}</td>
            <td><input type="checkbox" id="r_view_grouprights" {if $rights.r_view_grouprights}checked{/if} /></td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_edit_grouprights}</td>
            <td><input type="checkbox" id="r_edit_grouprights" {if $rights.r_edit_grouprights}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_rename_group}</td>
            <td><input type="checkbox" id="r_rename_group" {if $rights.r_rename_group}checked{/if} /></td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_add_group}</td>
            <td><input type="checkbox" id="r_add_group" {if $rights.r_add_group}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_group}</td>
            <td><input type="checkbox" id="r_remove_group" {if $rights.r_remove_group}checked{/if} /></td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_view_clients}</td>
            <td><input type="checkbox" id="r_view_clients" {if $rights.r_view_clients}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_msg_client}</td>
            <td><input type="checkbox" id="r_msg_client" {if $rights.r_msg_client}checked{/if} /></td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_kick_client}</td>
            <td><input type="checkbox" id="r_kick_client" {if $rights.r_kick_client}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_ban_client}</td>
            <td><input type="checkbox" id="r_ban_client" {if $rights.r_ban_client}checked{/if} /></td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_change_servergroup}</td>
            <td><input type="checkbox" id="r_change_servergroup" {if $rights.r_change_servergroup}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_change_channelgroup}</td>
            <td><input type="checkbox" id="r_change_channelgroup" {if $rights.r_change_channelgroup}checked{/if} /></td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_view_clientdetails}</td>
            <td><input type="checkbox" id="r_view_clientdetails" {if $rights.r_view_clientdetails}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_edit_clientdetails}</td>
            <td><input type="checkbox" id="r_edit_clientdetails" {if $rights.r_edit_clientdetails}checked{/if} /></td>
        </tr>    
		<tr class="highlight_row">
        	<td>{$lang.r_view_bans}</td>
            <td><input type="checkbox" id="r_view_bans" {if $rights.r_view_bans}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_bans}</td>
            <td><input type="checkbox" id="r_remove_bans" {if $rights.r_remove_bans}checked{/if} /></td>
        </tr>    
		<tr class="highlight_row">
        	<td>{$lang.r_view_complaints}</td>
            <td><input type="checkbox" id="r_view_complaints" {if $rights.r_view_complaints}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_complaints}</td>
            <td><input type="checkbox" id="r_remove_complaints" {if $rights.r_remove_complaints}checked{/if} /></td>
        </tr> 
        <tr class="highlight_row">
        	<td>{$lang.r_view_log}</td>
            <td><input type="checkbox" id="r_view_log" {if $rights.r_view_log}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_view}</td>
            <td><input type="checkbox" id="r_view" {if $rights.r_view}checked{/if} /></td>
        </tr> 
        <tr class="highlight_row">
        	<td>{$lang.r_edit_channel}</td>
            <td><input type="checkbox" id="r_edit_channel" {if $rights.r_edit_channel}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_channel}</td>
            <td><input type="checkbox" id="r_remove_channel" {if $rights.r_remove_channel}checked{/if} /></td>
        </tr> 
        <tr class="highlight_row">
        	<td>{$lang.r_add_channel}</td>
            <td><input type="checkbox" id="r_add_channel" {if $rights.r_add_channel}checked{/if}/></td>
        </tr>
        <tr>
        	<td>{$lang.r_move_client}</td>
            <td><input type="checkbox" id="r_move_client" {if $rights.r_move_client}checked{/if} /></td>
        </tr>
        {/if}
        <tr>
            <td><input type="submit" value="{$lang.ok}" style="width:50px;" onclick="javascript: addrights(); return false;"/></td>        
        </tr>
    </table>
</div>


{/if}