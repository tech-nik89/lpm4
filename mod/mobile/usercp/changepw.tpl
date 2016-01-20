<div class="headline">{$lang.changepw}</div>
<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<td width="20%">{$lang.password_old}:</td>
			<td><input type="password" name="password_old" id="password_old" /></td>
		</tr>
		<tr>
			<td>{$lang.password_new}:</td>
			<td><input type="password" name="password_new" id="password_new" /></td>
		</tr>
		<tr>
			<td>{$lang.password_new_repeat}:</td>
			<td><input type="password" name="password_new_repeat" id="password_new_repeat" /></td>
		</tr>
	</table>
	<p><input type="submit" name="save" value="{$lang.changepw}" /></p>	
</form>	