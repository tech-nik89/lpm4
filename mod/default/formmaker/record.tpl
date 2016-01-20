<div class="headline">{$lang.record}</div>

<table width="100%" border="0">
	<tr>
		<td width="150">{$lang.timestamp}:</td>
		<td>{$record.timestamp|date_format}</td>
	</tr>
	<tr class="highlight_row">
		<td>{$lang.ipaddress}:</td>
		<td>{$record.ipaddress}</td>
	</tr>
	<tr>
		<td valign="top">{$lang.content}:</td>
		<td>{$record.content}</td>
	</td>
</table>
<form action="" method="post">
	<p>
		<input type="submit" name="delete" value="{$lang.delete}" onclick="return confirm('{$lang.delete_record_ask}');" />
	</p>
</form>