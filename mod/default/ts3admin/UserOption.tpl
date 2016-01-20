{if $asksendmsg}
<script type="text/javascript">
	function sendMsg() {
		$("#askmsgContent").load("ajax_request.php?mod=ts3admin&file=UserOption.ajax&sendmsg=1&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cuid}")+"&msg="+
			encodeURIComponent(document.getElementById("msg").value));
	}
</script>
<div id="askmsgContent">
	{$lang.message}: <br />
    <textarea id="msg" rows="3" cols="100"></textarea><br/>
	<input type="submit" value="{$lang.send}" onclick="javascript: sendMsg(); return false;" />
</div>
{else if $sendmsg}
<center>
    {$lang.msgsend}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>

{else if $askpoke}
<script type="text/javascript">
	function sendPoke() {
		$("#askpokeContent").load("ajax_request.php?mod=ts3admin&file=UserOption.ajax&poke=1&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cuid}")+"&msg="+
			encodeURIComponent(document.getElementById("msg").value));
	}
</script>
<div id="askpokeContent">
	{$lang.message}: <br />
    <textarea id="msg" rows="3" cols="100"></textarea><br/>
	<input type="submit" value="{$lang.send}" onclick="javascript: sendPoke(); return false;" />
</div>
{else if $poke}
<center>
    {$lang.pokesend}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>



{else if $askcomplaint}
<script type="text/javascript">
	function sendComplaint() {
		$("#askcomplaintContent").load("ajax_request.php?mod=ts3admin&file=UserOption.ajax&complaint=1&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cuid}")+"&msg="+
			encodeURIComponent(document.getElementById("msg").value));
	}
</script>
<div id="askcomplaintContent">
	{$lang.complaint}: <br />
    <textarea id="msg" rows="3" cols="100"></textarea><br/>
	<input type="submit" value="{$lang.send}" onclick="javascript: sendComplaint(); return false;" />
</div>
{else if $complaint}
<center>
    {$lang.complaintsend}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>


{else if $askkickfromchannel}
<script type="text/javascript">
	function kickfromchannel() {
		$("#askkickfromchannel").load("ajax_request.php?mod=ts3admin&file=UserOption.ajax&kickfromchannel=1&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cuid}")+"&msg="+
			encodeURIComponent(document.getElementById("msg").value));
	}
</script>
<div id="askkickfromchannel">
	<table style="width:300px;">        <tr>
            <td colspan="3">{$lang.askkickfromchannel}</td>
        </tr>
        <tr>
        	<td colspan="3">
            	{$lang.reason}: <br/>
                <textarea id="msg" rows="3" style="width:300px"></textarea>
            </td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: kickfromchannel(); return false;"/></td>
            <td width="100%"></td>
            <td align="right"><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
        </tr>
    </table>
</div>
{else if $kickfromchannel}
<center>
    {$lang.kickedfromchannel}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>

{else if $askkickfromserver}
<script type="text/javascript">
	function kickfromserver() {
		$("#askkickfromserver").load("ajax_request.php?mod=ts3admin&file=UserOption.ajax&kickfromserver=1&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cuid}")+"&msg="+
			encodeURIComponent(document.getElementById("msg").value));
	}
</script>
<div id="askkickfromserver">
	<table style="width:300px;">
        <tr>
            <td colspan="3">{$lang.askkickfromserver}</td>
        </tr>
        <tr>
        	<td colspan="3">
            	{$lang.reason}: <br/>
                <textarea id="msg" rows="3" style="width:300px"></textarea>
            </td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: kickfromserver(); return false;"/></td>
            <td width="100%"></td>
            <td align="right"><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
        </tr>
    </table>
</div>
{else if $kickfromserver}
<center>
    {$lang.kickedfromserver}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>

{else if $askban}
<script type="text/javascript">
	function ban() {
		$("#askban").load("ajax_request.php?mod=ts3admin&file=UserOption.ajax&ban=1&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cuid}")+"&msg="+
			encodeURIComponent(document.getElementById("msg").value)+"&duration="+encodeURIComponent(document.getElementById("duration").value));
	}
</script>
<div id="askban">
	<table style="width:300px;">
        <tr>
            <td colspan="3">{$lang.askban}</td>
        </tr>
        <tr>
        	<td colspan="3">
            	{$lang.reason}: <br/>
                <textarea id="msg" rows="3" style="width:300px"></textarea><br/>
                {$lang.duration}: <input type="number" value="0" id="duration" />{$lang.secs} <br/>
                <i>{$lang.tillinfinity}</i>
            </td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: ban(); return false;"/></td>
            <td width="100%"></td>
            <td align="right"><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
        </tr>
    </table>
</div>
{else if $ban}
<center>
    {$lang.baned}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>

{else if $askeditdescription}
<script type="text/javascript">
	function editdescription() {
		$("#askeditdescription").load("ajax_request.php?mod=ts3admin&file=UserOption.ajax&editdescription=1&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cuid}")+"&text="+
			encodeURIComponent(document.getElementById("text").value));
	}
