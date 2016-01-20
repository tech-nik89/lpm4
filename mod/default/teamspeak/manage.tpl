{if $action == ""}
	
	<div class="headline">{$lang.teamspeak_manage}</div>
	
	<form action="" method="post">

	<table width="100%" border="0">
	
		<tr>
			<th>{$lang.order}</th>
			<th>{$lang.address}</th>
			<th>{$lang.tcp}</th>
			<th>{$lang.udp}</th>
			<th>{$lang.password}</th>
			<th>{$lang.remove}</th>
		</tr>
		
		{section name=m loop=$m}
		
		<tr class="{cycle values=',highlight_row'}">
			<td><input type="text" name="order_{$m[m].ID}" value="{$m[m].order}" style="width:40px;" /></td>
			<td><input type="text" name="address_{$m[m].ID}" value="{$m[m].address}" /></td>
			<td><input type="text" name="tcp_{$m[m].ID}" value="{$m[m].tcp}" /></td>
			<td><input type="text" name="udp_{$m[m].ID}" value="{$m[m].udp}" /></td>
			<td><input type="text" name="pw_{$m[m].ID}" value="{$m[m].pw}" /></td>
			<td>
				<input type="checkbox" name="delete_{$m[m].ID}" value="1" />
			</td>
		</tr>
		
		{/section}
		
		<tr class="{cycle values=',highlight_row'}">
			<td>{$lang.add}</td>
			<td><input type="text" name="address_new" value="" /></td>
			<td><input type="text" name="tcp_new" value="51234" /></td>
			<td><input type="text" name="udp_new" value="8767" /></td>
			<td><input type="text" name="pw_new" value="" /></td>
			<td>&nbsp;</td>
		</tr>
		
	</table>
	
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
	
	</form>
	
{/if}
