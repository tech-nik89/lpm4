<!-- fancybox -->
<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<!-- category and table styles -->
<style type="text/css">
	tr.row { height:24px; }
	tr.highlight_row { height:24px; }
	div.category, div.maxcategory {
		border:1px solid darkgrey;
		padding:2px 2px 2px 2px;
		width:500px;	
		margin-bottom:20px;
	}
	div.maxcategory {
		width:100%;	
	}
	div.categoryHead {
		position:relative;
		border:1px solid darkgrey;
		padding:2px 2px 2px 2px;	
		background-color:#lightgrey;
		font-weight:bold;
	}
	div.toggleCategoryHoder {
		position:absolute;
		top:2px;
		width:100%;
		text-align:right;
	}
	tr.selected_row td{
		background-color:lightblue;	
		height:24px;
	}
	
	a img{
		border:0px;	
	}
</style>

<!-- Std. vars and functions -->
<script type="text/javascript">
	var loading = "{$lang.loading}";
	var imgsrc="{$imgsrc}";
	
	<!--{literal}
	function toggleCategory(catelement, fasthide) {
		var cat = catelement.getAttribute("cat");
		if(fasthide) {
			if(document.getElementById(cat+"tbl")!=null)$("#"+cat+"tbl").hide();
		}else{
			$("#"+cat+"tbl").fadeToggle("slow","swing");
		}
		var closed = catelement.getAttribute("closed");
		var imgelement = catelement.getElementsByTagName("img")[0];
		if(closed=="1") {
			catelement.setAttribute("closed","0");
			imgelement.src=imgsrc+"/toggle_minus.png";
		}else{
			catelement.setAttribute("closed","1");
			imgelement.src=imgsrc+"/toggle_plus.png";
		}
	}
		
	{/literal}-->
	
	function addUserRight() {
		var uid = document.getElementById("userlist").value;
		if(uid!=""){
			$('<a href="ajax_request.php?mod=ts3admin&file=RightOption.ajax&vsid={$vsid}&sid={$sid}&uid='+uid+'&action=askadd&type=user"></a>').fancybox().click();
		}
	
	}
	
	function addGroupRight() {
		var uid = document.getElementById("grouplist").value;
		if(uid!=""){
			$('<a href="ajax_request.php?mod=ts3admin&file=RightOption.ajax&vsid={$vsid}&sid={$sid}&uid='+uid+'&action=askadd&type=group"></a>').fancybox().click();
		}
	}
</script>


<div class="headline">
	{$lang.rights}
</div>
<form name="managerights" method="GET">
<input type="hidden" name="mod" value="ts3admin" />
<input type="hidden" name="mode" value="manage_rights" />
<input type="hidden" name="osid" value="{$sid}" />

<table>
	<tr>
    	<td>{$lang.server}:
        <select name="sid" onchange="javascript:document.managerights.submit();">
        	{section name=i loop=$server}
            	<option value="{$server[i].ID}" {if $sid==$server[i].ID}selected{/if}>{$server[i].name} - {$server[i].address}</option>
        	{/section}
        </select>
    	</td>
        <td>{$lang.vserver}:
        <select name="vsid" onchange="javascript:document.managerights.submit();">
        	<option value="-1">{$lang.serverrights}</option>
            {section name=i loop=$vservers}
        		<option value="{$vservers[i].id}" {if $vsid==$vservers[i].id}selected{/if}>{$vservers[i].virtualserver_name}</option>
   		 	{/section}
        </select>
        </td>
    </tr>
</table>
</form>
<hr />

<div class="headline">
	{$lang.users}
</div>

