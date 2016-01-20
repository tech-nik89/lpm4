<div class="headline">{$lang.content}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
    <tr>
        <th>
            {$lang.content}
        </th>
		<th>
			Key
		</th>
		<th>
			{$lang.versions}
		</th>
        <th colspan="2">
            {$lang.options}
        </th>
    </tr>
    {section name=i loop=$content}
        <tr{cycle values=', class="highlight_row"'}>
        	<td>
				<a href="{$content[i].url}">{$content[i].title}</a>
			</td>
			<td>
				{$content[i].k}
			</td>
			<td>
				{$content[i].version_count}
			</td>
			<td width="100">
					<a href="{$content[i].edit_url}">{$lang.edit}</a>
			</td>
			<td width="100">
					<a href="{$content[i].remove_url}">{$lang.remove}</a>
			</td>
        </tr>
    {/section}
</table>