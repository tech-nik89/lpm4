<style type="text/css">

div.treeitem {
	padding-left:10px;
	padding-top:2px;
}

div.client {
	padding-left:20px;
	padding-top:2px;
}

.treeitem a{
	border:0px;
	color:black;
	text-decoration:none;	
	vertical-align:middle;
}

div.itemtitle:hover {
	background-color:#E8F1FF;
	border:1px solid #8894FF;
}

div.selecteditemtitle {
	background-color:#C4C5FF;
	border:1px solid #3E28FF;
}

div.contextmenu {
	border:1px solid black;
	background-color:#eee;	
	padding:3px 3px 3px 3px;
	z-index:999;
}

div.contextmenu a {
	color:black;
	text-decoration:none;
}

div.contextmenu a:hover div {
	background-color:#CCC;
}

span.icon {
	border-left:1px solid #333;	
}
</style>


<script type="text/javascript">
var channels = new Array();

{foreach from=$channels item=chn}
channels["{$chn.cid}"] = new Array();
channels["{$chn.cid}"]["cid"] = "{$chn.cid}";
channels["{$chn.cid}"]["pid"] = "{$chn.pid}";
channels["{$chn.cid}"]["name"] = "{$chn.channel_name}";
channels["{$chn.cid}"]["homechn"] = "{$chn.channel_flag_default}";
channels["{$chn.cid}"]["codec"] = "{$chn.channel_codec}";
channels["{$chn.cid}"]["haspw"] = "{$chn.channel_haspw}";
channels["{$chn.cid}"]["order"] = {$chn.channel_order};
channels["{$chn.cid}"]["icon"] = "{$chn.channel_icon}";
channels["{$chn.cid}"]["clients"] = new Array();
{foreach from=$chn.clients item=clt}
channels["{$chn.cid}"]["clients"]["{$clt.cid}"] = new Array();
channels["{$chn.cid}"]["clients"]["{$clt.cid}"]["cid"] = "{$clt.cid}";
channels["{$chn.cid}"]["clients"]["{$clt.cid}"]["dbid"] = "{$clt.dbid}";
channels["{$chn.cid}"]["clients"]["{$clt.cid}"]["nick"] = "{$clt.nick}";
channels["{$chn.cid}"]["clients"]["{$clt.cid}"]["inmute"] = "{$clt.inmute}";
channels["{$chn.cid}"]["clients"]["{$clt.cid}"]["outmute"] = "{$clt.outmute}";
channels["{$chn.cid}"]["clients"]["{$clt.cid}"]["priospeaker"] = "{$clt.priospeaker}";
channels["{$chn.cid}"]["clients"]["{$clt.cid}"]["channelcommander"] = "{$clt.channelcommander}";

channels["{$chn.cid}"]["clients"]["{$clt.cid}"]["servergroups"] = new Array();
{foreach from=$clt.servergroups item=cltsg}
channels["{$chn.cid}"]["clients"]["{$clt.cid}"]["servergroups"]["{$cltsg}"]="{$cltsg}";
{/foreach}
channels["{$chn.cid}"]["clients"]["{$clt.cid}"]["channelgroup"] = "{$clt.channelgroup}";
{/foreach}

{/foreach}

function sortChannels(chn1, chn2) {
	if(chn1["pid"]==chn2["pid"])
		return chn1["order"] - chn2["order"];	
	else
		return chn1["pid"] - chn2["pid"];	
} 

function channelsUnsorted() {
	var lastchannel = null;
	for(chn in channels) {
		chn = channels[chn];
		if(lastchannel==null) {
			lastchannel=chn; 
			continue;	
		}	
		if(	sortChannels(lastchannel,chn)>0)
			return true;
		lastchannel=chn;
	}
	return false;
}

while(channelsUnsorted()) {
	channels.sort(sortChannels);
}


var servergroups = new Array();
{foreach from=$sgroups item=sg}
servergroups["{$sg.sgid}"] = new Array();
servergroups["{$sg.sgid}"]["sgid"]="{$sg.sgid}";
servergroups["{$sg.sgid}"]["name"]="{$sg.name}";
servergroups["{$sg.sgid}"]["iconid"]="{$sg.iconid}";
{/foreach}

var channelgroups = new Array();
{foreach from=$cgroups item=cg}
channelgroups["{$cg.cgid}"] = new Array();
channelgroups["{$cg.cgid}"]["cgid"]="{$cg.cgid}";
channelgroups["{$cg.cgid}"]["name"]="{$cg.name}";
channelgroups["{$cg.cgid}"]["iconid"]="{$cg.iconid}";
{/foreach}

