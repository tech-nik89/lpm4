<table width="100%" border="0">
	{foreach from=$result item=item}
		<tr>
			<td width="30%"><a href="javascript:void(0);" onclick="payCredit({$item.userid});">{$item.nickname}</a></td>
			<td width="30%">{$item.prename}</td>
			<td>{$item.lastname}</td>
		</tr>
	{/foreach}
</table>