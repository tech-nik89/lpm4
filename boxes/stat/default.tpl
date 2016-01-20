<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<td>{$lang.user_online}:</td>
		<td align="right">{$stat.user_online}</td>
	</tr>
	<tr>
		<td>{$lang.visitors_today}:</td>
		<td align="right">{$stat.visitors_today}</td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="{$stat_url}">{$lang.show_advanced_stat}</a>
		</td>
	</tr>
</table>

<br />

{foreach from=$userlist item=i}
&raquo; <a href="{$i.url}">{$i.nickname}</a><br />
{/foreach}