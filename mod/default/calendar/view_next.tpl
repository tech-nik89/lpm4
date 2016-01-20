<div class="headline">{$lang.view_next}</div>
{include file='../mod/default/calendar/switch_view.tpl' url=$url lang=$lang view=$view}
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th width="160">{$lang.start}</th>
		<th width="160">{$lang.end}</th>
		<th>{$lang.calendar_entry}</th>
	</tr>
	{section name=i loop=$entries}
	<tr{cycle values=', class="highlight_row"'}>
		<td>
			{$entries[i].day}<br />
			{$entries[i].time}
		</td>
		<td>
			{if $entries[i].day_end != ''}
				{$entries[i].day_end}
				<br />
			{/if}
			{$entries[i].time_end}
		</td>
		<td><a href="{$entries[i].url}">{$entries[i].title}</a></td>
	</tr>
	{/section}
</table>