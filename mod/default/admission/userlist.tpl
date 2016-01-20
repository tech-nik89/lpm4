{section name=i loop=$list}
<form action="" method="post">

	 <table width="100%" border="0" cellpadding="5" cellspacing="1">
	 
		<tr bgcolor="{cycle values=',#DDDDDD'}">
			<td width="10%">{$list[i].userid|string_format:"%04d"}</td>
			<td width="15%">{$list[i].nickname}</td>
			<td width="15%">{$list[i].lastname}</td>
			<td width="20%">{$list[i].prename}</td>
			<td width="20%">
				<input type="hidden" name="search" />
				<input type="hidden" name="search_string" value="{$search_string}" />
				{html_options name=paystate options=$paystates selected=$list[i].payed}
				<input type="hidden" name="userid" value="{$list[i].userid}" />
			</td>
			<td><input type="checkbox" name="appeared" {if $list[i].appeared==1}checked="checked"{/if} value="1" /></td>
			<td align="right"><input type="submit" name="save" value="{$lang.save}" /></td>
		</tr>
	 </table>

</form>
{/section}