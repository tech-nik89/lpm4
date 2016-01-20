<div class="headline">{$lang.view_next}</div>
{include file='../mod/default/calendar/switch_view.tpl' url=$url lang=$lang view=$view}
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th width="160">{$lang.day}</th>
		<th width="160">{$lang.time}</th>
		<th>{$lang.calendar_entry}</th>
	</tr>
	{section name=i loop=$entries}
	<tr>
		<td>{$entries[i].day}</td>
		<td>{$entries[i].time}</td>
		<td><a href="{$entries[i].url}">{$entries[i].title}</a></td>
	</tr>
	{/section}
</table>