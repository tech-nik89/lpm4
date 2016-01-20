<h1>{$lang.edit_tags}</h1>

<p align="right">
	<a href="ajax_request.php?mod=tagcloud&file=manage.edit.ajax&add" class="aEdit">{$lang.add}</a>
</p>

<table width="100%" border="0">
	<tr>
		<th>{$lang.title}</th>
		<th>{$lang.url}</th>
		<th>{$lang.language}</th>
		<th>{$lang.weight}</th>
		<th>&nbsp;</th>
	</tr>
	{foreach from=$tags item=tag}
		<tr{cycle values=', class="highlight_row"'}>
			<td>{$tag.title}</td>
			<td>
				<a href="{$tag.url}" target="_blank">{$tag.url|truncate:50}</a>
			</td>
			<td>
				{if $tag.language != ''}
					{$tag.language}
				{else}
					-
				{/if}
			</td>
			<td>{$tag.weight - 10}</td>
			<td align="right">
				<a href="ajax_request.php?mod=tagcloud&file=manage.edit.ajax&tagid={$tag.tagid}" class="aEdit">
					<img src="mod/default/tagcloud/edit.png" alt="{$lang.edit}" border="0" />
				</a>
				<a href="{makeurl mod='tagcloud' mode='manage' tagid=$tag.tagid}&delete" onclick="return confirm('{$lang.remove_ask}');">
					<img src="mod/default/tagcloud/delete.png" alt="{$lang.remove}" border="0" />
				</a>
			</td>
		</tr>
	{/foreach}
</table>

<form action="" method="post" onsubmit="return verifySave();">
	<h1>{$lang.headertext}</h1>
	<table width="100%" border="0">
		<tr>
			<td style="width:120px;">
				<strong>{$lang.language}:</strong>
			</td>
			<td>
				<select name="text_language" id="text_language" onchange="languageChange();">
					{foreach from=$languages item=language}
						<option>{$language}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<strong>{$lang.headline}:</strong>
			</td>
			<td>
				<input type="text" name="headertext" id="headertext" value="{$headertext}" style="width:100%;" />
			</td>
		</tr>
	</table>
	<p>{$lang.headertext_descr}</p>
	<p>
		<textarea name="toptext" style="width:100%;" id="textarea_id" rows="8" cols="35">{$toptext}</textarea>
	</p>
	<p>
		<input type="submit" value="{$lang.save}" name="save_preferences" />
	</p>
	
	<h1>{$lang.preferences}</h1>
	<p>
		<label>
			<input type="checkbox" value="1" name="justify"{if $justify == '1'} checked="checked"{/if} />
			{$lang.justify}
		</label>
	</p>
	<p>
		<input type="submit" value="{$lang.save}" name="save_preferences" />
	</p>
</form>

<script type="text/javascript">
	var top_text = '';
	var header_text = '';
	var current_lang = '';
	
	function verifySave() {
		var lng = $("#text_language").val();
		if (lng == '') {
			alert('{$lang.no_lang_selected}');
			return false;
		}
		return true;
	}
	
	function languageChange() {
		if (CKEDITOR.instances.textarea_id.checkDirty()) {
			alert('{$lang.text_has_changed}');
			$("#text_language").val(current_lang);
			return;
		}
		
		var lng = $("#text_language").val();
		current_lang = lng;
		
		$.get('ajax_request.php?mod=tagcloud&file=top-text.ajax&lang='+lng, function(data) {
			top_text = data;
			CKEDITOR.instances.textarea_id.setData(data, function() {
				CKEDITOR.instances.textarea_id.resetDirty();
			});
		});
		
		$.get('ajax_request.php?mod=tagcloud&file=header-text.ajax&lang='+lng, function(data) {
			header_text = data;
			$("#headertext").val(data);
		});
		
	}
	
	CKEDITOR.replace( 'textarea_id', {
		skin : 'office2003',
		{if $fileadmin_installed}filebrowserBrowseUrl : 'ajax_request.php?mod=fileadmin&file=browser.ajax',
		filebrowserWindowWidth : '150',{/if}
		height : '500'
	});
	$(".aEdit").fancybox();
	
</script>