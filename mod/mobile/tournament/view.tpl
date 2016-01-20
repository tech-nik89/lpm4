<div class="headline">{$tournament.title}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
  <tr>
  	<td width="25%">{$lang.event_assignment}:</td>
	<td><a href="{$tournament.event.url}">{$tournament.event.name}</a></td>
  </tr>
  <tr bgcolor="#DDDDDD">
    <td valign="top">{$lang.tournament_start}:</td>
    <td valign="top">{$tournament.start_str}</td>
  </tr>
  <tr>
    <td valign="top">{$lang.tournament_name}:</td>
    <td valign="top">{$tournament.title}</td>
  </tr>
  <tr bgcolor="#DDDDDD">
    <td valign="top">{$lang.state}:</td>
    <td valign="top">{$tournament.state_str}</td>
  </tr>
  <tr>
    <td valign="top">{$lang.playerlimit}:</td>
    <td valign="top">{$tournament.playerlimit}</td>
  </tr>
  <tr bgcolor="#DDDDDD">
    <td valign="top">{$lang.tournament_joined}:</td>
    <td valign="top">{$tournament.regcount}</td>
  </tr>
  <tr>
    <td valign="top">{$lang.tournament_game}:</td>
    <td valign="top">{$tournament.game}</td>
  </tr>
  <tr bgcolor="#DDDDDD">
    <td valign="top">{$lang.map_pool}:</td>
    <td valign="top">{$tournament.mappool_str}</td>
  </tr>
  <tr>
    <td valign="top">{$lang.game_mode}:</td>
    <td valign="top">{$tournament.mode_str}</td>
  </td>
  </tr>
  <tr bgcolor="#DDDDDD">
    <td valign="top">{$lang.credits}:</td>
    <td valign="top">{$tournament.credits}</td>
  </tr>
  <tr>
    <td>{$lang.playerperteam}:</td>
    <td>{$tournament.playerperteam}</td>
  </tr>
</table>

{if $tournament.state == 3}
	<div class="headline">{$lang.tournament_results}</div>

    
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
    {section loop=$ranking name=i start=0}
    	<tr class="{cycle values='highlight_row,'}">
			<td valign="top" width="20">
				{$ranking[i].rank}.
			</td>
			<td valign="top">
            {foreach from=$ranking[i].participants item=rank}
                <a href="{$rank.url}">{$rank.name}</a>
				{if !empty($rank.points)}
					</td>
					<td>
					{$rank.points}
				{/if}
            {/foreach}
			</td>
        </tr>
    {/section}
	</table>
{/if}

{if $tournament.state == 1 && $logged_in}
	<div class="headline">{$lang.registration}</div>
	
	{if $tournament.playerperteam == 1}
		
		{if $already_joined == false}
			
			{if $tournament.regcount < $tournament.playerlimit}
			
				<p>{$lang.not_joined}</p>
				<form action="" method="post">
					<input type="submit" value="{$lang.join}" name="join" />
				</form>
			
			{else}
				<p>{$lang.tournament_full}</p>
			{/if}
			
		{else}
		
			<p>{$lang.already_joined}</p>
			<form action="" method="post">
				<input type="submit" value="{$lang.unjoin}" name="unjoin" />
			</form>				
			
		{/if}
		
    {else}
    
        {if $already_joined == false}
            
			<p>{$lang.not_joined}</p>
			
			<div style="float:left;">
				<form action="" method="post">
					<table border="0" cellpadding="5" cellspacing="1">
						<tr>
							<td>&nbsp;</td>
							<td><strong>{$lang.create_group}</strong></td>
						</tr>
						<tr>
							<td><strong>{$lang.group}:</strong></td>
							<td><input type="text" name="creategroup_name" value=""
								{if $tournament.regcount >= $tournament.playerlimit} readonly="readonly"{/if} /></td>
						</tr>
						<tr>
							<td><strong>{$lang.password}:</strong></td>
							<td><input type="password" name="creategroup_password"
								{if $tournament.regcount >= $tournament.playerlimit} readonly="readonly"{/if} /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" name="creategroup" value="{$lang.create_group}" /></td>
						</tr>
					</table>
				</form>
			</div>
			
			<div style="float:left;">
				<form action="" method="post">
					<table border="0" cellpadding="5" cellspacing="1">
						<tr>
							<td><strong>{$lang.join_group}</strong></td>
						</tr>
						<tr>
							<td>
								<select name="joingroup_groupid">
									{foreach from=$grouplist item=group}
										<option value="{$group.groupid}">{$group.name}</option>
									{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<td><input type="password" name="joingroup_password" /></td>
						</tr>
						<tr>
							<td><input type="submit" name="joingroup" value="{$lang.join_group}" /></td>
						</tr>
					</table>
				</form>
			</div>
			<div style="clear:left;"></div>
            
        {else}
        
            <p>{$lang.already_joined}</p>
            <form action="" method="post">
                <input type="submit" value="{$lang.unjoin}" name="unjoin" />
            </form>		
        
        {/if}
 
	{/if}
	
    <div class="headline">{$lang.playerlist}</div>

    {if $tournament.playerperteam == 1}
    
        <table width="100%" border="0" cellpadding="0" cellspacing="1">
    
          {section name=i loop=$playerlist}
          <tr>
            <td>
                <a href="{$playerlist[i].url}">{$playerlist[i].nickname}</a>
            </td>
          </tr>
          {/section}
          
        </table>
    
    {else}
    
        <table width="100%" border="0" cellpadding="0" cellspacing="1">
    
          {section name=i loop=$grouplist}
          <tr>
            <td width="33%">
                <a href="{$grouplist[i].url}">{$grouplist[i].name}</a>
            </td>
            <td>
                {$grouplist[i].members} / {$tournament.playerperteam}
            </td>
          </tr>
          {/section}
          
        </table>
    
    {/if}
    
{/if}

<div class="headline">WWCL</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
  <tr>
  	<td width="25%">WWCL Game ID:</td>
	<td>{$tournament.wwclgameid_str}</td>
  </tr>
  <tr>
  	<td>{$lang.rules}:</td>
	<td><a href="media/wwcl/rules/{$tournament.rules}" target="_blank">{$tournament.rules}</a></td>
  </tr>
</table>