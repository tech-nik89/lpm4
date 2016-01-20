<div class="headline">{$lang.products}</div>
<div align="right">
	<a href="{$url.AddProduct}" class="FancyboxAnchor">{$lang.add}</a>
</div>
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th>{$lang.name}</th>
		<th>{$lang.price}</th>
		<th>&nbsp;</th>
	</tr>
	{foreach from=$products item=product}
		<tr{cycle values=', class="highlight_row"'}>
			<td>{$product.name}</td>
			<td width="100">{($product.price / 100)|number_format:2:",":"."} &euro;</td>
			<td align="right" width="100"><a href="{$product.url}" class="FancyboxAnchor">{$lang.edit}</a></td>
		</tr>
	{/foreach}
</table>
<script type="text/javascript" language="javascript">
	$(".FancyboxAnchor").fancybox();
</script>