<script type="text/javascript">
function addGroup() {
	var group = document.getElementById("groupselector").value;
	$('#ClientGroupContent').load("ajax_request.php?mod=ts3admin&file=ClientGroup.ajax&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cuid}")+"&group="+group+"&add=1");
}

function removeGroup() {
	$('#ClientGroupContent').load("ajax_request.php?mod=ts3admin&file=ClientGroup.ajax&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cuid}")+"&group={$groupid}&rem=1");
}
</script>


{if $ajaxcallback}
	{if $groupaction eq "rem"}
       <p>{$lang.groupremoved}</p>
        <center><input type="submit" value="{$lang.ok}" onclick="javascript: $.fancybox.close(); return false;" /></center>
        <script type="text/javascript">
			clientgroups["{$cuid}"]["{$groupid}"] = -1;
            refreshCurrentSelectedClient();
        </script>
    {else}
        <p>{$lang.groupadded}</p>
        <center><input type="submit" value="{$lang.ok}" onclick="javascript: $.fancybox.close(); return false;" /></center>
        <script type="text/javascript">
			clientgroups["{$cuid}"]["{$groupid}"] = "{$groupid}";
            refreshCurrentSelectedClient();
        </script>
   	{/if}
{else}
	<div id="ClientGroupContent">
    {if $removegroup}
        <table style="width:300px;">
            <tr>
                <td colspan="3">{$lang.askremclientgroup}</td>
            </tr>
            <tr>
                <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: removeGroup(); return false;"/></td>
                <td width="100%"></td>
                <td><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
            </tr>
        </table>   
    {else}
    	{$lang.client}: {$client.client_nickname}<br />
        <p></p>
    	<select id="groupselector" style="width:200px;"></select><br />
        <input type="submit" value="{$lang.addgroup}" onclick="javascript: addGroup(); return false;"/>
    	   
        <script type="text/javascript">
		var sele = document.getElementById("groupselector");
		for(group in addableGroups) {
			group = addableGroups[group];
			var opt = document.createElement("option");
			opt.setAttribute("value",group["id"]);
			opt.innerHTML =group["id"]+" - "+group["name"];
			if((parseInt(clientgroups["{$cuid}"]["clienttype"])+1)==group["type"]) {
				sele.appendChild(opt);
			}
		}
		</script>
   	{/if}
    </div>
{/if}