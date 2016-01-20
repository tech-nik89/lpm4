<ul style="padding-left:1em;">
{foreach from=$tournaments item=tourney}
	<li style="color:{$tourney.listcolor};" title="{$tourney.statename}">{$tourney.url}</li>
	{if $tourney.nextencounter != ''} 
		<div style="padding-left:5px;">
		{if $tourney.nextencounter.kickedout}
			{$lang.kickedout}
		{elseif $tourney.nextencounter.finished}
			{$lang.finished}
		{else}
			{$lang.round}: {$tourney.nextencounter.roundid} <br/>
			{if $tourney.nextencounter.mapname != ''}
				{$lang.map}: {$tourney.nextencounter.mapname} <br/>
			{/if}
			{if $tourney.nextencounter.startTime !=''}
				<div style="color:{$tourney.nextencounter.timeState.color};">
					{$lang.start}: {$tourney.nextencounter.startTime} {$lang.o_clock}
				</div>
			{/if}
			{$tourney.nextencounter.encounterUrl} <br />

			
		{/if}
		</div>
	{/if}
	{if $tourney.state == 3}
		{$lang.winner}: <a href="{$tourney.ranking[0].participants[0].url}">{$tourney.ranking[0].participants[0].name}</a>
	{/if}
{/foreach}
</ul>