var selectedtreeitem = null;
function selectTreeItem(itm) {
	if(selectedtreeitem != 'undeinfed' && selectedtreeitem != null) {
		selectedtreeitem.setAttribute("class","itemtitle");
	}
	selectedtreeitem=itm.getElementsByTagName("div");
	selectedtreeitem=selectedtreeitem[0];
	selectedtreeitem.setAttribute("class","selecteditemtitle");
}

{if $ts3vserver_rights.r_edit_channel==1 or $ts3vserver_rights.r_add_channel==1 or $ts3vserver_rights.r_remove_channel==1}
function showChannelContextMenu(e, cid, element) {
	selectTreeItem(element);
	showChannelInfo(cid);
	
	var menu = document.getElementById("channelcontextmenu");
	menu.style.visibility="visible";
	
	if(menu.parentNode!=document.body) {
		menu.parentNode.removeChild(menu);
		document.body.appendChild(menu);
	}
	
	var x = e.pageX-5;
	var y = e.pageY-5;
	menu.style.top = y+"px";
	menu.style.left = x+"px";
	
	var url = "ajax_request.php?mod=ts3admin&file=ChannelOption.ajax&sid="+sid+"&vsid="+vsid+"&cid="+encodeURIComponent(cid);
	{if $ts3vserver_rights.r_edit_channel==1}
	document.getElementById("channeleditoption").href=url+"&askedit=1";
	document.getElementById("channelmoveoption").href=url+"&askmove=1";
	$("#channeleditoption").fancybox();
	$("#channelmoveoption").fancybox();
	{/if}
	{if $ts3vserver_rights.r_remove_channel==1}
	document.getElementById("channeldeleteoption").href=url+"&askdelete=1";
	$("#channeldeleteoption").fancybox();
	{/if}
	{if $ts3vserver_rights.r_add_channel==1}
	document.getElementById("channelcreateoption").href=url+"&askcreate=1";
	document.getElementById("channelcreatesuboption").href=url+"&askcreatesub=1";
	$("#channelcreateoption").fancybox();
	$("#channelcreatesuboption").fancybox();
	{/if}
}
{else}
function showChannelContextMenu(e, cid, element) {
	selectTreeItem(element);
	showChannelInfo(cid);
}
{/if}

function hideChannelContextMenu() {
	var menu = document.getElementById("channelcontextmenu");
	menu.style.visibility="hidden";
	menu.style.left="-100px";
}

function showClientContextMenu(e, cid, element) {
	selectTreeItem(element);
	showClientInfo(cid);
	
	var menu = document.getElementById("clientcontextmenu");
	menu.style.visibility="visible";
	
	if(menu.parentNode!=document.body) {
		menu.parentNode.removeChild(menu);
		document.body.appendChild(menu);
	}
	
	var x = e.pageX-5;
	var y = e.pageY-5;
	menu.style.top = y+"px";
	menu.style.left = x+"px";
	
	var url = "ajax_request.php?mod=ts3admin&file=UserOption.ajax&sid="+sid+"&vsid="+vsid+"&cid="+encodeURIComponent(cid);
	
	{if $ts3vserver_rights.r_msg_client==1}
	document.getElementById("msgtoclientoption").href=url+"&asksendmsg=1";
	document.getElementById("pokeclientoption").href=url+"&askpoke=1";
	$("#msgtoclientoption").fancybox();
	$("#pokeclientoption").fancybox();
	{/if}
	
	document.getElementById("complaintoption").href=url+"&askcomplaint=1";
	$("#complaintoption").fancybox();
	
	{if $ts3vserver_rights.r_edit_clientdetails==1}
	document.getElementById("editdescriptionoption").href=url+"&askeditdescription=1";
	$("#editdescriptionoption").fancybox();
	{/if}
	
	{if $ts3vserver_rights.r_kick_client==1}
	document.getElementById("kickfromchanneloption").href=url+"&askkickfromchannel=1";
	document.getElementById("kickfromserveroption").href=url+"&askkickfromserver=1";
	$("#kickfromchanneloption").fancybox();
	$("#kickfromserveroption").fancybox();
	{/if}
	
	{if $ts3vserver_rights.r_ban_client==1}
	document.getElementById("banclientoption").href=url+"&askban=1";
	$("#banclientoption").fancybox();
	{/if}
	
	{if $ts3vserver_rights.r_change_servergroup==1}
	document.getElementById("servergroupoption").href=url+"&asksetservergroups=1";
	$("#servergroupoption").fancybox();
	{/if}
	
	{if $ts3vserver_rights.r_change_channelgroup==1}
	document.getElementById("channelgroupsoption").href=url+"&asksetchannelgroup=1";
	$("#channelgroupsoption").fancybox();
	{/if}
	
	{if $ts3vserver_rights.r_move_client==1}
	document.getElementById("clientmoveoption").href=url+"&askclientmove=1";
	$("#clientmoveoption").fancybox();
	{/if}
}

