<div class="headline">{$lang.editpictures}</div>

<form action="" method="post">

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<th>{$lang.remove}</th>
			<th>{$lang.folder}</th>
		</tr>
		
		{section name=i loop=$pictures}
			<tr{cycle values=', class="highlight_row"'}>
				<td width="20"><input type="checkbox" name="remove_{$pictures[i].imageid}" value="1" /></td>
				<td><a href="{$pictures[i].url}">{$pictures[i].folder}</a></td>
			</tr>
		{/section}
		
	</table>

	<p><input type="submit" name="remove" value="{$lang.remove}" /></p>

</form>