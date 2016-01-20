<table width="100%">
	{foreach from=$boxservers item=servers}
		<tr>
			<td>{$servers.name}</td>
			<td>{$servers.usersonline} / {$servers.maxusers}</td>
			<td>
				{if $servers.join}
				<a href="{$servers.joinlink}">{$lang.joinserver}</a>
				{/if}			
			</td>		
		</tr>
	{/foreach}
</table>