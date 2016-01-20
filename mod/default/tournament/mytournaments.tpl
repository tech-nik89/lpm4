<div class="headline">{$lang.my_tournaments}</div>

<ul>
	{if !$no_tournaments} 
		{foreach from=$mytournaments item=tournament}
			<li style="color:{$tournament.color};">
				<a href="{$tournament.url}">
					{$tournament.title}
				</a>
			</li>
		{/foreach}
	{else}
		{$lang.no_tournaments_registered}
	{/if}
</ul>

<div class="headline">{$lang.credits}</div>
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th>{$lang.event}</th>
		<th>{$lang.credits}</th>
	</tr>
	{foreach from=$credits item=credit}
		<tr>
			<td>{$credit.name}</td>
			<td>
				{if $credit.credits > 0}
					{$credit.credits}
				{else}
					0
				{/if}
			</td>
		</tr>
	{/foreach}
</table>