<div class="headline">{$lang.project}</div>

<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<td width="100">{$lang.name}</td>
			<td><input type="text" name="name" value="{$project.name}" /></td>
		</tr>
		<tr>
			<td>{$lang.description}</td>
			<td><input type="text" name="description" value="{$project.description}" style="width:100%;" /></td>
		</tr>
	</table>
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
</form>