<div class="headline">{$action}</div>

<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<td width="130">{$lang.name}:</td>
			<td><input type="text" name="title" value="{$room.title}" style="width:100%;" /></td>
		</tr>
		
		<tr>
			<td>{$lang.description}:</td>
			<td><textarea name="description" style="width:100%;" rows="7">{$room.description}</textarea></td>
		</tr>
		
		<tr>
			<td>{$lang.height}:</td>
			<td><input type="text" name="height" value="{$room.height}" style="width:70px;" /> {$lang.fields}</td>
		</tr>
		
		<tr>
			<td>{$lang.width}:</td>
			<td><input type="text" name="width" value="{$room.width}" style="width:70px;" /> {$lang.fields}</td>
		</tr>
		
		<tr>
			<td>{$lang.event}:</td>
			<td>
				<select name="event">
					<option value="-1">
						&nbsp;
					</option>
					{foreach from=$events item=event}
						<option value="{$event.eventid}"{if $event.eventid==$room.eventid} selected="selected"{/if}>
							{$event.name}
						</option>
					{/foreach}
				</select>
			</td>
		</tr>
		
	</table>
	
	<p><input type="submit" name="do" value="{$lang.save}" /></p>
</form>