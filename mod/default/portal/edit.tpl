<div class="headline">{$lang.edit_portal}</div>

<form action="" method="post">

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<th width="25">&nbsp;</th>
			<th>{$lang.mod}</th>
		</tr>
		
		<tr>
			<td><input type="checkbox" name="topnews" value="1"{if $cfg.topnews == "1"} checked="checked"{/if} /></td>
			<td>{$lang.topnews}</td>
		</tr>
		
		<tr>
			<td><input type="checkbox" name="news" value="1"{if $cfg.news == "1"} checked="checked"{/if} /></td>
			<td>{$lang.news}</td>
		</tr>
		
		<tr>
			<td><input type="checkbox" name="posts" value="1"{if $cfg.posts == "1"} checked="checked"{/if} /></td>
			<td>{$lang.posts}</td>
		</tr>
		
		<tr>
			<td><input type="checkbox" name="poll" value="1"{if $cfg.poll == "1"} checked="checked"{/if} /></td>
			<td>{$lang.poll}</td>
		</tr>
		
		<tr>
			<td><input type="checkbox" name="calendar" value="1"{if $cfg.calendar == "1"} checked="checked"{/if} /></td>
			<td>{$lang.calendar}</td>
		</tr>
		
		<tr>
			<td><input type="checkbox" name="article" value="1"{if $cfg.article == "1"} checked="checked"{/if} /></td>
			<td>{$lang.article}</td>
		</tr>
		
	</table>
	
	<p><input type="submit" name="save" value="{$lang.save}" /></p>
	
</form>