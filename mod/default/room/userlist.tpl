<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th>{$lang.nickname}</th>
		<th>{$lang.lastname}</th>
		<th>{$lang.prename}</th>
		<th>{$seatable}</td>
	</tr>
	{section name=i loop=$list}
		<tr onclick="setUser({$list[i].userid}, '{$list[i].nickname}');" style="cursor: pointer;">
			<td>{$list[i].nickname}</td>
			<td>{$list[i].lastname}</td>
			<td>{$list[i].prename}</td>
			<td>{if $list[i].reserve OR $list[i].sitdown}{$lang.yes}{else}{$lang.no}{/if}</td>
		</tr>
	{/section}
</table>
