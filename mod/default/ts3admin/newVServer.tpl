<script type="text/javascript">
	var newvserverfailed = "{$lang.newts3_failed}";
	
	<!--{literal}
	function saveNewVServer() {
		var name = escape(document.getElementById("vservername").value);
		var passw = escape(document.getElementById("vserverpw").value);
		var maxclients = escape(document.getElementById("vservermaxclients").value);
		var autostart = document.getElementById("vserverautostart").checked;
		if(autostart){autostart=1;}else{autostart=0;}
		
		if(name=="" || maxclients=="") {
			document.getElementById("newVServerError").innerHTML=newvserverfailed;
			return;	
		}
		
		document.getElementById("newvservercontent").innerHTML=loading;
		
		$("#newvservercontent").load("ajax_request.php?mod=ts3admin&file=newVServer.ajax&sid="+sid+"&save=1&name="+name+"&passw="+passw+"&maxclients="+maxclients+"&autostart="+autostart);
	}
	{/literal}-->
</script>

{if $ajaxcallback}
	<p>{$lang.newts3_successfully}</p>
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
			selectVServer({$newvserver.sid});
			self.location.reload();
		}
    </script>
{else}
    <div id="newvservercontent">
        <table style="width:100%;">
            <tr>
                <td>{$lang.servername}</td>
                <td><input type="text" id="vservername" style="width:100%" /></td>
            </tr>
            <tr>
                <td>{$lang.password}</td>
                <td><input type="password" id="vserverpw" style="width:100%" /></td>
            </tr>
            <tr>
                <td>{$lang.maxclients}</td>
                <td><input type="text" id="vservermaxclients" style="width:100%" value="32"/></td>
            </tr>
            <tr>
                <td>{$lang.autostart}</td>
                <td><input type="checkbox" id="vserverautostart" checked/></td>
            </tr>
        </table>
        <div class="" id="newVServerError"></div>
        <p></p>
        <input type="submit" value="{$lang.save}" onclick="javascript:saveNewVServer(); return false;"/>
    </div>
{/if}