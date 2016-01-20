<div class="headline">{$lang.events}</div>

<table width="100%" border="0" cellspacing="1" cellpadding="5">
	
	<tr>
		<th>{$lang.name}</th>
		<th>{$lang.begin}</th>
		<th>{$lang.min_age}</th>
		<th>{$lang.event_pay_count}</th>
		<th>{$lang.event_last_check}</th>
	</tr>
	
	{section name=i loop=$list}
	
	<tr class="{cycle values='highlight_row,'}">
		<td><a href="{$list[i].url}">{$list[i].name}</a></td>
		<td>{$list[i].start} {$lang.o_clock}</td>
		<td>{$list[i].min_age} {$lang.years}</td>
		<td>{include file=$bar_tpl payed_width=$list[i].payed_width not_payed_width=$list[i].not_payed_width free_width=$list[i].free_width}</td>
		<td>{$list[i].last_check}</td>
	</tr>
	
	{/section}
	
</table>