<div class="headline">{$lang.edit}</div>
<form action="" method="post">
	<input type="hidden" name="categoryid" value="{$category.categoryid}" />
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<td>{$lang.order}</td>
			<td><input type="text" name="OrderTextBox" value="{$category.rank}" style="width:45px;" /></td>
		</tr>
		<tr>
			<td>{$lang.name}</td>
			<td><input type="text" name="NameTextBox" value="{$category.name}" /></td>
		</tr>
		<tr>
			<td>{$lang.visible}</td>
			<td><input type="checkbox" name="VisibleCheckBox" value="1"{if $category.visible == 1} checked="checked"{/if} /></td>
		</tr>
	</table>
	<p align="right">
		<input type="submit" name="DeleteCategorySubmitButton" value="{$lang.delete}" />
		<input type="submit" name="EditCategorySubmitButton" value="{$lang.save}" />
	</p>
</form>