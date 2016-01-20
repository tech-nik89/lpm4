<div class="headline">{$lang.versions_manage}</div>
<table width="700" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<th>{$lang.version}</th>
		<th>{$lang.title}</th>
		<th>{$lang.timestamp}</th>
		<th>{$lang.author}</th>
		<th>{$lang.options}</th>
	</tr>
	{foreach from=$content.versions item=version}
		<tr{cycle values=', class="highlight_row"'}>
			<td>
            	<a href="{makeurl mod='admin' mode='content' action='edit' key=$content.key version=$version.version}">{$lang.version} {$version.version + 1}</a>
            </td>
			<td>
				{$version.title}
			</td>
			<td>
				{if $version.version_timestamp > 0}{$version.version_timestamp|date_format:"%d.%m.%Y - %R"}{else}-{/if}
			</td>
			<td>
				{if $version.author != ''}{$version.author}{else}-{/if}
			</td>
			<td>
				{if count($content.versions) > 1}
					<a href="ajax_request.php?mod=admin&amp;file=manage_versions.ajax&amp;key={$content.key}&amp;version={$version.version}" class="deleteUrls">
				{/if}
				{$lang.delete}
				{if count($content.versions) > 1}
					</a>
				{/if}
			</td>
		</tr>
	{/foreach}
</table>

<script type="text/javascript" language="javascript">
	$(".deleteUrls").fancybox();
</script>