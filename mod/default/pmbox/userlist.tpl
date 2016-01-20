<table width="100%" border="0" cellpadding="5" cellspacing="1">
	{section name=i loop=$list}
		<tr>
			<td width="170">
				<a href="#" onclick="setUser('{$list[i].nickname}');">{$list[i].nickname}</a>
			</td>
			<td>
				{$list[i].prename}
			</td>
		</tr>
	{/section}
</table>