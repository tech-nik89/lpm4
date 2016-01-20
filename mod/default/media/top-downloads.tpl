<div class="headline">{$lang.top_downloads}</div>

<table width="100%" border="0">
	{section loop=$top name=i}
		<tr>
			<td valign="top" width="30"><font color="#999999">{$smarty.section.i.rownum}.</font></td>
			<td valign="top"><strong><a href="{$top[i].url}">{$top[i].name}</a></strong><br />
			{$top[i].description}</td>
		</tr>
	{/section}
	
</table>