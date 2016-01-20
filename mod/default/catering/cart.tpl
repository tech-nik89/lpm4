<div class="headline">{$lang.cart}</div>

<script type="text/javascript">
	{if $cart.totalPrice > 0}
		var totalPrice = {$cart.price};
	{else}
		var totalPrice = 0;
	{/if}

	function updateQuantity(index) {
		var quantity = $("#cart_product_"+index).val();
		$("#mycart").load("ajax_request.php?mod=catering&file=update.cart&index="+index+"&quantity="+quantity);
	}
	
	function deleteFromCart(index) {
		$("#mycart").load("ajax_request.php?mod=catering&file=update.cart&index="+index+"&quantity=0");
	}
	
	function submitOrder() {
		$("#mycart").load("ajax_request.php?mod=catering&file=submit.order");
	}
</script>

{if !empty($msg)}
	<p>{$msg}</p>
{/if}

{if !empty($cart) && count($cart.products) > 0}
	<form action="" method="POST">
		<table style="width:100%; border-collapse:collapse;">
			<tr>
				<th>{$lang.product}</th>
				<th>{$lang.ingredients}</th>
				<th>{$lang.price}</th>
				<th>{$lang.amount}</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
			{foreach from=$cart.products item=product name=cartfor}
				<tr {cycle values=',class="highlight_row"'}>
					<td style="padding:3px;">
						{$product.name}
					</td>
					<td>
						{if !empty($product.ingredients)} 
							{section name=i loop=$product.ingredients}
								{$product.ingredients[i].name}{if $smarty.section.i.index < $product.ingredients|@Count-1},{/if}
							{/section}
						{else}
							&nbsp;
						{/if}
					</td>
					<td>
						{({$product.price} / 100)|number_format:2:",":"."} &euro;
					</td>
					<td>
						<input type="text" size="10" value="{$product.quantity}" id="cart_product_{$smarty.foreach.cartfor.index}" onChange="updateQuantity({$smarty.foreach.cartfor.index})">
					</td>
					<td>
						{({$product.quantityprice} / 100)|number_format:2:",":"."} &euro;
					</td>
					<td>
						<div onClick="deleteFromCart({$smarty.foreach.cartfor.index})">X</div>
					</td>
				</tr>
			{/foreach}
			<tr>
				<td colspan="4">
					&nbsp;
				</td>
				<td>
					{({$cart.price} / 100)|number_format:2:",":"."} &euro;
				</td>
				<td> 
					&nbsp; 
				</td>
			</tr>
			<tr>
			<td colspan ="6">
				<input type="button" value="{$lang.order_submit}" onClick="submitOrder()" />
			</td>
		</table>
	</form>
{else}
	<p>{$lang.cart_empty}</p>
{/if}