<table width="100%" border="0">
	<tr>
		<th>{$lang.file}</th>
		<th>{$lang.extension}</th>
		<th>{$lang.size}</th>
		<th>&nbsp;</th>
	</tr>
	{foreach from=$files item=file}
		<tr{cycle values=', class="highlight_row"'}>
			<td>
				<img src="mod/default/fileadmin/icons/document.png" alt="Delete" />
				<a href="{$file.url}" target="_blank">
					{$file.name}
				</a>
			</td>
			<td>{$file.extension}</td>
			<td>{$file.size_str}</td>
			<td align="right" width="20">
				<form action="" method="post">
					<input type="hidden" name="delete_file_filename" value="{$file.name}" />
					<input type="image" src="mod/default/fileadmin/icons/delete.png" alt="Delete" name="delete_file" border="0" style="border:0;" />
				</form>
			</td>
		</tr>
	{/foreach}
</table>