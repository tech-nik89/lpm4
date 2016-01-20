<div class="headline">{$lang.guestlist}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<td width="100">{$lang.event_reg_count}:</td>
		<td>{$event.registered}</td>
	</tr>
	<tr>
		<td>{$lang.event_pay_count}:</td>
		<td>{$event.payed} ({$event.payed_pre} {$lang.prepayment}, {$event.payed_box_office} {$lang.box_office})</td>
	</tr>
</table>
<p>&nbsp;</p>

<form action="" method="post">
	<p>{$lang.find}: <input type="text" name="find" value="{$find}" style="width:300px;" /> <input type="submit" name="go" value="{$lang.go}" /></p>
</form>

<table width="100%" border="0" cellspacing="1" cellpadding="5">

	<tr>
		<th><a href="{$sort.nickname}">{$lang.nickname}</a></th>
		<th><a href="{$sort.prename}">{$lang.prename}</a></th>
        <th>{if $eventid > 0}<a href="{$sort.paystate}">{/if}{$lang.event_paystate}{if $eventid > 0}</a>{/if}</th>
        <th>{if $eventid > 0}<a href="{$sort.appeared}">{/if}{$lang.appeared}{if $eventid > 0}</a>{/if}</th>
	</tr>
	
	{section name=i loop=$users}
		
		<tr class="{cycle values=',highlight_row'}">
			<td><a href="{$users[i].url}">{$users[i].nickname}</a></td>
			<td>{$users[i].prename}</td>
            <td>{$users[i].payed_str}</td>
            <td>{$users[i].appeared_str}</td>
		</tr>
		
	{/section}
	
</table>

<p>
	{$pages}
</p>