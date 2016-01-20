<div class="headline">{$lang.profile_of} {$user.nickname}</div>

	<table width="100%" border="0" cellspacing="1" cellpadding="5">
		
		{if $user.nickname != $user.prename|cat:" "|cat:$user.lastname}
			<tr>
				<td width="150">{$lang.nickname}:</td>
				<td><strong>{$user.nickname}</strong></td>
				<td rowspan="4" align="right" valign="top">{$avatar}</td>
			</tr>
		{/if}
		
		<tr>
			<td>{$lang.prename}:</td>
			<td><strong>{$user.prename}</strong></td>
			{if $user.nickname == $user.prename|cat:" "|cat:$user.lastname}
				<td rowspan="3" align="right" valign="top">{$avatar}</td>
			{/if}
		</tr>
		
		<tr>
			<td>{$lang.lastname}:</td>
			<td><strong>{$user.lastname}</strong></td>
		</tr>
		
		<tr>
			<td>{$lang.last_seen}:</td>
			<td><strong>{$last_seen}</strong></td>
		</tr>
		
		{if $clan != null}
			<tr>
				<td>{$lang.clan}:</td>
				<td>
					<a href="{$clan.url}">
						<strong>{$clan.name}</strong>
					</a>
				</td>
			</tr>
		{/if}
		
		{if $usergroups|@Count > 0}
			<tr>
				<td>{$lang.groups}:</td>
				<td>
					{section name=i loop=$usergroups}
						{$usergroups[i].name}{if $smarty.section.i.index < $usergroups|@Count-1},{/if}
					{/section}
				</td>
			</tr>
		{/if}
		
	</table>
	
<div class="headline">{$lang.contact}</div>
	<p>
    	<input type="button" name="sendpm" value="{$lang.sendpm}" onclick="location.href='{$pm_url}'" />
    </p>
	
	{if $buddylist_enabled && $logged_in && $myid != $user.userid}
		<form action="" method="post">
			<p>
				{if !$is_buddy}
				<input type="submit" name="buddy_request" value="{$lang.buddy_request}" />
				{/if}
			</p>
		</form>
	{/if}
    
<div class="headline">
	{$lang.personal}
	{if $myid == $user.userid}
		(<a href="{$edit_personal_url}">{$lang.edit_my_personal}</a>)
	{/if}
</div>
	<table width="100%" border="0" cellspacing="1" cellpadding="5">
		{section name=i loop=$personal}
			{if $personal[i].value != ''}
				<tr>
					<td width="150">{$personal[i].name}:</td>
					<td><strong>{$personal[i].value}</strong></td>
				</tr>
			{/if}
		{/section}
	</table>

{if $event_count > 0}
<div class="headline">{$lang.registered_at}</div>

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
    	
		<tr>
			<th>{$lang.event}</th>
			<th>{$lang.date}</th>
			<th>{$lang.seat}</th>
		</tr>
		
        {section name=i loop=$events}
        
        <tr class="{cycle values=',highlight_row'}">
        	<td width="150">
            	<a href="{$events[i].url}">{$events[i].name}</a>
            </td>
            <td width="200">
            	{$events[i].date}
            </td>
			<td>
				{if $events[i].seat !='-'}
					<a href="{$events[i].seat_url}">{$events[i].seat}</a>
				{else}
					{$events[i].seat}
				{/if}
			</td>
        </tr>
        
        {/section}
        
    </table>
{/if}