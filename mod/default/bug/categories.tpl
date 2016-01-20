<div class="headline">{$lang.categories}</div>

<form action="" method="post">
	<table width="100%" border="0">
		{foreach from=$categories item=category}
			<tr>
				<td><input type="text" name="category_{$category.categoryid}" value="{$category.name}" style="width:100%;" /></td>
				<td align="center"><input type="checkbox" name="delete_{$category.categoryid}" value="1" /></td>
			</tr>
		{/foreach}
		<tr>
			<td><input type="text" name="category_new" value="" style="width:100%;" /></td>
			<td width="35">&nbsp;</td>
		</tr>
	</table>
	<p><input type="submit" name="save" value="{$lang.save}" /></p>
</form>