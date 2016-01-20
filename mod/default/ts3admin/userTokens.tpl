<script type="text/javascript">	
	var groups = new Array();
	{foreach from=$groups item=group}
	groups["{$group.sgid}"] = new Array();
	groups["{$group.sgid}"]["sgid"] = "{$group.sgid}";
	groups["{$group.sgid}"]["name"] = "{$group.name}";
	groups["{$group.sgid}"]["type"] = "{$group.type}";
	{/foreach}
	
	var groupsheader = '<tr><th width="320">{$lang.name}</th><th width="50">{$lang.id}</th><th>{$lang.type}</th><th></th></tr>';
	
	var grouptype = new Array();
	grouptype["0"] = "{$grouptypes.0}";
	grouptype["1"] = "{$grouptypes.1}";
	grouptype["2"] = "{$grouptypes.2}";
	
	var removegrouptitle = "{$lang.removegroup}";
	
	if(selectedClient==undefined) {
		var selectedClient=null;
		var selectedClient_className="";
		var selectedClientName="";
		var selectedClientID="";
		var selectedClientTR=null;
		
		var clientgroups = new Array();
		var addableGroups = new Array();
	}
	
	function refreshCurrentSelectedClient() {
		selectClient(selectedClientTR,selectedClientID,selectedClientName);
	}
	
	function selectClient(trelement, cid, clientname) {
		selectedClientName=clientname;
		selectedClientID=cid;
		selectedClientTR=trelement;
		
		//Clear Class
		if(selectedClient!=null) {
			selectedClient.className=selectedClient_className;
		}
		
		//Set Class
		selectedClient_className=trelement.className;
		selectedClient=trelement;
		selectedClient.className="selected_row";
		
		var addgroupopt = document.getElementById("addgroupopt").cloneNode(true);
		var table = document.getElementById("clientgroupstbl");
		table.innerHTML="";
		table.appendChild(addgroupopt);
		table.innerHTML += groupsheader;
		var classIndex = 0;
		addableGroups = new Array();
		
		for(group in groups) {
			group = groups[group];
			var pos = $.inArray(group["sgid"], clientgroups[cid]);
			if (pos!=-1 && clientgroups[cid][group["sgid"]]!=-1) {
				var tr = document.createElement("tr");
				var tdname = document.createElement("td");
				tdname.innerHTML = group["name"];
				tr.appendChild(tdname);
				var tdgsid = document.createElement("td");
				tdgsid.innerHTML = group["sgid"];
				tr.appendChild(tdgsid);
				
				var tdtype = document.createElement("td");
				tdtype.innerHTML = grouptype[group["type"]];
				if((classIndex++)%2==0) {
					tr.className = "highlight_row";
				} else {
					tr.className = "row";
				}
				tr.appendChild(tdtype);
					
				
				var tddelete = document.createElement("td");
				{if $ts3vserver_rights.r_change_servergroup==1}
				var adelete = document.createElement("a");
				adelete.setAttribute("title",removegrouptitle);
				adelete.setAttribute("id","groupdeletea"+group["sgid"]);
				adelete.innerHTML = '<img src="'+imgsrc+'/delete.png"/>';
				adelete.href="ajax_request.php?mod=ts3admin&file=ClientGroup.ajax&sid="+sid+"&vsid="+vsid+"&cid="+encodeURIComponent(cid)+"&group="+group["sgid"]+"&remove=1";
				tddelete.appendChild(adelete);
				{/if}
				tr.appendChild(tddelete);
				table.appendChild(tr);
				
				$("#groupdeletea"+group["sgid"]).fancybox();
			} else {
				var index = addableGroups.length;
				addableGroups[index] = new Array();
				addableGroups[index]["id"] = group["sgid"];
				addableGroups[index]["name"] = group["name"];
				addableGroups[index]["type"] = group["type"];
			}
		}
		
		{if $ts3vserver_rights.r_change_servergroup==1}
		// Groups
		document.getElementById("addGroupA").href="ajax_request.php?mod=ts3admin&file=ClientGroup.ajax&sid="+sid+"&vsid="+vsid+"&cid="+encodeURIComponent(cid);
		$("#addGroupA").fancybox();	
		{/if}
		
		// Info
		$("#clientinfocontent").load("ajax_request.php?mod=ts3admin&file=ClientInfo.ajax&sid="+sid+"&vsid="+vsid+"&cid="+encodeURIComponent(cid));
	}
	
	{foreach from=$clients item=client name=aussen}
		clientgroups["{$client.client_unique_identifier}"] = new Array();
		
		{foreach from=","|explode:$client.client_servergroups item=group}
		clientgroups["{$client.client_unique_identifier}"]["{$group}"] = "{$group}";
		{/foreach}
		
		clientgroups["{$client.client_unique_identifier}"]["clienttype"] = "{$client.client_type}";
	{/foreach}
	
</script>
<div id="tempAddClientToGroup"></div>

