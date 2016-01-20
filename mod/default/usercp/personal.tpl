<div class="headline">{$lang.personal}</div>
<form acton="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		{section name=i loop=$list}
			<tr>
				<td width="20%">{$list[i].name}:</td>	
				<td><input type="text" name="value_{$list[i].fieldid}" value="{$list[i].value|escape:"html"}" style="width:100%;" /></td>
			</tr>
		{/section}			
	</table>
	<p><input type="submit" name="save" value="{$lang.save}" /></p>
</form>