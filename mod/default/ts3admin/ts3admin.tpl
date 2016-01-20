<div style="position:relative;">
    <div class="headline">{$lang.teamspeak3} <pre>[Lib: {$ts3lib_version}]</pre></div>
    <script type="text/javascript">	
	var loading = "{$lang.loading}";
	
	
	<!--{literal}	
	function trim (zeichenkette) {
	  	return zeichenkette.replace (/^\s+/, '').replace (/\s+$/, '');
	}
	
	String.prototype.startsWith = function(str) 
	{return (this.match("^"+str)==str)}
	
    function selectServer(id) {
		id=parseInt(id);
		var lastServer = parseInt(getCookieValue("lastSelectedTS3ServerID",-1));
		if (lastServer!=id) {
			delete_cookie("lastSelectedVServerID");
		}
		document.cookie = "lastSelectedTS3ServerID="+id+";";
		document.getElementById("ts3serverselector").value=id;
        document.getElementById("ts3adminContent").innerHTML=loading;
		$("#ts3adminContent").load("ajax_request.php?mod=ts3admin&file=chooseVServer.ajax&sid="+id);
    }
    {/literal}-->
    </script>


    {if $server=="none"}
        <div class="notification">{$lang.noservers}</div>
    {else}
        <div style="position:absolute; width:100%; text-align:right; top:0px;" >{$lang.server}: 
        <select id="ts3serverselector" onchange="javascript: selectServer(this.value); return false;" style="width:150px;">
        {section name=i loop=$server}
            <option value="{$server[i].ID}">{$server[i].name} - {$server[i].address}</option>
        {/section}
        </select></div>
        
        <div id="ts3adminContent">
        </div>
        
        
        <script type="text/javascript"> 
		var serverid = {$server[0].ID};
		var last = getCookieValue("lastSelectedTS3ServerID",serverid);
		{section name=i loop=$server}
            if({$server[i].ID}==last)
				serverid=last;
        {/section}
		<!--{literal}
            selectServer(serverid);
			
			function getCookieValue(name, defaultValue) {
				if (document.cookie) {
					var cookies = document.cookie.split(";");
					for (cookie in cookies) {
						if(!isNaN(parseInt(cookie))) {
							cookie = cookies[cookie];
							var eqpos = cookie.indexOf("=");
							if(name==trim(cookie.substr(0,eqpos))) {
								return cookie.substr(eqpos+1);
							}
						}
					}
				}
				return defaultValue;
			}
			
			function delete_cookie ( cookie_name )
			{
			  var cookie_date = new Date ( );  // current date & time
			  cookie_date.setTime ( cookie_date.getTime() - 1 );
			  document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
			}
		{/literal}-->
        </script>
    {/if}
</div>