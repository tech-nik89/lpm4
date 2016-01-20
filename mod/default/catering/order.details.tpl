<div class="headline">{$lang.order_details}</div>

<table width="500" border="0">
	<tr>
		<th>{$lang.name}</th>
		<th>{$lang.price}</th>
		<th>{$lang.state}</th>
	</tr>
	{foreach from=$items item=item}
		<tr>
			<td>{$item.name}</td>
			<td>{({$item.price_sum} / 100)|number_format:2:",":"."} &euro;</td>
			<td>{$lang.order_state[$item.state]}</td>
		</tr>
	{/foreach}
</table>