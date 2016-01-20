<div class="headline">{$lang.board_manage}</div>

<form action="" method="post">

	<table width="100%" border="0" cellspacing="1" cellpadding="5">
	
		<tr>
			<th width="80">{$lang.order}</th>
			<th width="200">{$lang.board}</th>
			<th>{$lang.visible_for}</th>
			<th width="70">{$lang.remove}</th>
		</tr>
		
		<tr bgcolor="#DDDDDD">
			<td>{$lang.add}</td>
			<td><input type="text" name="board_new" value="" /></td>
			<td>
				<select name="assigned_groupid_new">
	                <option value="0">{$lang.all}</option>
					
                    {section name=j loop=$groups}
					<option value="{$groups[j].groupid}">{$groups[j].name}</option>
					{/section}
                    
				</select>
			</td>
			<td>&nbsp;</td>
		</tr>
        <tr>
        	<td>{$lang.description}:</td>
        	<td colspan="3"><input type="text" name="description_new" style="width:100%" /></td>
		</tr>
        <tr>
        	<td colspan="4">&nbsp;</td>
        </tr>
        
		{section name=i loop=$bl}
		
		<tr bgcolor="#DDDDDD">
			<td><input type="text" name="order_{$bl[i].boardid}" value="{$bl[i].order}" style="width:40px;" /></td>
			<td><input type="text" name="board_{$bl[i].boardid}" value="{$bl[i].board}" /></td>
			<td>
				<select name="assigned_groupid_{$bl[i].boardid}">
                	<option value="0">{$lang.all}</option>
                    
					{section name=j loop=$groups}
					<option value="{$groups[j].groupid}"{if $groups[j].groupid == $bl[i].assigned_groupid} selected="selected"{/if}>{$groups[j].name}</option>
					{/section}
                    
				</select>
			</td>
			<td><input type="checkbox" name="remove_{$bl[i].boardid}" value="1" /></td>
		</tr>
		
        <tr>
        	<td>{$lang.description}:</td>
        	<td colspan="3"><input type="text" name="description_{$bl[i].boardid}" value="{$bl[i].description}" style="width:100%" /></td>
		</tr>
        <tr>
        	<td colspan="4">&nbsp;</td>
        </tr>
        
		{/section}
		
	</table>
	
	<p><input type="submit" name="save" value="{$lang.save}" /></p>
	
</form>