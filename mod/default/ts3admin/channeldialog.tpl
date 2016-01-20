<script type="text/javascript">
	var action = {if $edit}"edit"{else if $create}"create"{else if $createsub}"createsub"{/if};
	
	var old_channel_name = "{$channel_name}";
	var old_channel_password = "{$channel_password}";
	var old_channel_topic = "{$channel_topic}";
	var old_channel_description = "{$channel_description}";
	var old_channel_phonetic = "{$channel_phonetic}";
	var old_channel_codec = "{$channel_codec}";
	var old_channel_quality = "{$channel_quality}";
	var old_channel_delay = "{$channel_delay}";
	var old_channel_semi = "{$channel_semi}";
	var old_channel_perm = "{$channel_perm}";
	var old_channel_mcunlimited = "{$channel_mcunlimited}";
	var old_channel_maxclients = "{$channel_maxclients}";
	var old_channel_default = "{$channel_default}";
	var old_channel_unencryptvoice = "{$channel_unencryptvoice}";
	var old_channel_talkpower = "{$channel_talkpower}";
	var old_mcintcunlimited = "{$mcintcunlimited}";
	var old_mcinctinherit = "{$mcinctinherit}";
	var old_channel_maxclientsintree = "{$channel_maxclientsintree}";
	var old_channel_order = "{$channel_order}";
	
	function saveChannelSettings() {
		var channel_name = document.getElementById("name").value;
		var channel_password = document.getElementById("password").value;
		var channel_topic = document.getElementById("topic").value;
		var channel_description = document.getElementById("description").value;
		var channel_phonetic = document.getElementById("phname").value;
		var channel_codec = document.getElementById("codec").value;
		var channel_quality = document.getElementById("quality").value;
		var channel_delay = document.getElementById("delay").value;
		var channel_semi = document.getElementById("typesemi").checked;
		var channel_perm = document.getElementById("typeperm").checked;
		var channel_mcunlimited = document.getElementById("mcunlimited").checked;
		var channel_maxclients = document.getElementById("mclimited").value;
		var channel_default = document.getElementById("defaultchannel").checked;
		var channel_unencryptvoice = !document.getElementById("encryptvoice").checked;
		var channel_talkpower = document.getElementById("talkpower").value;
		var mcintcunlimited = document.getElementById("mcintcunlimited").checked;
		var mcinctinherit = document.getElementById("mcinctinherit").checked;
		var channel_maxclientsintree = document.getElementById("mcinctlimit").value;
		var channel_order = document.getElementById("parent").value;
		
		var addurl = "&"+action+"=1";
		if(old_channel_name!=channel_name) addurl += "&n="+encodeURIComponent(channel_name); 
		if(old_channel_password!=channel_password) addurl += "&pw="+encodeURIComponent(channel_password); 
		if(old_channel_topic!=channel_topic) addurl += "&t="+encodeURIComponent(channel_topic); 
		if(old_channel_description!=channel_description) addurl += "&d="+encodeURIComponent(channel_description); 
		if(old_channel_phonetic!=channel_phonetic) addurl += "&ph="+encodeURIComponent(channel_phonetic); 
		if(old_channel_codec!=channel_codec) addurl += "&c="+encodeURIComponent(channel_codec); 
		if(old_channel_quality!=channel_quality) addurl += "&q="+encodeURIComponent(channel_quality); 
		if(old_channel_delay!=channel_delay) addurl += "&dl="+encodeURIComponent(channel_delay); 
		if(old_channel_semi!=channel_semi) addurl += "&s="+encodeURIComponent(channel_semi); 
		if(old_channel_perm!=channel_perm) addurl += "&p="+encodeURIComponent(channel_perm); 
		if(old_channel_mcunlimited!=channel_mcunlimited) addurl += "&mcu="+encodeURIComponent(channel_mcunlimited); 
		if(old_channel_maxclients!=channel_maxclients) addurl += "&mc="+encodeURIComponent(channel_maxclients); 
		if(old_channel_default!=channel_default) addurl += "&df="+encodeURIComponent(channel_default); 
		if(old_channel_unencryptvoice!=channel_unencryptvoice) addurl += "&uc="+encodeURIComponent(channel_unencryptvoice); 
		if(old_channel_talkpower!=channel_talkpower) addurl += "&tp="+encodeURIComponent(channel_talkpower); 
		if(old_mcintcunlimited!=mcintcunlimited) addurl += "&mctu="+encodeURIComponent(mcintcunlimited); 
		if(old_mcinctinherit!=mcinctinherit) addurl += "&mcti="+encodeURIComponent(mcinctinherit); 
		if(old_channel_maxclientsintree!=channel_maxclientsintree) addurl += "&mct="+encodeURIComponent(channel_maxclientsintree); 
		if(old_channel_order!=channel_order) addurl += "&o="+encodeURIComponent(channel_order); 
		alert(addurl);
		$("#channeleditcontent").load("ajax_request.php?mod=ts3admin&file=ChannelOption.ajax&sid="+sid+"&vsid="+vsid+"&cid="+encodeURIComponent({$cid})+addurl);
	}
	
	function resetDefaultChannel(checked) {
		if(checked) {
			document.getElementById("mcinctlimited").setAttribute("disabled","true");
			document.getElementById("mcintcunlimited").setAttribute("disabled","true");
			document.getElementById("mcinctinherit").setAttribute("disabled","true");
			document.getElementById("mcunlimited").setAttribute("disabled","true");
			document.getElementById("mclimited").setAttribute("disabled","true");
			document.getElementById("mclimit").setAttribute("disabled","true");
			document.getElementById("typetemp").setAttribute("disabled","true");
			document.getElementById("typesemi").setAttribute("disabled","true");
			document.getElementById("typeperm").setAttribute("disabled","true");
			document.getElementById("mcinctlimit").setAttribute("disabled","true");
		}else{
			document.getElementById("mcinctlimited").removeAttribute("disabled");
			document.getElementById("mcintcunlimited").removeAttribute("disabled");
			document.getElementById("mcinctinherit").removeAttribute("disabled");
			document.getElementById("mcunlimited").removeAttribute("disabled");
			document.getElementById("mclimited").removeAttribute("disabled");
			document.getElementById("mclimit").removeAttribute("disabled");
			document.getElementById("typetemp").removeAttribute("disabled");
			document.getElementById("typesemi").removeAttribute("disabled");
			document.getElementById("typeperm").removeAttribute("disabled");
			document.getElementById("mcinctlimit").removeAttribute("disabled");
		}
	}    
	