</script>
<div id="askeditdescription">
	<table style="width:300px;">
        <tr>
        	<td colspan="3">
            	{$lang.description}: <br/>
                <textarea id="text" rows="3" style="width:300px">{$description}</textarea><br/>
            </td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.ok}" style="width:50px;" onclick="javascript: editdescription(); return false;"/></td>
            <td width="100%" colspan="2"></td>
        </tr>
    </table>
</div>
{else if $editeddescription}
<center>
    {$lang.editeddescription}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>

{else if $asksetservergroups}
<script type="text/javascript">
	function setservergroups() {
		var vals = "";
		{foreach from=$sgroups item=sg}
		vals+="&sg{$sg.sgid}="+document.getElementById("sg{$sg.sgid}").checked;
		{/foreach}
		$("#asksetservergroups").load("ajax_request.php?mod=ts3admin&file=UserOption.ajax&setservergroups=1&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cuid}")+vals );
	}
</script>
<div id="asksetservergroups">
	<table style="width:300px;">
        <tr>
        	<td>
            	{$lang.servergroups}: <br/>
            </td>
        </tr>
        {foreach from=$sgroups item=sg}
        <tr>
        	<td><input type="checkbox" id="sg{$sg.sgid}"/> {$sg.name} {if $sg.iconid!="0"}<img src="{$imgsrc}/groupicons/group_{$sg.iconid}.png" />{/if}</td>
        </tr>
        {/foreach}
        <tr>
            <td><input type="submit" value="{$lang.ok}" style="width:50px;" onclick="javascript: setservergroups(); return false;"/></td>
        </tr>
    </table>
    <script type="text/javascript">
	{foreach from=$servergroups item=sg}
    document.getElementById("sg{$sg}").checked=true;
	{/foreach}
    </script>
</div>
{else if $setservergroups}
<center>
    {$lang.servergroupsset}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>

{else if $asksetchannelgroup}
<script type="text/javascript">
	function setchannelgroup() {
		var val = "0";
		{foreach from=$cgroups item=cg}
		if(document.getElementById("cg{$cg.cgid}").checked) {
			val = "{$cg.cgid}";	
		}
		{/foreach}
		$("#asksetchannelgroup").load("ajax_request.php?mod=ts3admin&file=UserOption.ajax&setchannelgroup=1&sid={$sid}&vsid={$vsid}&channelid={$channelid}&cid="+
			encodeURIComponent("{$cuid}")+"&cg="+val );
	}
</script>
<div id="asksetchannelgroup">
	<table style="width:300px;">
        <tr>
        	<td>
            	{$lang.channelgroup}: <br/>
            </td>
        </tr>
        {foreach from=$cgroups item=cg}
        <tr>
        	<td>
            	<input type="radio" name="cg" id="cg{$cg.cgid}" {if $cg.cgid==$channelgroup}checked{/if}/> {$cg.name} 
            	{if $cg.iconid!="0"}<img src="{$imgsrc}/groupicons/group_{$cg.iconid}.png" />{/if}
            </td>
        </tr>
        {/foreach}
        <tr>
            <td><input type="submit" value="{$lang.ok}" style="width:50px;" onclick="javascript: setchannelgroup(); return false;"/></td>
        </tr>
    </table>
</div>
{else if $setchannelgroup}
<center>
    {$lang.channelgroupset}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>

{else if $askclientmove}
<script type="text/javascript">
	function clientmove() {
		$("#askclientmove").load("ajax_request.php?mod=ts3admin&file=UserOption.ajax&clientmove=1&sid={$sid}&vsid={$vsid}&cid="+encodeURIComponent("{$cuid}")+"&chn="+
			document.getElementById("schn").value);
	}
	
	var chns = new Array();
	{foreach from=$channels item=chn}
	chns["{$chn.cid}"] = new Array();
	chns["{$chn.cid}"]["cid"] = "{$chn.cid}";
	chns["{$chn.cid}"]["pid"] = "{$chn.pid}";
	chns["{$chn.cid}"]["name"] = "{$chn.channel_name}";
	chns["{$chn.cid}"]["level"] = 0;
	{/foreach}
</script>
<div id="askclientmove">
	<table style="width:300px;">
        <tr>
        	<td colspan="3">
            	{$lang.channel}: <br/>
                <select id="schn" size="{$channelcount}" style="width:100%">
                </select>
                
                <script type="text/javascript">
				for(chn in chns) {
					chn = chns[chn];
					
					var opt = document.createElement("Option");
					opt.setAttribute("value",chn["cid"]);
					if(chn["cid"]=="{$channelid}") {
						opt.setAttribute("selected","1");
					}
					
					var name = chn["name"];
					if(chn["pid"]!=0) {
						chn["level"] = chns[chn["pid"]]["level"]+1;
						for(var i=0;i<chn["level"];i++) {
							name = "&nbsp;&nbsp;&nbsp;"+name;	
						}
					}
					opt.innerHTML=name;
					document.getElementById("schn").appendChild(opt);;
				}
				</script>
            </td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.ok}" style="width:50px;" onclick="javascript: clientmove(); return false;"/></td>
            <td width="100%" colspan="2"></td>
        </tr>
    </table>
</div>
{else if $clientmove}
<center>
    {$lang.clientmoved}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); activateView(-1); return false;" />
</center>




{/if}
