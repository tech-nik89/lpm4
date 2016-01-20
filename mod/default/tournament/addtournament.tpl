<div class="headline">{$action}</div>

<script language="javascript" type="text/javascript">
	
	function hideAll() {
		document.getElementById('singleelimination').style.display='none';
		document.getElementById('doubleelimination').style.display='none';
		document.getElementById('group').style.display='none';
		document.getElementById('points').style.display='none';
		document.getElementById('randomize').style.display='none';
		document.getElementById('koth').style.display='none';
		document.getElementById('deathmatch').style.display='none';
	}
	
	function show(id) {
		hideAll();
		document.getElementById(id).style.display='block';
	}
	
	$(document).ready(function() {
		var mode = '{$tournament.mode}';
		
		switch (mode) {
			case '1':
				show('singleelimination');
				break;
			case '2':
				show('doubleelimination');
				break;
			case '3':
				show('group');
				break;
			case '4':
				show('points');
				break;
			case '5':
				show('randomize');
				break;
			case '6':
				show('koth');
				break;
			case '7':
				show('deathmatch');
				break;
		}
	});
	
</script>

<form action="" method="post">

<table width="100%" border="0" cellpadding="5" cellspacing="1">
  <tr>
  	<td  width="25%">{$lang.event_assignment}:</td>
	<td>{html_options name=eventid options=$eventlist selected=$tournament.eventid}</td>
  </tr>
  <tr>
    <td valign="top">{$lang.tournament_name}:</td>
    <td valign="top"><input type="text" name="title" value="{$tournament.title}" /></td>
  </tr>
  <tr>
    <td valign="top">{$lang.playerlimit}:</td>
    <td valign="top"><select name="playerlimit" id="playerlimit">
      <option value="4"{if $tournament.playerlimit == 4} selected="selected"{/if}>4</option>
      <option value="8"{if $tournament.playerlimit == 8} selected="selected"{/if}>8</option>
      <option value="16"{if $tournament.playerlimit == 16} selected="selected"{/if}>16</option>
      <option value="32"{if $tournament.playerlimit == 32} selected="selected"{/if}>32</option>
      <option value="64"{if $tournament.playerlimit == 64} selected="selected"{/if}>64</option>
      <option value="128"{if $tournament.playerlimit == 128} selected="selected"{/if}>128</option>
      <option value="256"{if $tournament.playerlimit == 256} selected="selected"{/if}>256</option>
    </select></td>
  </tr>
  <tr>
    <td valign="top">{$lang.tournament_game}:</td>
    <td valign="top"><input type="text" name="game" id="game" value="{$tournament.game}"></td>
  </tr>
  <tr>
    <td valign="top">{$lang.map_pool}:</td>
    <td valign="top"><input type="text" name="mappool" id="mappool" value="{$tournament.mappool}"> 
      <br>
      {$lang.howto_separate}</td>
  </tr>
  <tr>
    <td valign="top">{$lang.game_mode}:</td>
    <td valign="top">
    <p>
      	<label>
        	<input type="radio" name="mode" value="1" id="mode_1" {if $tournament.mode == 1 || $tournament.mode == ""} checked="checked"{/if} onclick="show('singleelimination');" />
        	{$lang.singleelimination}
        </label>
      <br />
      	<label>
        	<input type="radio" name="mode" value="2" id="mode_2" {if $tournament.mode == 2} checked="checked"{/if} onclick="show('doubleelimination');" />
        	{$lang.doubleelimination}
        </label>
      <br />
        <label>
        	<input type="radio" name="mode" value="3" id="mode_3" {if $tournament.mode == 3} checked="checked"{/if} onclick="show('group');" />
        	{$lang.group}
        </label>
      <br />
        <label>
        	<input type="radio" name="mode" value="4" id="mode_4" {if $tournament.mode == 4} checked="checked"{/if} onclick="show('points');" />
        	{$lang.points}
        </label>
      <br />
        <label>
        	<input type="radio" name="mode" value="5" id="mode_5" {if $tournament.mode == 5} checked="checked"{/if} onclick="show('randomize');" />
        	{$lang.randomize}
        </label>
      <br />
	     <label>
        	<input type="radio" name="mode" value="6" id="mode_6" {if $tournament.mode == 6} checked="checked"{/if} onclick="show('koth');" />
        	{$lang.koth}
        </label>
      <br />
		<label>
        	<input type="radio" name="mode" value="7" id="mode_7" {if $tournament.mode == 7} checked="checked"{/if} onclick="show('deathmatch');" />
        	{$lang.deathmatch}
        </label>
      <br />
    </p></td>
  </tr>
  <tr style="display:none;">
    <td valign="top">{$lang.picture}:</td>
    <td valign="top"><input type="text" name="picture" id="picture" value="{$tournament.picture}" /></td>
  </tr>
  <tr>
    <td valign="top">{$lang.credits}:</td>
    <td valign="top"><input type="text" name="credits" value="{$tournament.credits}" /></td>
  </tr>
  <tr>
    <td>{$lang.playerperteam}:</td>
    <td>{html_options name=playerperteam options=$playerperteamlist selected=$tournament.playerperteam}</td>
  </tr>