<div class="maxcategory">
    <div class="categoryHead">{$lang.clients}
        <div class="toggleCategoryHoder"><a href="" cat="clients" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png"/></a></div>
    </div>
    <table id="clientstbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <th>{$lang.nickname}</th>
            <th>{$lang.queryclient}</th>
            <th>{$lang.priorityspeaker}</th>
            <th>{$lang.options}</th>
        </tr>
        {foreach from=$clients item=client name=client}
        <tr class="{cycle values='highlight_row,row'}" style="cursor:pointer;" onclick="javascript:selectClient(this,'{$client.client_unique_identifier}','{$client.client_nickname}');" >
            <td>{$client.client_nickname}</td>
            <td><input type="checkbox" {if $client.client_type==1} checked {/if} disabled /></td>
            <td><input type="checkbox" {if $client.client_is_priority_speaker==1} checked {/if} disabled /></td>
            <td>
                {if $ts3vserver_rights.r_msg_client==1}
                <a href="ajax_request.php?mod=ts3admin&file=UserOption.ajax&asksendmsg=1&sid={$sid}&vsid={$vsid}&cid=" title="{$lang.msgtoclient}" 
                        id="msgtoclient{$smarty.foreach.client.iteration}" >
                    <img src="{$imgsrc}/msg.png" />
                </a>
                <a href="ajax_request.php?mod=ts3admin&file=UserOption.ajax&askpoke=1&sid={$sid}&vsid={$vsid}&cid=" title="{$lang.pokeclient}" 
                        id="poke{$smarty.foreach.client.iteration}" >
                    <img src="{$imgsrc}/poke.png" />
                </a>
                {/if}
                <a href="ajax_request.php?mod=ts3admin&file=UserOption.ajax&askcomplaint=1&sid={$sid}&vsid={$vsid}&cid=" title="{$lang.sendcomplaint}" 
                        id="sendcomplaint{$smarty.foreach.client.iteration}" >
                    <img src="{$imgsrc}/send_complaint.png" />
                </a>
                {if $ts3vserver_rights.r_kick_client==1}															
                <a href="ajax_request.php?mod=ts3admin&file=UserOption.ajax&askkickfromchannel=1&sid={$sid}&vsid={$vsid}&cid=" title="{$lang.kickfromchannel}" 
                        id="kickfromchannel{$smarty.foreach.client.iteration}" >
                    <img src="{$imgsrc}/kick_from_channel.png" />
                </a>
                <a href="ajax_request.php?mod=ts3admin&file=UserOption.ajax&askkickfromserver=1&sid={$sid}&vsid={$vsid}&cid=" title="{$lang.kickfromserver}" 
                        id="kickfromserver{$smarty.foreach.client.iteration}" >
                    <img src="{$imgsrc}/kick_from_server.png" />
                </a>
                {/if}
                {if $ts3vserver_rights.r_ban_client==1}
                <a href="ajax_request.php?mod=ts3admin&file=UserOption.ajax&askban=1&sid={$sid}&vsid={$vsid}&cid=" title="{$lang.banclient}" 
                        id="banclient{$smarty.foreach.client.iteration}" >
                    <img src="{$imgsrc}/ban_client.png" />
                </a>
                {/if}
                
                <script type="text/javascript">
                    {if $ts3vserver_rights.r_msg_client==1}
					document.getElementById("msgtoclient{$smarty.foreach.client.iteration}").href += encodeURIComponent("{$client.client_unique_identifier}");
                    document.getElementById("poke{$smarty.foreach.client.iteration}").href += encodeURIComponent("{$client.client_unique_identifier}");
                   	{/if}
				    document.getElementById("sendcomplaint{$smarty.foreach.client.iteration}").href += encodeURIComponent("{$client.client_unique_identifier}");
                    {if $ts3vserver_rights.r_kick_client==1}
					document.getElementById("kickfromchannel{$smarty.foreach.client.iteration}").href += encodeURIComponent("{$client.client_unique_identifier}");
                    document.getElementById("kickfromserver{$smarty.foreach.client.iteration}").href += encodeURIComponent("{$client.client_unique_identifier}");
					{/if}
					{if $ts3vserver_rights.r_ban_client==1}
					document.getElementById("banclient{$smarty.foreach.client.iteration}").href += encodeURIComponent("{$client.client_unique_identifier}");
					{/if}
                </script>
                <script type="text/javascript">
                    $("#poke{$smarty.foreach.client.iteration}").fancybox();
                    $("#msgtoclient{$smarty.foreach.client.iteration}").fancybox();
                    $("#sendcomplaint{$smarty.foreach.client.iteration}").fancybox();
                    $("#kickfromchannel{$smarty.foreach.client.iteration}").fancybox();
                    $("#kickfromserver{$smarty.foreach.client.iteration}").fancybox();
                    $("#banclient{$smarty.foreach.client.iteration}").fancybox();
                </script>
            </td>
        </tr>
        {/foreach}
    </table>
</div>
			
<div class="maxcategory">
    <div class="categoryHead">{$lang.groups}
        <div class="toggleCategoryHoder"><a href="" cat="clientgroups" class="toggleCategory" onclick="javascript: toggleCategory(this); return false;"><img src="{$imgsrc}/toggle_minus.png"/></a></div>
    </div>
    <table id="clientgroupstbl" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>{$lang.nouserselected}</td>
        </tr>
        <tr id="addgroupopt">
            <td colspan="3">
                {if $ts3vserver_rights.r_change_servergroup==1}
                <a href="" id="addGroupA" title="{$lang.addgroup}" onclick="javascript:return false;" >
                	<img src="{$imgsrc}/add.png" /></a>
                {/if}
            </td>
        </tr>
    </table>
</div>

<hr />
<div class="headline">
	<a href="" onclick="javascript: refreshCurrentSelectedClient(); return false;" style="border:0px;" > <img src="{$imgsrc}/refresh.png" style="border:0px;" /> </a> &nbsp;
	{$lang.clientinfo}
</div>
<div id="clientinfocontent">{$lang.nouserselected}</div>