function hideClientContextMenu() {
	var menu = document.getElementById("clientcontextmenu");
	menu.style.visibility="hidden";
	menu.style.left="-100px";
}

function showServerContextMenu(e, element) {
	selectTreeItem(element);
	showServerInfo();
	
	var menu = document.getElementById("vservercontextmenu");
	menu.style.visibility="visible";
	
	if(menu.parentNode!=document.body) {
		menu.parentNode.removeChild(menu);
		document.body.appendChild(menu);
	}
	
	var x = e.pageX-5;
	var y = e.pageY-5;
	menu.style.top = y+"px";
	menu.style.left = x+"px";
	
	var url = "ajax_request.php?mod=ts3admin&file=ChannelOption.ajax&sid="+sid+"&vsid="+vsid+"&cid=0";
	{if $ts3vserver_rights.r_add_channel==1}
	document.getElementById("serverchannelcreateoption").href=url+"&askcreate=1";
	{/if}
	
	$("#serverchannelcreateoption").fancybox();
}

function hideServerContextMenu() {
	var menu = document.getElementById("vservercontextmenu");
	menu.style.visibility="hidden";
	menu.style.left="-100px";
}
try {
var oldvservercontecntmenu = document.getElementById("vservercontextmenu");
if(oldvservercontecntmenu!="undeinfed") document.body.removeChild(oldvservercontecntmenu);
}catch(err){}

try {
var oldchannelcontecntmenu = document.getElementById("channelcontextmenu");
if(oldchannelcontecntmenu!="undeinfed") document.body.removeChild(oldchannelcontecntmenu);
}catch(err){}

try {
var oldclientcontecntmenu = document.getElementById("clientcontextmenu");
if(oldclientcontecntmenu!="undeinfed") document.body.removeChild(oldclientcontecntmenu);
}catch(err){}

</script>

<div style="position:absolute;visibility:hidden;" id="vservercontextmenu" class="contextmenu">
    {if $ts3vserver_rights.r_add_channel==1}
    <a href="" onclick="javascript: hideServerContextMenu(); return false;" id="serverchannelcreateoption">
        <div style="width:100%"><img src="{$imgsrc}/channel_create.png" /> {$lang.channelcreate}</div>
    </a>
    <hr />
    {/if}
    <a href="" onclick="javascript: activateView(1); hideServerContextMenu(); return false;">
        <div style="width:100%"><img src="{$imgsrc}/virtualserver_edit.png" /> {$lang.vserveredit}</div>
    </a>
</div>

<div style="position:absolute;visibility:hidden;" id="channelcontextmenu" class="contextmenu">
    {if $ts3vserver_rights.r_edit_channel==1}
    <a href="" onclick="javascript: hideChannelContextMenu(); return false;" id="channeleditoption">
        <div style="width:100%"><img src="{$imgsrc}/channel_edit.png" /> {$lang.channeledit}</div>
    </a>
    <hr />
    {/if}
    
    {if $ts3vserver_rights.r_remove_channel==1}
     <a href="" onclick="javascript: hideChannelContextMenu(); return false;" id="channeldeleteoption">
        <div style="width:100%"><img src="{$imgsrc}/channel_delete.png" /> {$lang.channeldelete}</div>
    </a>
    {if not $ts3vserver_rights.r_edit_channel==1 and $ts3vserver_rights.r_add_channel}
    <hr />
    {/if}
    {/if}
    {if $ts3vserver_rights.r_add_channel==1}
    <a href="" onclick="javascript: hideChannelContextMenu(); return false;" id="channelcreateoption">
        <div style="width:100%"><img src="{$imgsrc}/channel_create.png" /> {$lang.channelcreate}</div>
    </a>
    <a href="" onclick="javascript: hideChannelContextMenu(); return false;" id="channelcreatesuboption">
        <div style="width:100%"><img src="{$imgsrc}/channel_create_sub.png" /> {$lang.channelcreatesub}</div>
    </a>
    {if $ts3vserver_rights.r_edit_channel==1}
    <hr />
    {/if}
    {/if}
    {if $ts3vserver_rights.r_edit_channel==1}
    <a href="" onclick="javascript: hideChannelContextMenu(); return false;" id="channelmoveoption">
        <div style="width:100%"><img src="{$imgsrc}/channel_move.png" /> {$lang.channelmove}</div>
    </a>
    {/if}
