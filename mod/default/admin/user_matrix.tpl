<div class="headline">{$lang.user_matrix}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	{foreach from=$matrix item=y}
		<tr{cycle values=', class="highlight_row"'}>
			{foreach from=$y item=x}
				<td>
					{$x}
				</td>
			{/foreach}
		</tr>
	{/foreach}
	
</table>