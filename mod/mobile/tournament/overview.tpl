<div class="headline">{$lang.tournament_overview}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
    <tr>
    	<th>{$lang.tournament}</th>
        <th>{$lang.tournament_game}</th>
		<th>{$lang.game_mode}</th>
        <th>{$lang.tournament_joinstate}</th>
        <th>{$lang.state}</th>
    </tr>
    
    {section name=i loop=$tournamentList}
    
    <tr class="{cycle values=',highlight_row'}">
    	<td><a href="{$tournamentList[i].url}">{$tournamentList[i].title}</a></td>
        <td>{$tournamentList[i].game}</td>
		<td>{$modenames[$tournamentList[i].mode]}</td>
        <td>{$tournamentList[i].joinstate}</td>
        <td>{$tournamentList[i].state}</td>
    </tr>
    
    {/section}
    
</table>