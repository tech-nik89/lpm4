{if $askdelete}
<script type="text/javascript">
	function deletechn() {
		$("#askdelete").load("ajax_request.php?mod=ts3admin&file=ChannelOption.ajax&delete=1&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cid}"));
	}
</script>
<div id="askdelete">
	<table style="width:300px;">        <tr>
            <td colspan="3">{$lang.askdeletechannel}</td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: deletechn(); return false;"/></td>
            <td width="100%"></td>
            <td align="right"><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
        </tr>
    </table>
</div>
{else if $delete}
<center>
    {$lang.deletedchannel}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>

{else if $askmove}
<script type="text/javascript">
	function channelmove() {
		$("#askmove").load("ajax_request.php?mod=ts3admin&file=ChannelOption.ajax&move=1&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cid}")+"&chn="+
			document.getElementById("schn").value);
	}
	
	var chns = new Array();
	{foreach from=$channels item=chn}
	chns["{$chn.cid}"] = new Array();
	chns["{$chn.cid}"]["cid"] = "{$chn.cid}";
	chns["{$chn.cid}"]["pid"] = "{$chn.pid}";
	chns["{$chn.cid}"]["name"] = "{$chn.channel_name}";
	chns["{$chn.cid}"]["level"] = 1;
	chns["{$chn.cid}"]["disabled"] = false;
	{/foreach}
</script>
<div id="askmove">
	<table style="width:300px;">
        <tr>
        	<td colspan="3">
            	{$lang.channel}: <br/>
                <select id="schn" size="{$channelcount+1}" style="width:100%">
                <option value="0" {if "{$channelid}"=="0"}selected="1"{/if}>{$virtualserver_name}</option>
                </select>
                
                <script type="text/javascript">
				for(chn in chns) {
					chn = chns[chn];
					
					var opt = document.createElement("Option");
					opt.setAttribute("value",chn["cid"]);
					if(chn["cid"]=="{$channelid}") {
						opt.setAttribute("selected","1");
					}
					if(chn["cid"]=="{$cid}") {
						opt.setAttribute("disabled","1");
						chn["disabled"] = true;
					}
					
					var name = chn["name"];
					if(chn["pid"]!=0) {
						chn["level"] = chns[chn["pid"]]["level"]+1;
						if(chns[chn["pid"]]["disabled"]) {
							opt.setAttribute("disabled","1");
							chn["disabled"] = true;
						}
					}
					for(var i=0;i<chn["level"];i++) {
						name = "&nbsp;&nbsp;&nbsp;"+name;	
					}
					opt.innerHTML=name;
					document.getElementById("schn").appendChild(opt);;
				}
				</script>
            </td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.ok}" style="width:50px;" onclick="javascript: channelmove(); return false;"/></td>
            <td width="100%" colspan="2"></td>
        </tr>
    </table>
</div>
{else if $move}
<center>
    {$lang.channelmoved}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>

{else if $edit}
<center>
    {$lang.channeledited}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>

{else if $create}
<center>
    {$lang.channelcreated}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>


{/if}