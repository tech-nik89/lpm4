<div class="headline">{$lang.company}</div>

<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr class="highlight_row">
			<td width="20%">{$lang.company}:</td>
			<td><input type="text" name="company" value="{$usr.company}" style="width:100%;" /></td>
		</tr>
		
		<tr>
			<td valign="top">{$lang.address}:</td>
			<td><textarea name="address" style="width:100%; height:80px;">{$usr.address}</textarea></td>
		</tr>
	</table>
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
</form>