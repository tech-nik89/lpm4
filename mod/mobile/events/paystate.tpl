<div class="headline">{$lang.event_paystate}</div>

<form action="" method="post">
	<div align="right">
		<strong>{$lang.search}:</strong>
		<input type="text" name="search_string" value="{$search_string}" />
		<input type="submit" name="search" value="{$lang.search}" />
	</div>
</form>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<th>{$lang.nickname}</th>
		<th>{$lang.lastname}</th>
		<th>{$lang.prename}</th>
		<th>{$lang.event_paystate}</th>
	</tr>

	{section name=i loop=$list}
		
	<tr class="{cycle values=',highlight_row'}">
		<td>{$list[i].nickname}</td>
		<td>{$list[i].lastname}</td>
		<td>{$list[i].prename}</td>
		<td>
			<form action="" method="post">
				<input type="hidden" name="search" />
				<input type="hidden" name="search_string" value="{$search_string}" />
				{html_options name=paystate options=$paystates selected=$list[i].payed}
				<input type="submit" name="save" value="{$lang.save}" />
				<input type="hidden" name="userid" value="{$list[i].userid}" />
			</form>
		</td>
	</tr>
		
	{/section}
	
</table>