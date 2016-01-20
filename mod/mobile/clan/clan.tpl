<div class="headline">{$lang.clan}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<td>{$lang.name}:</td>
		<td>
			{$clan.name}
		</td>
	</tr>
	<tr>
		<td>{$lang.prefix}:</td>
		<td>
			{$clan.prefix}
		</td>
	</tr>
	<tr>
		<td>{$lang.description}:</td>
		<td>
			{$clan.description}
		</td>
	</tr>
	<tr>
		<td>{$lang.leader}:</td>
		<td>
			<a href="{$clan.leader.url}">{$clan.leader.nickname}</a>
		</td>
	</tr>
	<tr>
		<td>{$lang.members}:</td>
		<td>
			<ul style="padding-left:1em;">
				{foreach from=$clan.members item=member}
					<li><a href="{$member.url}">{$member.nickname}</a></li>
				{/foreach}
			</ul>
		</td>
	</tr>
</table>

{if !$hasclan && $loggedin}
	<div class="headline">{$lang.joinclan}</div>
	<form action="" method="post">
		<p>
			{$lang.password}:
			<input type="password" name="password" value="" style="width:300px;" />
			<input type="submit" name="join" value="{$lang.joinclan}" />
		</p>
	</form>
{/if}

{if $myclan && $myid != $clan.leader.userid}
	<div class="headline">{$lang.leaveclan}</div>
	<form action="" method="post">
		<p>
			<input type="submit" name="leave" value="{$lang.leaveclan}" />
		</p>
	</form>
{/if}