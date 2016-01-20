<table width="100%" border="0" cellspacing="1" cellpadding="5">
	
	<tr>
		<td><strong>{$lang.name}:</strong></td>
		<td><a href="{$box_events_event.url}">{$box_events_event.name}</a></td>
	</tr>
	
	<tr>
		<td><strong>{$lang.begin}:</strong></td>
		<td>{$box_events_event.start}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.seats}:</strong></td>
		<td>{$box_events_event.seats}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.event_reg_count}:</strong></td>
		<td>{$box_events_event.registered}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.event_pay_count}:</strong></td>
		<td>{$box_events_event.payed}</td>
	</tr>
	
	<tr>
		<td colspan="2" align="center">
			{include file='../boxes/events/bar.tpl'}
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
		{if $logged_in}
			<strong>&raquo; 
				<a href="{$box_events_event.url}#register">
					<font color="#{if $box_events_reg==1}009900{else}CC0000{/if}">
						{$box_events_state}
					</font>
				</a>
			</strong>
		{else}
			&raquo; {$lang.please_log_in}
		{/if}
		</td>
	</tr>
	
</table>