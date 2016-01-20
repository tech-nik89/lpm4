

<script type="text/javascript">
	<!--{literal}
	function deleteVServer() {
		document.getElementById("deleteVServerContent").innerHTML=loading;
		
		$("#deleteVServerContent").load("ajax_request.php?mod=ts3admin&file=deleteVServer.ajax&sid="+sid+"&vsid="+vsid+"&delete=1");
	}
	{/literal}-->
</script>

{if $ajaxcallback}
	<p>{$lang.deletets3_successfully}</p>
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
			viewid=1;
			selectVServer({$vsid});
			self.location.reload();
		}
    </script>


{else}
<div id="deleteVServerContent">
    <table style="width:300px;">
        <tr>
            <td colspan="3">{$lang.askdeletevserver}</td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: deleteVServer(); return false;"/></td>
            <td width="100%"></td>
            <td><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
        </tr>
    </table>
</div>
{/if}