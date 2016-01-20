<div class="headline">{$lang.records}</div>

<table width="100%" border="0">
	<tr>
		<th>{$lang.timestamp}</th>
	</tr>
	{foreach from=$records item=record}
		<tr>
			<td>
				<a href="{$record.url}">{$record.timestamp|date_format}</a>
			</td>
		</tr>
	{/foreach}
</table>