<div class="headline">{$lang.transponderlist}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th width="40">ID</th>
		<th>{$lang.lastname}</th>
		<th>{$lang.prename}</th>
	</tr>
	{foreach from=$list item=item}
		<tr{cycle values=', class="highlight_row"'}>
			<td>{$item.transponderid}</td>
			<td>{$item.lastname}</td>
			<td>{$item.prename}</td>
		</tr>
	{/foreach}
</table>