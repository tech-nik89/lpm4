<div class="headline">{$lang.my_orders}</div>

{if count($orders) == 0}
	<p>{$lang.no_orders_msg}</p>
{else}
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<th width="25">#</th>
			<th width="120">{$lang.timestamp}</th>
			<th width="35">{$lang.items}</th>
			<th>{$lang.state}</th>
		</tr>
		{foreach from=$orders item=order}
			<tr {cycle values=', class="highlight_row"'}>
				<td>{$order.id}</td>
				<td>
					<a href="ajax_request.php?mod=catering&file=order.details&orderid={$order.id}" class="details">
						{timeelapsed time=$order.date}
					</a>
				</td>
				<td>{$order.items}</td>
				<td>{$lang.order_state[$order.max_state]}</td>
			</tr>
		{/foreach}
	</table>
{/if}

<script type="text/javascript">
	$(".details").fancybox();
</script>