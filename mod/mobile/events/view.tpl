<div class="headline">{$lang.event}</div>

<table width="100%" border="0" cellspacing="1" cellpadding="5">
	
	<tr class="highlight_row">
		<td width="170"><strong>{$lang.name}:</strong></td>
		<td>{$event.name}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.description}:</strong></td>
		<td>{$event.description}</td>
	</tr>
	
	<tr class="highlight_row">
		<td><strong>{$lang.begin}:</strong></td>
		<td>{$event.start} {$lang.o_clock}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.end}:</strong></td>
		<td>{$event.end} {$lang.o_clock}</td>
	</tr>
	
	<tr class="highlight_row">
		<td><strong>{$lang.reg_begin}:</strong></td>
		<td>{$event.reg_start} {$lang.o_clock}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.reg_end}:</strong></td>
		<td>{$event.reg_end} {$lang.o_clock}</td>
	</tr>
	
	<tr class="highlight_row">
		<td><strong>{$lang.min_age}:</strong></td>
		<td>{$event.min_age} {$lang.years}</td>
	</tr>
	
    <tr>
		<td><strong>{$lang.seats}:</strong></td>
		<td>{$event.seats}</td>
	</tr>
    
	<tr class="highlight_row">
		<td><strong>{$lang.event_reg_count}:</strong></td>
		<td>{$event.registered}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.event_pay_count}:</strong></td>
		<td>{include file=$bar_tpl}</td>
	</tr>
	
	<tr>
		<td>&nbsp;</td>
		<td>{if $event.free == 0}{$event.payed} ({$event.payed_pre} {$lang.prepayment}, {$event.payed_box_office} {$lang.box_office}){else}-{/if}</td>
	</tr>
	
	<tr class="highlight_row">
		<td><strong>{$lang.agb}:</strong></td>
		<td><a href="{$agb_url}">{$lang.show}</a></td>
	</tr>
	
	<tr>
		<td><strong>{$lang.event_last_check}:</strong></td>
		<td>{$event.last_check}</td>
	</tr>
	
	<tr class="highlight_row">
		<td><strong>{$lang.credits}:</strong></td>
		<td>{$event.credits}</td>
	</tr>
	
</table>

{if $event.login_active == 1}
	<a name="register"></a>
	<div class="headline">{$lang.event_reg}</div>
	
	{if ($event.free == 0 && $event.payed < $event.seats) || ($event.free == 1 && $event.registered < $event.seats) || $reg == 1}
	
		<form action="" method="post">
		
			<table width="100%" border="0" cellspacing="1" cellpadding="5">
				
				<tr>
					<td width="20%"><strong>{$lang.state}:</strong></td>
					<td><font color="{if $reg==0}#990000{else}#009900{/if}">{$state}</font></td>
				</tr>
				
				{if $reg==0}
				
				<tr>
					<td>&nbsp;</td>
					<td><label><input type="checkbox" name="agb" value="1" /> {$lang.agb_accept}</label></td>
				</tr>
				
				{/if}
				
				<tr class="highlight_row">
					<td><strong>{$lang.action}:</strong></td>
					<td><input type="submit" name="register" value="{$register}" /></td>
				</tr>
				
			</table>
		
		</form>
	
	{else}
	
		<p>{$lang.event_full}</p>
	
	{/if}
	
{/if}

{if $isallowed == true}

	<div class="headline">{$lang.administrate}</div>
	
	<form action="" method="post">
		<p>
			<input type="submit" name="unregister_all" value="{$lang.event_unregister_all}" />
			<input type="submit" name="last_check" value="{$lang.event_last_check_now}" />
			<input type="button" name="edit" value="{$lang.edit}" onclick="location.href='{$edit_url}'" />
			<input type="button" name="remove" value="{$lang.remove}" onclick="location.href='{$remove_url}'" />
		</p>
	</form>
{/if}