<div class="headline">
	{$lang.minitools}
</div>

{if !empty($minitools)}
	<table>
		{foreach from=$minitools item=minitool}
			<tr>
				<td>
					{$minitool.url}
				</td>
			</th>
		{/foreach}
	</table>
{/if}
