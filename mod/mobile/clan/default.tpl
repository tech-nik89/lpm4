<div class="headline">{$lang.clans}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th>{$lang.name}</th>
		<th width="50">{$lang.prefix}</th>
		<th>{$lang.leader}</th>
	</tr>
	{foreach from=$clanlist item=clan}
		<tr class="{cycle values=',highlight_row'}">
			<td><a href="{$clan.url}">{$clan.name}</a></td>
			<td>{$clan.prefix}</td>
			<td>{$clan.nickname}</td>
		</tr>
	{/foreach}
</table>