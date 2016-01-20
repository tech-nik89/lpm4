<div class="headline">{$lang.edittask}</div>

<form action="" method="post">

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<td width="20%"><strong>{$lang.title}:</strong></td>
		<td><input type="text" name="title" style="width:100%" value="{$task.title}" /></td>
	</tr>
	
	<tr>
		<td><strong>{$lang.description}:</strong></td>
		<td><textarea name="description" style="width:100%; height:150px;">{$task.description}</textarea></td>
	</tr>
	
</table>

<div class="headline">{$lang.options}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<td width="20%"><strong>{$lang.done_at}:</strong></td>
		<td>
			{html_select_date time=$task.end} 
			{html_select_time display_seconds=false time=$task.end} {$lang.o_clock}
		</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.state}:</strong></<td>
		<td>{html_options options=$state name=state selected=$task.state}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.priority}:</strong></td>
		<td>{html_options options=$priority name=priority selected=$task.priority}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.visibility}:</strong></td>
		<td>
			<label>
				<input type="radio" name="private" value="0" {if $task.userid == 0}checked="checked"{/if} />
				{$lang.public}
			</label>
			<label>
				<input type="radio" name="private" value="{$userid}" {if $task.userid == $userid}checked="checked"{/if} /> 
				{$lang.private}
			</label> 
		</td>
	</tr>
	
</table>

<p>
	<input type="submit" name="save" value="{$lang.save}" />
</p>

</form>