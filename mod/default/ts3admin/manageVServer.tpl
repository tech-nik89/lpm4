<script type="text/javascript">
	var vsid = {$vsid};
	
	var viewid = 0;
	var lastSelectedViewBtn=null;
	function activateView(vid) {
		vid = parseInt(vid);
		if(vid>-1) {
			viewid=vid;
			
			if(lastSelectedViewBtn!='undefined' && lastSelectedViewBtn!=null) {
				lastSelectedViewBtn.setAttribute("style","font-weight:normal");	
			}
			var inputElement = document.getElementById("viewBtn"+vid);
			if(inputElement!='undefined' && inputElement!=null) {
				inputElement.setAttribute("style","font-weight:bold");	
				lastSelectedViewBtn=inputElement;
			}
			document.cookie = 'lastVServerManagerView='+vid+';'; 
		}
		document.getElementById("vServerManageContent").innerHTML=loading;	
		
		switch(viewid) {
			{if $ts3vserver_rights.r_view==1}
			case 7: //Viewer
				$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=viewer.ajax&sid="+sid+"&vsid="+vsid, toggleCategoryByCookie);
			 	break;
			{/if}
			{if $ts3vserver_rights.r_view_log==1}
			case 6: //logs
				$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=logs.ajax&sid="+sid+"&vsid="+vsid, toggleCategoryByCookie);
			 	break;
			{/if}
			{if $ts3vserver_rights.r_view_complaints==1}
			case 5: //Complains
				$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=complains.ajax&sid="+sid+"&vsid="+vsid, toggleCategoryByCookie);
			 	break;
			{/if}
			{if $ts3vserver_rights.r_view_bans==1}
			case 4: //Bans
				$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=banlist.ajax&sid="+sid+"&vsid="+vsid, toggleCategoryByCookie);
			 	break;
			{/if}
			{if $ts3vserver_rights.r_view_clientdetails==1}
			case 3: //User Tokens
				$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=userTokens.ajax&sid="+sid+"&vsid="+vsid, toggleCategoryByCookie);
			 	break;
			{/if}
			{if $ts3vserver_rights.r_view_grouprights==1}
			case 2: //Rights manager
				$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=rightsManagement.ajax&sid="+sid+"&vsid="+vsid, toggleCategoryByCookie);
			 	break;
			{/if}
			case 1: //VServerConfig
				$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=vserverConfig.ajax&sid="+sid+"&vsid="+vsid, toggleCategoryByCookie);
			 	break;
			default: //ServerConfig
				$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=serverConfig.ajax&sid="+sid+"&vsid="+vsid, toggleCategoryByCookie);
		}
		toggleCategoryByCookie();
	}
	
</script>

<div style="position:relative;">
    <div style="position:absolute; top:0px;">
		{if $ts3server_rights.r_add_vservers==1}
        <a href="ajax_request.php?mod=ts3admin&file=newVServer.ajax&sid={$sid}" id="newVServerA" title="{$lang.newvserver}" ><img src="{$imgsrc}/add.png" /></a>
        {/if}
        {if $ts3server_rights.r_remove_vservers==1}
        <a href="ajax_request.php?mod=ts3admin&file=deleteVServer.ajax&sid={$sid}&vsid={$vsid}" id="deleteVServerA" title="{$lang.deleteserver}" ><img src="{$imgsrc}/delete.png" /></a> 
    	{/if}
        <a href="" onclick="javascript: activateView(-1); return false;" style="border:0px;" title="{$lang.refreshpage}"> <img src="{$imgsrc}/refresh.png" style="border:0px;" /> </a> &nbsp;
        <input type="submit" name="serverinfo" id="viewBtn0" value="{$lang.serverconfig}" onclick="javascript:activateView(0); return false;"  />
        <input type="submit" name="vserverinfo" id="viewBtn1" value="{$lang.vserverconfig}" onclick="javascript:activateView(1); return false;"  />
        {if $ts3vserver_rights.r_view_grouprights==1}
        <input type="submit" name="rights" id="viewBtn2" value="{$lang.rightsandgroups}" onclick="javascript:activateView(2); return false;"  /> 
        {/if}
        {if $ts3vserver_rights.r_view_clientdetails==1}
        <input type="submit" name="usertokens" id="viewBtn3" value="{$lang.userTokens}" onclick="javascript:activateView(3); return false;"  /> 
        {/if}
        {if $ts3vserver_rights.r_view_bans==1}
        <input type="submit" name="bans"id="viewBtn4"  value="{$lang.bans}" onclick="javascript:activateView(4); return false;"  />    
        {/if}
        {if $ts3vserver_rights.r_view_complaints==1}
        <input type="submit" name="complains"id="viewBtn5"  value="{$lang.complains}" onclick="javascript:activateView(5); return false;"  />   
        {/if}
        {if $ts3vserver_rights.r_view_log==1} 
        <input type="submit" name="logs"id="viewBtn6"  value="{$lang.log}" onclick="javascript:activateView(6); return false;"  />    
        {/if}
        {if $ts3vserver_rights.r_view==1}
        <input type="submit" name="viewchannels"id="viewBtn7"  value="{$lang.viewchannels}" onclick="javascript:activateView(7); return false;"  /> 
        {/if}    
    </div>
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    <hr />
</div>
<p> </p>

<div id="vServerManageContent">
</div>

<script type="text/javascript">
	<!--{literal}
	activateView(getCookieValue("lastVServerManagerView",0));
	
	$("#newVServerA").fancybox();
	$("#deleteVServerA").fancybox();
	{/literal}-->
</script>