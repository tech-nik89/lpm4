<div class="headline">{$lang.account}</div>
<form action="" method="post">
	<table width="100%" border="0">
		<tr>
			<td width="20%"><strong>User-ID:</strong></td>
			<td>{$user.userid}</td>
			<td rowspan="6" align="right" valign="top">{$avatar}</td>
		</tr>
		<tr>
			<td width="20%"><strong>{$lang.email}:</strong></td>
			<td>{$user.email}</td>
		</tr>
		<tr>
			<td><strong>{$lang.nickname}:</strong></td>
			<td>{$user.nickname}</td>
		</tr>
		<tr>
			<td><strong>{$lang.prename}:</strong></td>
			<td><input type="text" name="prename" value="{$user.prename}" /></td>
		</tr>
		<tr>
			<td><strong>{$lang.lastname}:</strong></td>
			<td><input type="text" name="lastname" value="{$user.lastname}" /></td>
		</tr>
		<tr>
			<td><strong>{$lang.birthday}:</strong></td>
			<td>{html_select_date start_year='-70' end_year='-8' time=$user.birthday}</td>
		</tr>
	</table>
	<p><input type="submit" name="save" value="{$lang.save}" /></p>
</form>