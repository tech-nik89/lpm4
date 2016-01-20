{section name=i loop=$sections}
	{if $sections[i].news_count > 0}
		<div class="headline">{$sections[i].title}</div>
		<table width="100%" border="0" cellspacing="1" cellpadding="5">
		{section name=j loop=$sections[i].news}
				<tr>
					<td width="25%"><a href="{$sections[i].news[j].url}">{$sections[i].news[j].title}</a></td>
					<td width="25%">{$sections[i].news[j].time}</td>
					<td width="25%"><a href="{$sections[i].news[j].user_url}">{$sections[i].news[j].nickname}</a></td>
					<td width="25%">{$sections[i].news[j].comments} {$lang.comments}</td>
				</tr>
		{/section}
		</table>
	{/if}
{/section}