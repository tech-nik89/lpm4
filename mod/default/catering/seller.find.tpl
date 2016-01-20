<table width="100%" border="0">
	{foreach from=$result item=item}
		<tr>
			<td>
				<a href="javascript:void(0);" onclick="SellerSelected({$item.userid});">{$item.nickname}</a>
			</td>
			<td>{$item.prename}</td>
			<td>{$item.lastname}</td>
		</tr>
	{/foreach}
</table>