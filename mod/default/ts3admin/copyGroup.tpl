<script type="text/javascript">
	var newgroupfailed = "{$lang.copygroup_failed}";
	
	<!--{literal}
	function copyGroup() {
		var name = escape(document.getElementById("groupname").value);
		var type = escape(document.getElementById("grouptype").value);
		
		
		if(name=="" || type=="") {
			document.getElementById("copyGroupError").innerHTML=newvserverfailed;
			return;	
		}
		
		document.getElementById("copygroupcontent").innerHTML=loading;
		
		$("#copygroupcontent").load("ajax_request.php?mod=ts3admin&file=copyGroup.ajax&sid="+sid+"&vsid="+vsid+"&save=1&name="+name+"&type="+type+"&ssgid="+selectedGroupID);
	}
	{/literal}-->
</script>

{if $ajaxcallback}
	<p>{$lang.copygroup_successfully}</p>
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
    <div id="copygroupcontent">
        <table style="width:100%;">
            <tr>
            	<td colspan="2">
                	<div id="copyfromgrouptext"></div>
                </td>
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
        <div class="" id="copyGroupError"></div>
        <p></p>
        <input type="submit" value="{$lang.save}" onclick="javascript:copyGroup(); return false;"/>
    </div>
    <script type="text/javascript">
		var nogroupselected = "{$lang.permselectgroup}";
		var copyfromgrouptext = "{$lang.copyfromgrouptext}";
		<!--{literal}
		if(selectedGroup==undefined) {
			document.getElementById("copygroupcontent").innerHTML=nogroupselected;
		} else {
			document.getElementById("copyfromgrouptext").innerHTML=copyfromgrouptext+": "+selectedGroupName;
		}
		{/literal}-->
	</script>
{/if}