<div class="headline">{$lang.add}</div>
<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<td>{$lang.order}</td>
			<td><input type="text" name="OrderTextBox" value="1" style="width:45px;" /></td>
		</tr>
		<tr>
			<td>{$lang.name}</td>
			<td><input type="text" name="NameTextBox" value="" /></td>
		</tr>
		<tr>
			<td>{$lang.visible}</td>
			<td><input type="checkbox" name="VisibleCheckBox" value="1" checked="checked" /></td>
		</tr>
	</table>
	<p align="right">
		<input type="submit" name="NewCategorySubmitButton" value="{$lang.add}" />
	</p>
</form>