<div class="headline">{$lang.content}</div>

<form action="" method="post">
	{if $action == 'add'}
		<fieldset>
			<legend>Key</legend>
			<p>
				{$lang.content_key_descr}
			</p>
			<table width="100%" border="0">
				<tr>
					<td width="80">
						<strong>Key:</strong>
					</td>
					<td>
						<input type="text" name="key" value="{$content.key}" style="width:100%;"{if $content.key != ''} readonly="readonly"{/if} />
					</td>
				</tr>
			</table>
		</fieldset>
	{/if}
	<fieldset>
		<legend>{$lang.options}</legend>
		<table width="100%" border="0">
			{if $action == 'edit'}
				<tr>
					<td>
						{$lang.versions}:
					</td>
					<td>
						<select name="version" style="width:100%;" onchange="location.href='{makeurl mod='admin' mode='content' action='edit' key=$content.key}&amp;version='+this.options[this.selectedIndex].value">
							{foreach from=$content.versions item=version}
								<option value="{$version.version}"{if $version.version == $selected_version} selected="selected"{/if}>
									{$lang.version} {$version.version + 1} {if $version.version_timestamp > 0}({$version.version_timestamp|date_format:"%d.%m.%Y - %R"}){/if}
								</option>
							{/foreach}
						</select>
					</td>
					<td width="150" align="right">
						<a href="ajax_request.php?mod=admin&amp;file=manage_versions.ajax&amp;key={$content.key}" id="manage_versions">{$lang.versions_manage}</a>
					</td>
				</tr>
			{/if}
			<tr>
				<td width="100">
					{$lang.title}:
				</td>
				<td colspan="2">
					<input type="text" name="title" value="{$content.title}" style="width:100%;" />
				</td>
			</tr>
			<tr>
				<td valign="top">{$lang.assigned_group}:</td>
				<td>
					<table width="100%" border="0">
						{foreach from=$groups item=group}
							<tr>
								<td width="30">
									<input type="checkbox" name="group_{$group.groupid}"
										id="group_{$group.groupid}" value="1"
									{if $group.groupid|in_array:$permissions} checked="checked"{/if}/>
								</td>
								<td>
									<label for="group_{$group.groupid}" style="cursor:pointer;">{$group.name}</label>
								</td>
							</tr>
						{/foreach}
					</table>
					<p>{$lang.no_selection_means_all}</p>
				</td>
			</tr>
		</table>
	</fieldset>
	<p>
		<textarea id="textarea_id" name="text" style="width:100%; height:600px; overflow:scroll; overflow-x:scroll; overflow-y:scroll;">{$content.text}</textarea>
	</p>
	<p>
		<strong>
			<a href="javascript:void(0);" onclick="$('#pBoxContent').toggle();">{$lang.additional_box_content}</a>
		</strong>
	</p>
	<p id="pBoxContent"{if $content.box_content == ''} style="display:none;"{/if}>
		<textarea id="box_content_id" name="box_content" style="width:100%; height:400px; overflow:scroll; overflow-x:scroll; overflow-y:scroll;">{$content.box_content}</textarea>
	</p>	
	<p>
		<input type="submit" name="submit" value="{$lang.save}"{if $locked} disabled="disabled"{/if} />
	</p>

</form>

<script type="text/javascript">
	CKEDITOR.replace( 'textarea_id', {
		skin : 'office2003',
		{if $fileadmin_installed}filebrowserBrowseUrl : 'ajax_request.php?mod=fileadmin&file=browser.ajax',
		filebrowserWindowWidth : '150',{/if}
		height : '500'
	});
	CKEDITOR.replace( 'box_content_id', {
		skin : 'office2003',
		{if $fileadmin_installed}filebrowserBrowseUrl : 'ajax_request.php?mod=fileadmin&file=browser.ajax',
		filebrowserWindowWidth : '150',{/if}
		height : '500'
	});
	$("#manage_versions").fancybox();
</script>