</script>
<div id="channeleditcontent">
<table width="700">
    <tr>
    	<td style="border-right:1px solid darkgrey;">
        
        	<table style="width:100%">
            	<tr>
                	<td>{$lang.name}: </td>
                    <td colspan="3"><input type="text" id="name" value="{$channel_name}" style="width:100%;"/></td>
               	</tr>
                <tr>
                	<td>{$lang.password}: </td>
                    <td colspan="3"><input type="password" id="password" value="{if $channel_fpassword}{$channel_password}{/if}" style="width:100%;"/></td>
               	</tr>
                <tr>
                	<td>{$lang.topic}: </td>
                    <td colspan="3"><input type="text" id="topic" value="{$channel_topic}" style="width:100%;"/></td>
               	</tr>
                <tr>
                	<td>{$lang.description}: </td>
                    <td colspan="3"><textarea id="description" style="width:100%;" cols="4">{$channel_description}</textarea></td>
               	</tr>
                <tr>
                	<td>{$lang.phoneticname}: </td>
                    <td><input type="text" id="phname" value="{$channel_phonetic}" style="width:100%;"/></td>
                    <td>{$lang.icon}: </td>
                    <td>
                    	{if $channel_icon<=600}
                    	<img src="{$imgsrc}/groupicons/group_{$channel_icon}.png" border="0" />
                        {else}
                        <img src="ajax_request.php?mod=ts3admin&amp;file=image.ts3.ajax&amp;type=channel&amp;cid={$cid}&amp;sid={$sid}&amp;vsid={$vsid};resize" border="0" />
                        {/if}
                    </td>
               	</tr>
            </table>
        	<table style="width:100%">
            	<tr><th colspan="3">{$lang.codec} ({$lang.expert})</th></tr>
        		<tr>
        			<td>{$lang.codec}</td>
                    <td><select id="codec" style="width:100%;">
                    	<option value="0" {if $channel_codec==0}selected{/if}>{$lang.codec0}</option>
                    	<option value="1" {if $channel_codec==1}selected{/if}>{$lang.codec1}</option>
                        <option value="2" {if $channel_codec==2}selected{/if}>{$lang.codec2}</option>
                        <option value="3" {if $channel_codec==3}selected{/if}>{$lang.codec3}</option>
                    </select></td>
        		</tr>
                <tr>
        			<td>{$lang.quality}</td>
                    <td><select id="quality" style="width:100%;">
                    	<option value="-1" disabled>{$lang.lowquality}</option>
                    	<option value="0" {if $channel_quality==0}selected{/if}>0 (2.73 KiB/s)</option>
                        <option value="1" {if $channel_quality==1}selected{/if}>1 (3.13 KiB/s)</option>
                        <option value="2" {if $channel_quality==2}selected{/if}>2 (3.37 KiB/s)</option>
                        <option value="3" {if $channel_quality==3}selected{/if}>3 (3.61 KiB/s)</option>
                        <option value="4" {if $channel_quality==4}selected{/if}>4 (4.00 KiB/s)</option>
                        <option value="5" {if $channel_quality==5}selected{/if}>5 (4.49 KiB/s)</option>
                        <option value="6" {if $channel_quality==6}selected{/if}>6 (4.93 KiB/s)</option>
                        <option value="7" {if $channel_quality==7}selected{/if}>7 (5.32 KiB/s)</option>
                        <option value="8" {if $channel_quality==8}selected{/if}>8 (5.81 KiB/s)</option>
                        <option value="9" {if $channel_quality==9}selected{/if}>9 (6.59 KiB/s)</option>
                        <option value="10" {if $channel_quality==10}selected{/if}>10 (7.57 KiB/s)</option>
                        <option value="-2" disabled>{$lang.highquality}</option>
                    </select></td>
        		</tr>
        		<tr>
        			<td>{$lang.delay}</td>
                    <td><select id="delay" style="width:100%;">
                    	<option value="-1" disabled>{$lang.highdelay}</option>
                        <option value="1" {if $channel_delay==1}selected{/if}>1 (20 ms)</option>
                        <option value="2" {if $channel_delay==2}selected{/if}>2 (40 ms)</option>
                        <option value="3" {if $channel_delay==3}selected{/if}>3 (60 ms)</option>
                        <option value="4" {if $channel_delay==4}selected{/if}>4 (80 ms)</option>
                        <option value="5" {if $channel_delay==5}selected{/if}>5 (100 ms)</option>
                        <option value="6" {if $channel_delay==6}selected{/if}>6 (120 ms)</option>
                        <option value="7" {if $channel_delay==7}selected{/if}>7 (140 ms)</option>
                        <option value="-2" disabled>{$lang.highdelay}</option>
                    </select></td>
        		</tr>
        	</table>
        </td>
        <td style="border-right:1px solid darkgrey; vertical-align:top; width:100px;">
        	<table>
            	<tr><th>{$lang.channeltype}</th></tr>
                <tr>
                	<td>
            			<input type="radio" name="type" id="typetemp" value="0" {if not ($channel_semi or $channel_perm)}checked{/if}/>
                        <label for="typetemp">{$lang.channeltype_temp}</label><br />
                        <input type="radio" name="type" id="typesemi" value="1" {if $channel_semi}checked{/if}/>
                        <label for="typesemi">{$lang.channeltype_semiperm}</label><br />
                        <input type="radio" name="type" id="typeperm" value="2" {if $channel_perm}checked{/if}/>
                        <label for="typeperm">{$lang.channeltype_perm}</label><br />
            		</td>
        		</tr>
                <tr><th>{$lang.maxclients}</th></tr>
                <tr>
                	<td>
                		<input type="radio" name="maxclients" id="mcunlimited" value="0" {if $channel_mcunlimited}checked{/if}/>
                        <label for="mcunlimited">{$lang.unlimited}</label><br />
                        <input type="radio" name="maxclients" id="mclimited" value="1" onchange="javascript:if(this.checked)document.getElementById('mclimit').removeAttribute('disabled');else document.getElementById('mclimit').setAttribute('disabled',true);" {if not $channel_mcunlimited}checked{/if}/>
                        <label for="mclimited">{$lang.limited}</label>
                        <input type="text" id="mclimit"  value="{$channel_maxclients}" {if $channel_mcunlimited}disabled{/if}/>
                	</td>
                </tr>
        	</table>
        </td>
        <td style="vertical-align:top; width:160px">
        	<table>
            	<tr><th>{$lang.other}</th></tr>
                <tr>
                	<td>
                    	<input type="checkbox" id="defaultchannel" {if $channel_default}checked disabled{/if} onchange="javascript: resetDefaultChannel(this.checked);"/>
                        <label for="defaultchannel">{$lang.defaultchannel}</label><br />
	                    <input type="checkbox" id="encryptvoice" {if not $channel_unencryptvoice}checked{/if}/>
                        <label for="encryptvoice">{$lang.encryptvoice}</label><br />
                        {$lang.requiredtalkpower}: <input type="number" id="talkpower" value="{$channel_talkpower}"/><br /> 
                        {$lang.channelparent}: <br />
                        <select id="parent" style="width:100%">
                        {foreach from=$orderchannels item=chn}
                        	<option value="{$chn.cid}" {if $chn["cid"]==$channel_order}selected{/if}>{$chn.name}</option>
                        {/foreach}
                        </select>
                    </td>
                </tr>
                <tr><th>{$lang.maxclientsinchanneltree}</th></tr>
                <tr>
                	<td>
                		<input type="radio" name="mcinchanneltree" id="mcinctinherit" value="0" {if $mcinctinherit}checked{/if}/>
                        <label for="mcinctinherit">{$lang.inherited}</label><br />
                        
                        <input type="radio" name="mcinchanneltree" id="mcintcunlimited" value="1" {if $mcintcunlimited}checked{/if}/>
                        <label for="mcintcunlimited">{$lang.unlimited}</label><br />
                        
                        <input type="radio" name="mcinchanneltree" id="mcinctlimited" value="2" onchange="javascript:if(this.checked)document.getElementById('mcinctlimit').removeAttribute('disabled');else document.getElementById('mcinctlimit').setAttribute('disabled',true);" {if not ($mcintcunlimited or $mcinctinherit)}checked{/if}/>
                        <label for="mcinctlimited">{$lang.limited}</label>
                        <input type="text" id="mcinctlimit" value="{$channel_maxclientsintree}" {if $mcintcunlimited or $mcinctinherit}disabled{/if} />
                	</td>
                </tr>
        	</table>
        </td> 
    </tr>
</table>
<script type="text/javascript">
	resetDefaultChannel({if $channel_default}true{else}false{/if});
</script>

<input type="submit" value="{$lang.save}" onclick="javascript:saveChannelSettings();" />
</div>