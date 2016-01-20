<div class="headline">{$group.name}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr class="highlight_row">
		<td width="33%">{$lang.tournament}:</td>
		<td><a href="{$tournament.url}">{$tournament.title}</a></td>
	</tr>
	<tr>
		<td>{$lang.name}:</td>
		<td>{$group.name}</td>
	</tr>
	<tr class="highlight_row">
		<td>{$lang.founder}:</td>
		<td><a href="{$group.founder.url}">{$group.founder.nickname}</a></td>
	</tr>
	<tr>
		<td valign="top">{$lang.members}:</td>
		<td>
			{foreach from=$group.members item=member}
			<a href="{$member.url}">{$member.nickname}</a><br />
			{/foreach}
		</td>
	</tr>
</table>