</div>

<div style="position:absolute;visibility:hidden;" id="clientcontextmenu" class="contextmenu">
	{if $ts3vserver_rights.r_msg_client==1}
    <a href="" onclick="javascript: hideClientContextMenu(); " id="msgtoclientoption">
        <div style="width:100%"><img src="{$imgsrc}/msg.png" /> {$lang.msgtoclient}</div>
    </a>
    <a href="" onclick="javascript: hideClientContextMenu(); " id="pokeclientoption">
        <div style="width:100%"><img src="{$imgsrc}/poke.png" /> {$lang.pokeclient}</div>
    </a>
    {/if}
    <a href="" onclick="javascript: hideClientContextMenu(); " id="complaintoption">
        <div style="width:100%"><img src="{$imgsrc}/send_complaint.png" /> {$lang.sendcomplaint}</div>
    </a>
    {if $ts3vserver_rights.r_edit_clientdetails==1}
    <a href="" onclick="javascript: hideClientContextMenu(); " id="editdescriptionoption">
        <div style="width:100%"><img src="{$imgsrc}/edit.png" /> {$lang.editdescription}</div>
    </a>
    {/if}
    {if $ts3vserver_rights.r_kick_client==1 or $ts3vserver_rights.r_ban_client==1}
    <hr />
    {/if}
    {if $ts3vserver_rights.r_kick_client==1}
    <a href="" onclick="javascript: hideClientContextMenu(); " id="kickfromchanneloption">
        <div style="width:100%"><img src="{$imgsrc}/kick_from_channel.png" /> {$lang.kickfromchannel}</div>
    </a>
    <a href="" onclick="javascript: hideClientContextMenu(); " id="kickfromserveroption">
        <div style="width:100%"><img src="{$imgsrc}/kick_from_server.png" /> {$lang.kickfromserver}</div>
    </a>
    {/if}
    {if $ts3vserver_rights.r_ban_client==1}
    <a href="" onclick="javascript: hideClientContextMenu(); " id="banclientoption">
        <div style="width:100%"><img src="{$imgsrc}/ban_client.png" /> {$lang.banclient}</div>
    </a>
    {/if}
    {if ($ts3vserver_rights.r_change_servergroup==1 or $r_change_channelgroup.r_ban_client==1)}
    <hr />
    {/if}
    {if $ts3vserver_rights.r_change_servergroup==1}
    <a href="" onclick="javascript: hideClientContextMenu(); " id="servergroupoption">
        <div style="width:100%"><img src="{$imgsrc}/permissions_server_groups.png" /> {$lang.setservergroup}</div>
    </a>
    {/if}
    {if $ts3vserver_rights.r_change_channelgroup==1}
    <a href="" onclick="javascript: hideClientContextMenu(); " id="channelgroupsoption">
        <div style="width:100%"><img src="{$imgsrc}/permissions_channel_groups.png" /> {$lang.setchannelgroup}</div>
    </a>
    {/if}
    {if $ts3vserver_rights.r_move_client==1}
    <hr />
    <a href="" onclick="javascript: hideClientContextMenu(); return false;" id="clientmoveoption">
        <div style="width:100%"><img src="{$imgsrc}/channel_move.png" /> {$lang.clientmove}</div>
    </a>
    {/if}
</div>

<div>
	<div id="serverroot" class="treeitem">
    	<a href="" title="" id="vserver" onclick="javascript:selectTreeItem(this);showServerInfo();return false;"><div id="serverrootname" class="itemtitle"><img src="{$imgsrc}/server_green.png" /> {$virtualserver_name}</div></a>
        <div id="serverrootchilds" class="itemchilds"></div>
	</div>
</div>

<script type="text/javascript">
$('#vserver').bind('contextmenu',function(e) { showServerContextMenu(e, this); return false; } );

$("#channelcontextmenu").bind('mouseleave',function() { hideChannelContextMenu(); } );
//document.body.appendChild(document.getElementById("channelcontextmenu"));
$("#vservercontextmenu").bind('mouseleave',function() { hideServerContextMenu(); } );
//document.body.appendChild(document.getElementById("vservercontextmenu"));
$("#clientcontextmenu").bind('mouseleave',function() { hideClientContextMenu(); } );
//document.body.appendChild(document.getElementById("clientcontextmenu"));