{foreach from=$userrightlist item=r}
<div class="category">
    <div class="categoryHead">{$r.nickname}
        <div class="toggleCategoryHoder"><a href="" cat="userrights{$sid}_{$vsid}_{$r.uid}" id="auserrights{$sid}_{$vsid}_{$r.uid}" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="userrights{$sid}_{$vsid}_{$r.uid}tbl" border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td>
            	<a href="{$r.editurl}" id="useredit{$r.uid}"><img src="{$imgsrc}/edit.png" /></a> 
                <a href="{$r.removeurl}" id="userdelete{$r.uid}"><img src="{$imgsrc}/delete.png" /></a> 
                <script type="text/javascript">
					$("#useredit{$r.uid}").fancybox();
					$("#userdelete{$r.uid}").fancybox();
				</script>
            </td>
            <td width="1"></td>
        </tr>
        {if $vsid==-1}
        <tr class="highlight_row">
        	<td>{$lang.r_view_server}</td>
            <td>{if $r.r_view_server==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_edit_server}</td>
            <td>{if $r.r_edit_server==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_add_vservers}</td>
            <td>{if $r.r_add_vservers==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_vservers}</td>
            <td>{if $r.r_remove_vservers==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        {else}
        <tr class="highlight_row">
        	<td>{$lang.r_view_vserver}</td>
            <td>{if $r.r_view_vserver==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_control_vserver}</td>
            <td>{if $r.r_control_vserver==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_edit_vserver}</td>
            <td>{if $r.r_edit_vserver==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_view_grouprights}</td>
            <td>{if $r.r_view_grouprights==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_edit_grouprights}</td>
            <td>{if $r.r_edit_grouprights==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_rename_group}</td>
            <td>{if $r.r_rename_group==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_add_group}</td>
            <td>{if $r.r_add_group==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_group}</td>
            <td>{if $r.r_remove_group==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_view_clients}</td>
            <td>{if $r.r_view_clients==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_msg_client}</td>
            <td>{if $r.r_msg_client==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_kick_client}</td>
            <td>{if $r.r_kick_client==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_ban_client}</td>
            <td>{if $r.r_ban_client==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_change_servergroup}</td>
            <td>{if $r.r_change_servergroup==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_change_channelgroup}</td>
            <td>{if $r.r_change_channelgroup==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_view_clientdetails}</td>
            <td>{if $r.r_view_clientdetails==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_edit_clientdetails}</td>
            <td>{if $r.r_edit_clientdetails==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
      	<tr class="highlight_row">
        	<td>{$lang.r_view_bans}</td>
            <td>{if $r.r_view_bans==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_bans}</td>
            <td>{if $r.r_remove_bans==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
		<tr class="highlight_row">
        	<td>{$lang.r_view_complaints}</td>
            <td>{if $r.r_view_complaints==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_complaints}</td>
            <td>{if $r.r_remove_complaints==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
		<tr class="highlight_row">
        	<td>{$lang.r_view_log}</td>
            <td>{if $r.r_view_log==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_view}</td>
            <td>{if $r.r_view==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_edit_channel}</td>
            <td>{if $r.r_edit_channel==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_channel}</td>
            <td>{if $r.r_remove_channel==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_add_channel}</td>
            <td>{if $r.r_add_channel==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_move_client}</td>
            <td>{if $r.r_move_client==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        {/if}
    </table>
</div>
<script type="text/javascript">
	toggleCategory(document.getElementById("auserrights{$sid}_{$vsid}_{$r.uid}"),true);
</script>
{/foreach}


<br />
{$lang.adduser}: 
<select style="width:200px;" id="userlist">
{foreach from=$users item=user}
{if $user.set!=1}
<option value="{$user.userid}">{$user.nickname}</option>
{/if}
{/foreach}
</select> 
<input type="submit" value="{$lang.add}" onclick="javascript: addUserRight(); return false;" />

{cycle name='rowhighlight' reset=true values=''}
<hr />
<div class="headline">
	{$lang.groups}
</div>

{foreach from=$grouprightlist item=r}
<div class="category">
    <div class="categoryHead">{$r.name}
        <div class="toggleCategoryHoder"><a href="" cat="grouprights{$sid}_{$vsid}_{$r.uid}" id="agrouprights{$sid}_{$vsid}_{$r.uid}" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png" /></a></div>
    </div>
    <table id="grouprights{$sid}_{$vsid}_{$r.uid}tbl" border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td>
            	<a href="{$r.editurl}" id="groupedit{$r.uid}"><img src="{$imgsrc}/edit.png" /></a> 
                <a href="{$r.removeurl}" id="groupdelete{$r.uid}"><img src="{$imgsrc}/delete.png" /></a> 
                <script type="text/javascript">
					$("#groupedit{$r.uid}").fancybox();
					$("#groupdelete{$r.uid}").fancybox();
				</script>
            </td>
            <td width="1"></td>
        </tr>
        {if $vsid==-1}
        <tr class="highlight_row">
        	<td>{$lang.r_view_server}</td>
            <td>{if $r.r_view_server==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_edit_server}</td>
            <td>{if $r.r_edit_server==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_add_vservers}</td>
            <td>{if $r.r_add_vservers==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_vservers}</td>
            <td>{if $r.r_remove_vservers==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        {else}
        <tr class="highlight_row">
        	<td>{$lang.r_view_vserver}</td>
            <td>{if $r.r_view_vserver==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_control_vserver}</td>
            <td>{if $r.r_control_vserver==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_edit_vserver}</td>
            <td>{if $r.r_edit_vserver==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_view_grouprights}</td>
            <td>{if $r.r_view_grouprights==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_edit_grouprights}</td>
            <td>{if $r.r_edit_grouprights==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_rename_group}</td>
            <td>{if $r.r_rename_group==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_add_group}</td>
            <td>{if $r.r_add_group==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_group}</td>
            <td>{if $r.r_remove_group==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_view_clients}</td>
            <td>{if $r.r_view_clients==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_msg_client}</td>
            <td>{if $r.r_msg_client==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_kick_client}</td>
            <td>{if $r.r_kick_client==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_ban_client}</td>
            <td>{if $r.r_ban_client==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_change_servergroup}</td>
            <td>{if $r.r_change_servergroup==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_change_channelgroup}</td>
            <td>{if $r.r_change_channelgroup==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_view_clientdetails}</td>
            <td>{if $r.r_view_clientdetails==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_edit_clientdetails}</td>
            <td>{if $r.r_edit_clientdetails==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
      	<tr class="highlight_row">
        	<td>{$lang.r_view_bans}</td>
            <td>{if $r.r_view_bans==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_bans}</td>
            <td>{if $r.r_remove_bans==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
		<tr class="highlight_row">
        	<td>{$lang.r_view_complaints}</td>
            <td>{if $r.r_view_complaints==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_complaints}</td>
            <td>{if $r.r_remove_complaints==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
		<tr class="highlight_row">
        	<td>{$lang.r_view_log}</td>
            <td>{if $r.r_view_log==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_view}</td>
            <td>{if $r.r_view==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_edit_channel}</td>
            <td>{if $r.r_edit_channel==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_remove_channel}</td>
            <td>{if $r.r_remove_channel==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr class="highlight_row">
        	<td>{$lang.r_add_channel}</td>
            <td>{if $r.r_add_channel==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        <tr>
        	<td>{$lang.r_move_client}</td>
            <td>{if $r.r_move_client==1}<img src="{$imgsrc}/permission.png" />{/if}</td>
        </tr>
        {/if}
    </table>
</div>
<script type="text/javascript">
	toggleCategory(document.getElementById("agrouprights{$sid}_{$vsid}_{$r.uid}"),true);
</script>
{/foreach}

<br />
{$lang.addgroup}: 
<select style="width:200px;" id="grouplist">
{foreach from=$groups item=group}
{if $group.set!=1}
<option value="{$group.groupid}">{$group.name}</option>
{/if}
{/foreach}
</select> 
<input type="submit" value="{$lang.add}" onclick="javascript: addGroupRight(); return false;" />