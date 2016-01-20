<div class="headline">{$lang.orders}</div>

<table width="100%" border="0">
	<tr>
		<th>#</th>
		<th>{$lang.timestamp}</th>
		<th>{$lang.name}</th>
		<th>{$lang.product}</th>
		<th>{$lang.state}</th>
	</tr>
	{foreach from=$result item=item}
		<tr>
			<td>{$item.orderid}</td>
			<td>{timeelapsed time=$item.date}</td>
			<td>
				<a href="{makeurl mod='profile' userid=$item.ordererid}">{$item.nickname}</a>
			</td>
			<td>{$item.name}</td>
			<td>
				<a href="ajax_request.php?mod=catering&file=item.details.ajax&itemid={$item.itemid}" class="changeStateLinks">{$lang.order_state[$item.state]}</a>
			</td>
		</tr>
	{/foreach}
</table>

<script type="text/javascript">
	$(".changeStateLinks").fancybox();
</script>