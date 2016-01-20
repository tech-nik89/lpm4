<div class="headline">{$lang.overview}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<th>{$lang.room}</th>
		<th>{$lang.description}</th>
		<th>{$lang.event}</th>
		<th>{$lang.tables_free_this_room}</th>
	</tr>
	
	{section name=i loop=$roomList}
	<tr>
		<td><a href="{$roomList[i].url}">{$roomList[i].title}</a></th>
		<td>{$roomList[i].description}</td>
		<td>{$roomList[i].event.name}</td>
		<td>{$roomList[i].free_tables}</td>
	</tr>
	{/section}
	
</table>