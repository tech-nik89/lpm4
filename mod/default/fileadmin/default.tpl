<div class="headline">{$lang.fileadmin}</div>

<table width="100%" border="0">
	<tr>
		<td>
			<strong>
				{$lang.folder_structure}
			</strong>
		</td>
		<td>
			<strong>
				{$lang.files}
			</strong>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<fieldset>
				<legend><img src="mod/default/fileadmin/icons/folder_add.png" alt="Create Dir" /> {$lang.create_dir}</legend>
				<form action="" method="post">
					<input type="text" name="create_dir_name" value="" />
					<input type="submit" name="create_dir" value="{$lang.create}" />
				</form>
			</fieldset>
			<fieldset>
				<legend><img src="mod/default/fileadmin/icons/folder_delete.png" alt="Delete" /> {$lang.delete_this_dir}</legend>
				<form action="" method="post">
					<input type="submit" name="delete_dir" value="{$lang.remove}" />
					({$lang.delete_dir_clear})
				</form>
			</fieldset>
		</td>
		<td valign="top">
			<fieldset>
				<legend><img src="mod/default/fileadmin/icons/add.png" alt="Upload File" /> {$lang.upload_file}</legend>
				<form action="" method="post" enctype="multipart/form-data">
					<input type="file" name="file" />
					<input type="submit" name="upload_file" value="{$lang.upload}" /><br />
					<p>
						{$lang.max_size}: <strong>{$max_upload_size}</strong>
					</p>
				</form>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td valign="top" width="50%">
			{include file='../mod/default/fileadmin/directory.tpl' dir=$tree}
		</td>
		<td valign="top">
			{include file='../mod/default/fileadmin/files.tpl' files=$files}
		</td>
	</tr>
</table>