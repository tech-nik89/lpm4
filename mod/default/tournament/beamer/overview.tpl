<div class="headline">{$lang.tournament_overview}</div>

<table width="100%" border="0" cellpadding="8" cellspacing="1">
    
    {section name=i loop=$tournamentList}
    
		<tr>
			<td><strong>{$tournamentList[i].title}</strong></td>
			<td>{$tournamentList[i].game}</td>
			<td align="right"><span style="color:{$tournamentList[i].color};">{$tournamentList[i].state_str}</span></td>
		</tr>
		
    {/section}
    
</table>