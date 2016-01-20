<div class="headline">{$lang.editdownload}</div>

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

<form action="" method="post">
	<fieldset>
		<legend>{$lang.info}</legend>
		<table width="100%" border="0" cellspacing="1" cellpadding="5">
			
			<tr>
				<td width="20%"><strong>{$lang.name}:</strong></td>
				<td><input type="text" name="name" value="{$download.name}" style="width:100%;" /></td>
			</tr>
			
			<tr>
				<td><strong>{$lang.description}:</strong></td>
				<td><textarea name="description" style="width:100%; height:150px;">{$download.description}</textarea></td>
			</tr>
			
			<tr>
				<td><strong>{$lang.version}:</strong></td>
				<td><input type="text" name="version" value="{$download.version}" style="width:100%;" /></td>
			</tr>
			
			<tr>
				<td><strong>{$lang.release_notes}:</strong></td>
				<td><textarea name="release_notes" style="width:100%; height:150px;">{$download.release_notes}</textarea></td>
			</tr>
			
			<tr>
				<td><strong>{$lang.disable}:</strong></td>
				<td><input type="checkbox" name="disable" value="1"{if $download.disabled == 1} checked="checked"{/if} /></td>
			</tr>
			
		</table>
	</fieldset>
		
	<fieldset>
		<legend>{$lang.file}</legend>
		<table width="100%" border="0" cellspacing="1" cellpadding="5">
			{if $fileadmin_installed}
				<tr>
					<td valign="top"><strong>{$lang.file}:</strong></td>
					<td>
						<input type="text" name="path" id="path" value="{$download.file}" />
						<input type="button" name="browser_button" onclick="showFileBrowser()" value="{$lang.browse}" />
					</td>
				</tr>
			{else}
				<tr>
					<td valign="top"><strong>{$lang.file}:</strong></td>
					<td>{$download.file}</td>
				</tr>
			{/if}
			
			<tr>
				<td><strong>{$lang.category}:</strong></td>
				<td>
					<select name="categoryid" id="categoryid" style="width:100%;">
						{section name=i loop=$categories}
								{if $categories[i].categoryid == $download.categoryid}
										<option value="{$categories[i].categoryid}" selected>{$categories[i].name}</option>
								{else}
										<option value="{$categories[i].categoryid}">{$categories[i].name}</option>
								{/if}
						{/section}
					</select>
				</td>
			</tr>
			
		</table>
	</fieldset>
	
	<fieldset>
		<legend>{$lang.thumbnail}</legend>
		<table width="100%" border="0" cellspacing="1" cellpadding="5">
			<tr>
				<td valign="top" width="20%"><strong>{$lang.thumbnail}:</strong></td>
				<td>
					 <input type="text" name="thumbnail" id="thumbnail" readonly="readonly" value="{$download.thumbnail}" />
					 <input type="button" name="browse_button" value="{$lang.browse}" onclick="showFileBrowser('thumbnail');" />
				</td>
			</tr>
		</table>
	</fieldset>
	
  <p><input type="submit" name="save" value="{$lang.save}" /></p>
	
</form>