</table>

<div class="headline">{$lang.time_management}</div>
<p>{$lang.time_management_description}</p>
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<td valign="top">{$lang.tournament_start}:</td>
		<td valign="top">{html_select_date time=$tournament.start end_year="+2" start_year="-2"}<br />{html_select_time display_seconds=false time=$tournament.start}</td>
	</tr>
	<tr>
		<td>{$lang.duration}:</td>
		<td>
			<input type="number" value="{$tournament.duration}" name="duration" style="width:70px;" />
			{$lang.min}
		</td>
	</tr>
	<tr>
		<td>{$lang.breaktime}:</td>
		<td>
			<input type="number" value="{$tournament.breaktime}" name="breaktime" style="width:70px;" />
			{$lang.min}
		</td>
	</tr>
</table>

<div class="headline">WWCL</div>


<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
    	<td width="25%">WWCL Game ID:</td>
        <td>
        <select name="wwclgameid">
        	{section name=i loop=$wwcl_game_ini.gameid}
            <option value="{$wwcl_game_ini.gameid[i]}" {if $wwcl_game_ini.gameid[i] == $tournament.wwclgameid} selected="selected"{/if}>{$wwcl_game_ini.name[i]}</option>
            {/section}
        </select>
        </td>
    </tr>
    <tr>
    	<td>{$lang.rules}:</td>
        <td>
		<select name="rules">
			<option value="">{$lang.none}</option>
			{section name=i loop=$rules}
			<option value="{$rules[i]}" {if $tournament.rules == $rules[i]} selected="selected"{/if}>{$rules[i]}</option>
			{/section}
		</select>
		</td>
    </tr>
</table>

<div id="singleelimination">

	<div class="headline">{$lang.singleelimination}</div>
	
    <table width="100%" border="0" cellpadding="5" cellspacing="1">
    
        <tr>
        	<td width="25%">{$lang.mixed_game}:</td>
            <td><input type="checkbox" name="single_elimination_mixed_game" value="1" {if $single_elimination_mixed_game == 1}checked="checked"{/if} /></td>
	    </tr>
        
        <tr>
        	<td>{$lang.third_place_game}:</td>
            <td><input type="checkbox" name="single_elimination_third_place_game" value="1"  {if $single_elimination_third_place_game == 1}checked="checked"{/if} /></td>
	    </tr>
        
    </table>
    
</div>

<div id="doubleelimination" style="display:none;">

	<div class="headline">{$lang.doubleelimination}</div>
	
    <table width="100%" border="0" cellpadding="5" cellspacing="1">
    
        <tr>
        	<td width="25%">{$lang.mixed_game}:</td>
            <td><input type="checkbox" name="double_elimination_mixed_game" value="1" /></td>
	    </tr>
        
    </table>
    
</div>

<div id="group" style="display:none;">

	<div class="headline">{$lang.group}</div>
	
    <table width="100%" border="0" cellpadding="5" cellspacing="1">
    
    	<tr>
			<td>{$lang.playerpergroup}:</td>
	        <td>{html_options name=groups_playerpergroup options=$playerpergrouplist selected=$playerpergroup}</td>
      	</tr>
    
        <tr>
        	<td width="25%">{$lang.winners_per_group}:</td>
            <td>{html_options name=groups_winnerpergroup options=$winners_per_group_list selected=$winnerpergroup}</td>
	    </tr>
        
    </table>
    
</div>

<div id="points" style="display:none;">

</div>

<div id="randomize" style="display:none;">

</div>

<div id="koth" style="display:none;">

	<div class="headline">{$lang.koth}</div>
	
    <table width="100%" border="0" cellpadding="5" cellspacing="1">
    
        <tr>
        	<td width="25%">{$lang.rounds}:</td>
            <td><input type="input" name="number_of_rounds" value="{if $number_of_rounds>0}{$number_of_rounds}{else}10{/if}" /></td>
	    </tr>
        
    </table>
    
</div>

<div id="deathmatch" style="display:none;">

</div>


<p>
	<input type="submit" name="save" value="{$lang.save}" />
</p>

</form>