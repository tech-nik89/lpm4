<div class="headline">{$lang.new_downloads}</div>

<table width="100%" border="0">
	{section loop=$new name=i}
		<tr>
			<td valign="top" width="30"><font color="#999999">{$smarty.section.i.rownum}.</font></td>
			<td valign="top"><strong><a href="{$new[i].url}">{$new[i].name}</a></strong><br />
			{$new[i].time}		
			</td>
		</tr>
	{/section}
	
</table>