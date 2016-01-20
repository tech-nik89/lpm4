{if !empty($products)}
	<table style="width:100%; border-collapse:collapse;">
		{foreach from=$products item=product}
			<tr {cycle values=',class="highlight_row"'} id="product_{$product.productid}" onclick="loadIngredients({$product.productid});">
				<td style="padding:3px;">
					<a href="javascript:void(0);">{$product.name}</a>
				</td>
				<td width="60" align="right">
					{({$product.price} / 100)|number_format:2:",":"."} &euro;
				</td>
			</tr>
		{/foreach}
	</table>
{/if}