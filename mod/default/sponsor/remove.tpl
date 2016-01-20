<div class="headline">{$lang.sponsor_edit}</div>

<form action="" method="post">

	<table width="100%" border="0" cellspacing="1" cellpadding="5">
		
		{section name=i loop=$sl}
			<tr class="{cycle values='highlight_row,'}">
				<td width="20"><input type="checkbox" name="remove_{$sl[i].sponsorid}" id="remove_{$sl[i].sponsorid}" value="1"  /></td>
				<td>
					<label for="remove_{$sl[i].sponsorid}">
						<a href="{$sl[i].url}">{$sl[i].name}</a>
					</label>
				</td>
			</tr>
		{/section}
		
	</table>	
	
	<p><input type="submit" name="remove" value="{$lang.remove}" /></p>

</form>