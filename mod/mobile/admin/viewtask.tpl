<div class="headline">{$lang.task}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<td width="20%"><strong>{$lang.title}:</strong></td>
		<td>{$task.title}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.description}:</strong></td>
		<td>{$task.description}</td>
	</tr>
	
</table>

<div class="headline">{$lang.options}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<td width="20%"><strong>{$lang.done_at}:</strong></td>
		<td>{$task.end}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.state}:</strong></<td>
		<td>{$task.state}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.priority}:</strong></td>
		<td>{$task.priority}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.visibility}:</strong></td>
		<td>{if $task.userid == 0}{$lang.public}{else}{$lang.private}{/if}</td>
	</tr>
	
    {if $task.contactid > 0}
    <tr>
    	<td><strong>{$lang.reference_request}:</strong></td>
        <td><a href="{$url_reference}">{$lang.show}</a></td>
    </tr>
    {/if}
	
</table>

<p>
	<input type="button" name="edit" value="{$lang.edit}" onClick="location.href='{$url_edit}'" />
	<input type="button" name="edit" value="{$lang.remove}" onClick="location.href='{$url_remove}'" />
</p>