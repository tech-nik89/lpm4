<div class="headline">{$lang.credit}</div>

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<td width="20%">{$lang.balance}:</td>
			<td><strong>{$balance}</strong></td>
		</tr>
		<tr>
			<td>{$lang.last_activity}:</td>
			<td>{$last_activity}</td>
		</tr>
	</table>

<div class="headline">{$lang.credit_activities}</div>

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<th width="20%">{$lang.timestamp}</th>
			<th width="20%">{$lang.value}</th>
			<th>{$lang.description}</th>
		</tr>
		
		{section name=i loop=$mv}
		<tr class="{cycle values=',highlight_row'}">
			<td>{$mv[i].time}</td>
			<td>{$mv[i].value/100|string_format:"%.2f"}{$lang.currency}</td>
			<td>{$mv[i].description}</td>
		</tr>
		{/section}
		
	</table>
