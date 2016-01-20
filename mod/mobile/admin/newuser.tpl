<div class="headline">{$lang.user_add}</div>

<form action="" method="post">

	<table width="100%" border="0" cellspacing="1" cellpadding="5">
		
		<tr>
			<td width="20%"><strong>{$lang.nickname}:</strong></td>
			<td><input type="text" name="nickname" value="" style="width:100%;" /></td>
		</tr>
		
		<tr class="highlight_row">
			<td><strong>{$lang.lastname}:</strong></td>
			<td><input type="text" name="lastname" value="" style="width:100%;" /></td>
		</tr>
		
		<tr>
			<td><strong>{$lang.prename}:</strong></td>
			<td><input type="text" name="prename" value="" style="width:100%;" /></td>
		</tr>
		
		<tr class="highlight_row">
			<td><strong>{$lang.birthday}:</strong></td>
			<td>{html_select_date start_year='-70' end_year='-10' time=$birthday}</td>
		</tr>
		
		<tr>
			<td><strong>{$lang.email}:</strong></td>
			<td><input type="text" name="email" value="" style="width:100%;" /></td>
		</tr>
		
		<tr class="highlight_row">
			<td><strong>{$lang.password}:</strong></td>
			<td><input type="text" name="password" value="{$password}" style="width:100%;" /></td>
		</tr>
		
	</table>
	
	<p><input type="submit" name="create" value="{$lang.user_add}" /></p>

</form>