<script type="text/javascript">
	<!--{literal}
	function deleteGroup() {
		document.getElementById("deleteGroupContent").innerHTML=loading;
		
		$("#deleteGroupContent").load("ajax_request.php?mod=ts3admin&file=delGroup.ajax&sid="+sid+"&vsid="+vsid+"&delete=1&sgid="+selectedGroupID);
	}
	{/literal}-->
</script>

{if $ajaxcallback}
	<p>{$lang.deletegroup_successfully}</p>
	<input type="submit" value="" id="countdownnVS" onclick="javascript:reloadPage(); return false;" />    
    <script type="text/javascript">
		window.setInterval("countdown();",1000);
		var countdowncount = 10;
		countdown();
		function countdown() {
			document.getElementById("countdownnVS").value="{$lang.refresh} ["+(countdowncount--)+"]";
			if(countdowncount<0) {
				reloadPage();
			}
		}
		
		function reloadPage() {
			self.location.reload();
		}
    </script>
{else}
    <div id="deleteGroupContent">
        <table style="width:300px;">
            <tr>
                <td colspan="3">{$lang.askdeletegroup}</td>
            </tr>
            <tr>
                <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: deleteGroup(); return false;"/></td>
                <td width="100%"></td>
                <td><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
            </tr>
        </table>
    </div>
	<script type="text/javascript">
		var nogroupselected = "{$lang.permselectgroup}";
		<!--{literal}
		if(selectedGroup==undefined) {
			document.getElementById("deleteGroupContent").innerHTML=nogroupselected;
		} 
		{/literal}-->
	</script>
{/if}