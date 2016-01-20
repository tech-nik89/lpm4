<div class="headline">{$lang.categories}</div>
<div align="right">
	<a href="{$url.AddCategory}" class="FancyboxAnchor">{$lang.add}</a>
</div>
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th width="40">{$lang.order}</th>
		<th>{$lang.visible}</th>
		<th>{$lang.name}</th>
		<th>&nbsp;</th>
	</tr>
	{foreach from=$categories item=category}
		<tr{cycle values=', class="highlight_row"'}>
			<td>{$category.rank}</td>
			<td>{$category.visible}</td>
			<td>{$category.name}</td>
			<td align="right"><a href="{$category.url}" class="FancyboxAnchor">{$lang.edit}</a></td>
		</tr>
	{/foreach}
</table>
<script type="text/javascript" language="javascript">
	$(".FancyboxAnchor").fancybox();
</script>