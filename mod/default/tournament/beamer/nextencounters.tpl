<div class="headline">{$lang.tournament_nextencounters}</div>

<table width="100%" border="0" cellpadding="8" cellspacing="0">
	<tr>
		<th>
			{$lang.tournament_name}
		</th>	
		<th colspan="2">
			{$lang.encounter}
		</th>	
		<th>
			{$lang.round}
		</th>	
		<th>
			{$lang.start}
		</th>
	</tr>
	{foreach from=$encounterList item=enc}
			<tr>
				<td>
					{$enc.title}
				</td>
				<td>
					{$enc.player1id}
				</td>
				<td>
					{if $enc.participants != ''}
						{foreach from=$enc.participants item=participant}
							{$participant} <br />
						{/foreach}
					{else}
						{$enc.player2id}
					{/if}
				</td>
				<td>
					{$lang.round} {$enc.round}
				</td>
				<td align="right">
					{$enc.time.startFull}
				</td>
			</tr>
	{/foreach}
</table>