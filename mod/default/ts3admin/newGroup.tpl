<script type="text/javascript">
	var newgroupfailed = "{$lang.newgroup_failed}";
	 
	<!--{literal}
	function saveNewGroup() {
		var name = escape(document.getElementById("groupname").value);
		var type = escape(document.getElementById("grouptype").value);
		
		
		if(name=="" || type=="") {
			document.getElementById("newGroupError").innerHTML=newvserverfailed;
			return;	
		}
		
		document.getElementById("newvgroupcontent").innerHTML=loading;
		
		$("#newvgroupcontent").load("ajax_request.php?mod=ts3admin&file=newGroup.ajax&sid="+sid+"&vsid="+vsid+"&save=1&name="+name+"&type="+type);
	}
	{/literal}-->
</script>

{if $ajaxcallback}
	<p>{$lang.newgroup_successfully}</p>
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
    <div id="newvgroupcontent">
        <table style="width:100%;">
            <tr>
                <td>{$lang.name}</td>
                <td><input type="text" id="groupname" style="width:100%" /></td>
            </tr>
            <tr>
                <td>{$lang.type}</td>
                <td>
                	<select id="grouptype">
                		<option value="template">{$lang['type0_template']}</option>
                        <option value="clients">{$lang['type1_clients']}</option>
                        <option value="query">{$lang['type2_query']}</option>
                	</select>
                </td>
            </tr>
        </table>
        <div class="" id="newGroupError"></div>
        <p></p>
        <input type="submit" value="{$lang.save}" onclick="javascript:saveNewGroup(); return false;"/>
    </div>
{/if}