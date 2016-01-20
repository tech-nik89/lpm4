<div class="headline">{$lang.personal_fields}</div>

<form action="" method="post">

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<th>{$lang.field}</th>
			<th>{$lang.remove} / {$lang.add}</th>
		</tr>
		
		{section name=i loop=$list}
			
				<tr class="{cycle values=',highlight_row'}">
					<td><input type="text" name="value_{$list[i].fieldid}" value="{$list[i].value}" /></td>
					<td>{$lang.remove} <input type="checkbox" name="delete_{$list[i].fieldid}" value="1" /></td>
				</tr>
				
		{/section}
		
		<tr>
			<td><input type="text" name="value_new" value="" /></td>
			<td>{$lang.add}</td>
		</tr>
		
	</table>
	
	<p><input type="submit" name="save" value="{$lang.save}" /></p>
	
</form>