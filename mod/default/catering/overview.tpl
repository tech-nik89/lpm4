<link rel="stylesheet" href="mod/default/catering/seller.numblock.css" type="text/css" media="screen" />

<script type="text/javascript">
	$(window).load(function() {
		$("#mycart").load("ajax_request.php?mod=catering&file=load.cart&productid=-1&ingredients=");
	});

	function loadProducts(categoryid) {
		removeCategoryClassSelected();
		$("#category_"+categoryid).addClass('selected');
		$("#selectedcategoryid").val(categoryid);
		$("#selectedproductid").val('0');
		$("#products").load("ajax_request.php?mod=catering&file=load.products&categoryid="+categoryid);
		$("#ingredients").html("&nbsp;");
	}
	
	function removeCategoryClassSelected() {
		$('tr[id^=category_]').removeClass('selected');
	}
	
	function loadIngredients(productid) {
		removeProductClassSelected();
		$("#product_"+productid).toggleClass('selected');
		$("#selectedproductid").val(productid);
		$("#ingredients").load("ajax_request.php?mod=catering&file=load.ingredients&productid="+productid);
	}
	
	function removeProductClassSelected() {
		$('tr[id^=product_]').removeClass('selected');
	}	
	
	function addToCart() {
		var productid = $("#selectedproductid").val();
		
		var ids = new Array();
		
		jQuery.each($(".ingredient"), function() {
			if ($(this).attr('checked')){
				ids.push(this.value);
			}
		});

		$("#mycart").load("ajax_request.php?mod=catering&file=load.cart&productid="+productid+"&ingredients="+ids);
	}
	
</script>
<style type="text/css">
	.selected {
		background-color:#aaaaaa;
	}

</style>

<div class="headline">
	{$lang.catering}
</div>

<form action="" method="POST">
	<table border="0" width="100%" style="padding:0px; margin:0px; border-collapse:collapse; table-layout:fixed;">
		<colgroup>
			<col width="34%" />
			<col width="33%" />
			<col width="33%" />
		</colgroup>
		<tr>
			<th>
				{$lang.categories}
			</th>
			<th>
				{$lang.products}
			</th>
			<th>
				{$lang.ingredients}
			</th>
		</tr>
		<tr>
			<td style="vertical-align:top; ">
				<table style="width:100%; padding:0px; margin:0px;  border-collapse:collapse;" id="categories">
					{foreach from=$categories item=category}
						<tr {cycle values=',class="highlight_row"'} id="category_{$category.categoryid}" onclick="loadProducts({$category.categoryid});">
							<td style="padding:3px;">
								<a href="javascript:void(0);">{$category.name}</a>
							</td>
						</tr>
					{/foreach}
				</table>
			</td>
			<td style="vertical-align:top;" id="products">
				&nbsp;
			</td>
			<td style="vertical-align:top;" id="ingredients">
				&nbsp;
			</td>
		</tr>
		{if $show_cart}
			<tr>
				<td colspan="3">
					<div style="padding:5px;" align="right">
						<input type="hidden" id="selectedcategoryid" name="categoryid" value="0" />
						<input type="hidden" id="selectedproductid" name="productid" value="0" />
						<input type="button" value="{$lang.add_to_cart}" onClick="addToCart()" />
					</div>
				</td>
			</tr>
		{/if}
	</table>
	
	<div id="mycart">
		&nbsp;
	</div>
	
	{if $is.seller}
		<div class="headline">{$lang.seller}</div>
		<div style="padding:10px;">
			<a href="ajax_request.php?mod=catering&file=pay.ajax&mode=bar" class="payURLs" style="padding:10px; border:1px solid #CCC;">{$lang.pay_bar}</a>
			<a href="ajax_request.php?mod=catering&file=pay.ajax&mode=credit" class="payURLs" style="padding:10px; border:1px solid #CCC;">{$lang.pay_credit}</a>
		</div>
		<script type="text/javascript">
			$(".payURLs").fancybox();
		</script>
	{/if}
	
</form>
