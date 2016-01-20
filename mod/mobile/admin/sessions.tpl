<div class="headline">{$lang.sessions}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<th>{$lang.userid}</th>
		<th>{$lang.session_id}</th>
		<th>{$lang.nickname}</th>
		<th>{$lang.ipadress}</th>
		<th>{$lang.lastaction}</th>
	</tr>
	
	{section name=i loop=$list}
	<tr>
		<td>{$list[i].userid}</td>
		<td>{$list[i].session_id}</td>
		<td><a href="{$list[i].url}">{$list[i].nickname}</a></td>
		<td>{$list[i].ipadress}</td>
		<td>{$list[i].str_lastaction}</td>
	</tr>
	{/section}
	
</table>