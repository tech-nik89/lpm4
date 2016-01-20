<div class="headline">{$lang.edit}</div>
<form action="" method="post">
	<input type="hidden" name="ingredientid" value="{$ingredient.ingredientid}" />
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<td>{$lang.name}</td>
			<td><input type="text" name="NameTextBox" value="{$ingredient.name}" /></td>
		</tr>
		<tr>
			<td>{$lang.price}</td>
			<td><input type="text" name="PriceTextBox" value="{$ingredient.price}" style="width:60px" /> Cent</td>
		</tr>
		<tr>
			<td>{$lang.description}</td>
			<td><textarea name="DescriptionTextArea" style="width:100%;">{$ingredient.description}</textarea></td>
		</tr>
		<tr>
			<td>{$lang.available}</td>
			<td><input type="checkbox" name="AvailableCheckBox" value="1"{if $ingredient.available == 1} checked="checked"{/if} /></td>
		</tr>
	</table>
	<p align="right">
		<input type="submit" name="DeleteIngredientSubmitButton" value="{$lang.delete}" />
		<input type="submit" name="EditIngredientSubmitButton" value="{$lang.save}" />
	</p>
</form>