for(chn in channels) {
	chn = channels[chn];
	var chndiv = document.createElement("div");
	chndiv.setAttribute("id","channel"+chn["cid"]);
	chndiv.setAttribute("class","treeitem");
	var newContent = '<a href="" title="" cid="'+chn["cid"]+'" id="chn'+chn["cid"]+'" onclick="javascript:selectTreeItem(this);showChannelInfo(\''+chn["cid"]+'\');return false;">'+
		'<div id="channel'+chn["cid"]+'name" class="itemtitle"><table style="width:100%"><tr><td>'+
		'<img src="{$imgsrc}/channel_green.png" /> '+chn["name"]+' </td><td align="right">'+
		'<img src="ajax_request.php?mod=ts3admin&amp;file=image.ts3.ajax&amp;type=channel&amp;cid='+chn["cid"]+'&amp;sid={$sid}&amp;vsid={$vsid};resize" border="0" /> ';
	if (chn.homechn=="1")
		newContent += '<img src="{$imgsrc}/default.png" border="0" /> ';
	if (chn.haspw=="1")
		newContent += '<img src="{$imgsrc}/register.png" border="0" /> ';
	if (chn.codec=="3")
		newContent += '<img src="{$imgsrc}/music.png" border="0" /> ';
		
	if (chn.icon!="")
		newContent += '<img src="{$imgsrc}/groupicons/group_'+chn.icon+'.png" border="0" /> ';
	
	newContent += '</td></tr></table></div></a><div id="channel'+chn["cid"]+'childs" class="itemchilds"></div>';
	chndiv.innerHTML = newContent;
	if(parseInt(chn["pid"])==0) {
		document.getElementById("serverrootchilds").appendChild(chndiv);
	} else {
		document.getElementById("channel"+chn["pid"]+"childs").appendChild(chndiv);
	}
	$('#chn'+chn["cid"]).bind('contextmenu',function(e) { showChannelContextMenu(e, this.getAttribute("cid"), this); return false; } );
	
	for (clt in chn["clients"]) {
		clt = chn["clients"][clt];
		var cltdiv = document.createElement("div");
		cltdiv.setAttribute("id","client"+clt["cid"]);
		cltdiv.setAttribute("class","client");
		
		// player icon
		var cltIcon = '<img src="{$imgsrc}/player_off.png" />';
		if(clt["inmute"]=="1")
			cltIcon = '<img src="{$imgsrc}/input_muted.png" />';
		if(clt["outmute"]=="1")
			cltIcon = '<img src="{$imgsrc}/output_muted.png" />';
		
		// group icons
		var cltgroupicons = '';
		if(clt["priospeaker"]=="1") 
			cltgroupicons += '<img src="{$imgsrc}/capture.png" /> ';
		if(clt["channelcommander"]=="1") 
			cltgroupicons += '<img src="{$imgsrc}/channel_commander.png" /> ';			
		if(channelgroups[clt["channelgroup"]]["iconid"]!='undefined' && channelgroups[clt["channelgroup"]]["iconid"]!="0")
			cltgroupicons += '<img src="{$imgsrc}/groupicons/group_'+channelgroups[clt["channelgroup"]]["iconid"]+'.png" /> ';
		for(sg in clt["servergroups"]) {
			if(servergroups[clt["servergroups"][sg]]["iconid"]!='undefined' && servergroups[clt["servergroups"][sg]]["iconid"]!="0")
				cltgroupicons += '<img src="{$imgsrc}/groupicons/group_'+servergroups[clt["servergroups"][sg]]["iconid"]+'.png" /> ';
		}
		
		cltdiv.innerHTML = '<a href="" title="" cid="'+clt["cid"]+'" id="clt'+clt["dbid"]+'" onclick="javascript:selectTreeItem(this);showClientInfo(\''+
									clt["cid"]+'\');return false;"><div id="client'+clt["cid"]+
				'name" class="itemtitle"><table style="width:100%"><tr><td>'+cltIcon+' '+clt["nick"]+
				'</td><td align="right">'+cltgroupicons+'</td></tr></table></div></a>';
		
		document.getElementById('channel'+chn["cid"]+'childs').appendChild(cltdiv);
		$('#clt'+clt["dbid"]).bind('contextmenu',function(e) { showClientContextMenu(e, this.getAttribute("cid"), this); return false; } );
	}
}

</script>