<div class="headline">{$lang.ingredients}</div>
<div align="right">
	<a href="{$url.AddIngredient}" class="FancyboxAnchor">{$lang.add}</a>
</div>
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th>{$lang.name}</th>
		<th>{$lang.price}</th>
		<th width="50">{$lang.available}</th>
		<th>&nbsp;</th>
	</tr>
	{foreach from=$ingredients item=ingredient}
		<tr{cycle values=', class="highlight_row"'}>
			<td>{$ingredient.name}</td>
			<td>{($ingredient.price / 100)|number_format:2:",":"."} &euro;</td>
			<td>{$ingredient.available}</td>
			<td align="right"><a href="{$ingredient.url}" class="FancyboxAnchor">{$lang.edit}</a></td>
		</tr>
	{/foreach}
</table>
<script type="text/javascript" language="javascript">
	$(".FancyboxAnchor").fancybox();
</script>