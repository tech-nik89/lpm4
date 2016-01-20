<div class="headline">{$lang.teamspeak3}</div>
<form action="" method="post">
    
	<div> <input type="submit" name="newserver" value="{$lang.newts3}" onclick="javascript: window.location.href='{$newts3_linkurl}'; return false;"/> </div>
	
	<p></p>
	
	{if $server=="none"}
		<div class="notification">{$lang.noservers}</div>
	{else}
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
        		<th>{$lang.id}</th>
                <th>{$lang.name}</th>
                <th>{$lang.address}</th>
                <th></th>
            </tr>
        	{section name=i loop=$server}
        	<tr class="{cycle values='highlight_row,'}">
        		<td>{$server[i].ID}</td>  
                <td>{$server[i].name}</td>  
                <td>{$server[i].address}</td>     
                <td style="text-align:right;"><input type="submit" name="editserver" value="{$lang.edit}" onclick="javascript: window.location.href='{$server[i].editlink}'; return false;" /> 	
                	<input type="submit" name="editserver" value="{$lang.delete}" onclick="javascript: window.location.href='{$server[i].deletelink}'; return false;" /></td>   	
            </tr>
			{/section}
		</table>
	{/if}

</form>