<div class="headline">{$lang.edit}</div>
<script type="text/javascript" language="javascript">
	function ConfigurableCheckBox_Click() {
		if ($("#ConfigCheckBox").attr('checked')) {
			$("#ConfigDiv").show();
		}
		else {
			$("#ConfigDiv").hide();
		}
	}
	
	function InfiniteAmountCheckBox_Click() {
		if ($("#InfiniteAmountCheckBox").attr('checked')) {
			$("#AmountTextBox").val("-1");
			$("#AmountTextBox").attr('disabled', 'disabled');
		}
		else {
			$("#AmountTextBox").val("0");
			$("#AmountTextBox").removeAttr('disabled');
		}
	}
	
	function SellerTextBox_KeyPressed() {
		var value = $("#SellerTextBox").val();
		if (value.length > 2) {
			$("#SellerSearchResults").load('ajax_request.php?mod=catering&file=seller.find.ajax&find='+value);
		}
		else {
			$("#SellerSearchResults").html('');
		}
	}
	
	function SellerSelected(id) {
		$("#SellerTextBox").val(id);
		$("#SellerSearchResults").html('');
	}
</script>
<form action="" method="post">
	<input type="hidden" name="productid" value="{$product.productid}" />
	<table width="400" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<td>{$lang.name}</td>
			<td><input type="text" name="NameTextBox" value="{$product.name}" style="width:100%;" /></td>
		</tr>
		<tr>
			<td>{$lang.category}</td>
			<td>
				<select name="CategorySelect">
					{foreach from=$categories item=category}
						<option value="{$category.categoryid}"{if $product.categoryid == $category.categoryid} selected="selected"{/if}>{$category.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$lang.description}</td>
			<td><textarea name="DescriptionTextArea" style="width:100%;">{$product.description}</textarea></td>
		</tr>
		<tr>
			<td>{$lang.price}</td>
			<td><input type="text" name="PriceTextBox" value="{$product.price}" style="width:60px" /> Cent</td>
		</tr>
		<tr>
			<td>{$lang.amount}</td>
			<td>
				<input type="text" name="AmountTextBox" id="AmountTextBox" value="{$product.amount}" style="width:60px"{if $product.amount == -1} disabled="disabled"{/if} />
				<label>
					<input type="checkbox" id="InfiniteAmountCheckBox" name="InfiniteAmountCheckBox" value="1" onclick="javascript:InfiniteAmountCheckBox_Click();"{if $product.amount == -1} checked="checked"{/if} />
					{$lang.infinite}
				</label>
			</td>
		</tr>
		<tr>
			<td>{$lang.visible}</td>
			<td><input type="checkbox" name="VisibleCheckBox" value="1"{if $product.visible==1} checked="checked"{/if} /></td>
		</tr>
		<tr>
			<td valign="top">{$lang.seller} (ID)</td>
			<td>
				<div>
					<input type="text" id="SellerTextBox" name="SellerTextBox" value="{$product.sellerid}" style="width:100%;" onkeyup="SellerTextBox_KeyPressed();" />
				</div>
				<div id="SellerSearchResults"></div>
			</td>
		</tr>
		<tr>
			<td>{$lang.configurable}</td>
			<td><input type="checkbox" name="ConfigurableCheckBox" value="1" id="ConfigCheckBox" onclick="javascript:ConfigurableCheckBox_Click();"{if $product.configurable == 1} checked="checked"{/if} /></td>
		</tr>
	</table>
	<div id="ConfigDiv" style="{if $product.configurable == 0}display:none;{/if}">
		<div class="headline">{$lang.optional_ingredients}</div>
		<table width="100%" border="0" cellpadding="5" cellspacing="1">
			{foreach from=$ingredients item=ingredient}
				<tr>
					<td width="40">
						<input type="checkbox" name="Ingredient_{$ingredient.ingredientid}" id="Ingredient_{$ingredient.ingredientid}" value="1"{if $product_ingredients[$ingredient.ingredientid] == 1} checked="checked"{/if} />
					</td>
					<td>
						<label for="Ingredient_{$ingredient.ingredientid}" style="cursor:pointer;">{$ingredient.name}</label>
					</td>
				</tr>
			{/foreach}
		</table>
	</div>
	<p align="right">
		<input type="submit" name="DeleteProductSubmitButton" value="{$lang.delete}" />
		<input type="submit" name="EditProductSubmitButton" value="{$lang.save}" />
	</p>
</form>