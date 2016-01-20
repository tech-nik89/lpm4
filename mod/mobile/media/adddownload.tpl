<div class="headline">{$lang.adddownload}</div>

<script type="text/javascript" language="javascript">
	function showFileBrowser(id) {
		var width = 500;
		var height = 500;
		var top = (screen.height / 2) - (height / 2);
		var left = (screen.width / 2) - (width / 2);
		var params = "width="+width+",height="+height+",top="+top+",left="+left+",resizable=yes,scrollbars=yes";
		var url = "ajax_request.php?mod=fileadmin&file=browser.ajax&mode=media&id="+id;
		var w = window.open(url, "Browser", params);
		w.focus();
		return false;
	}
</script>

<form action="" method="post" enctype="multipart/form-data" name="upload">
	
	<fieldset>
		<legend>{$lang.info}</legend>
		<table width="100%" border="0" cellspacing="1" cellpadding="5">
			
			<tr>
				<td width="20%"><strong>{$lang.name}:</strong></td>
			  <td><input type="text" name="name" style="width:100%;" /></td>
			</tr>
			
			<tr>
				<td><strong>{$lang.description}:</strong></td>
				<td><textarea name="description" style="width:100%; height:150px;"></textarea></td>
			</tr>
			
			<tr>
				<td><strong>{$lang.version}:</strong></td>
				<td><input type="text" name="version" style="width:100%;" /></td>
			</tr>
			
			<tr>
				<td><strong>{$lang.release_notes}:</strong></td>
				<td><textarea name="release_notes" style="width:100%; height:150px;"></textarea></td>
			</tr>
			
			<tr>
				<td><strong>{$lang.disable}:</strong></td>
				<td><input type="checkbox" name="disable" value="1" /></td>
			</tr>
			
		</table>
	</fieldset>
	
	<fieldset>
		<legend>{$lang.file}</legend>
		<table width="100%" border="0" cellspacing="1" cellpadding="5">
			<tr>
				<td valign="top" width="20%"><strong>{$lang.upload}:</strong></td>
				<td>
					 <input type="file" name="file" />
				</td>
			</tr>
			
			{if $fileadmin_installed}
				<tr>
					<td><strong>{$lang.browse}:</strong></td>
					<td>
						<input type="text" name="path" value="" id="path" readonly="readonly" />
						<input type="button" name="browse_button" value="{$lang.browse}" onclick="showFileBrowser('path');" />
					</td>
				</tr>
			{/if}
		</table>
	</fieldset>
	
	<fieldset>
		<legend>{$lang.thumbnail}</legend>
		<table width="100%" border="0" cellspacing="1" cellpadding="5">
			<tr>
				<td valign="top" width="20%"><strong>{$lang.thumbnail}:</strong></td>
				<td>
					 <input type="text" name="thumbnail" id="thumbnail" readonly="readonly" />
					 <input type="button" name="browse_button" value="{$lang.browse}" onclick="showFileBrowser('thumbnail');" />
				</td>
			</tr>
		</table>
	</fieldset>
	
  <p><input type="submit" name="add" value="{$lang.add}" /></p>
	
</form>