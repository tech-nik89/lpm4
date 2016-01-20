<style type="text/css">
td.viewerarea {
	border:1px solid black;
	padding:5px;
	height:400px;
	vertical-align:top;
}

td.detailsarea {
	border:1px solid black;
	padding:5px;
}

td.chatarea {
	border:1px solid black;	
	padding:5px;
	height:180px;
}
</style>

<script type="text/javascript">
	function refreshChannellist() {
		document.getElementById("viewerarea").innerHTML = loading;
		$("#viewerarea").load("ajax_request.php?mod=ts3admin&file=channellist.ajax&sid="+sid+"&vsid="+vsid);
	}
	
	function showServerInfo() {
		document.getElementById("detailsarea").innerHTML = loading;
		$("#detailsarea").load("ajax_request.php?mod=ts3admin&file=serverviewinfo.ajax&sid="+sid+"&vsid="+vsid);
	}
	
	function showChannelInfo(cid) {
		document.getElementById("detailsarea").innerHTML = loading;
		$("#detailsarea").load("ajax_request.php?mod=ts3admin&file=channelviewinfo.ajax&sid="+sid+"&vsid="+vsid+"&cid="+cid);
	}
	
	function showClientInfo(cid) {
		document.getElementById("detailsarea").innerHTML = loading;
		$("#detailsarea").load("ajax_request.php?mod=ts3admin&file=clientviewinfo.ajax&sid="+sid+"&vsid="+vsid+"&cid="+encodeURIComponent(cid));
	}
</script>

<table cellpadding="0" cellspacing="3" width="100%" border="0">
	<colgroup>
    	<col width="50%" >
        <col width="50%" >
    </colgroup>
    <tr>
    	<td colspan="2" align="right">
        	<a href="" onclick="javascript: refreshChannellist(); return false;" style="border:0px;" title="{$lang.refreshview}"> <img src="{$imgsrc}/refresh.png" style="border:0px;" /> </a>
        </td>
    <tr>
		<td class="viewerarea" id="viewerarea" valign="top"></td>
        <td class="detailsarea" id="detailsarea" valign="top"><b>{$lang.selectchannelorclient}</b></td>
	</tr>
</table>
<script type="text/javascript">
	refreshChannellist();
</script>