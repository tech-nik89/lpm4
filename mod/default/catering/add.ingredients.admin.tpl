<div class="headline">{$lang.add}</div>
<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<td>{$lang.name}</td>
			<td><input type="text" name="NameTextBox" value="" /></td>
		</tr>
		<tr>
			<td>{$lang.price}</td>
			<td><input type="text" name="PriceTextBox" value="100" style="width:60px" /> Cent</td>
		</tr>
		<tr>
			<td>{$lang.description}</td>
			<td><textarea name="DescriptionTextArea" style="width:100%;"></textarea></td>
		</tr>
		<tr>
			<td>{$lang.available}</td>
			<td><input type="checkbox" name="AvailableCheckBox" value="1" checked="checked" /></td>
		</tr>
	</table>
	<p align="right">
		<input type="submit" name="NewIngredientSubmitButton" value="{$lang.add}" />
	</p>
</form>