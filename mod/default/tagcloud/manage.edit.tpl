<h1>{$lang.tag}</h1>

<script type="text/javascript" language="javascript">
	function showFileBrowser(id) {
		var width = 500;
		var height = 500;
		var top = (screen.height / 2) - (height / 2);
		var left = (screen.width / 2) - (width / 2);
		var params = "width="+width+",height="+height+",top="+top+",left="+left+",resizable=yes,scrollbars=yes";
		var url = "ajax_request.php?mod=fileadmin&file=browser.ajax&mode=id&id="+id;
		var w = window.open(url, "Browser", params);
		w.focus();
		return false;
	}
</script>

<form action="" method="post">
	<table width="400" border="0">
		<tr class="highlight_row">
			<td width="120">{$lang.title}:</td>
			<td><input type="text" name="title" style="width:90%;" value="{$tag.title}" /></td>
		</tr>
		<tr>
			<td>{$lang.url}:</td>
			<td>
				<input type="text" name="url" style="width:50%;" id="tag_url" value="{$tag.url}" />
				<input type="button" name="browse" value="{$lang.browse}" onclick="showFileBrowser('tag_url');" />
			</td>
		</tr>
		<tr class="highlight_row">
			<td>{$lang.weight}:</td>
			<td>
				<input type="text" name="weight" style="width:50%;" value="{if $tag.weight > 10}{$tag.weight - 10}{/if}" />
				(0 - 100)
			</td>
		</tr>
		<tr>
			<td>{$lang.language}:</td>
			<td>
				{html_options name=language values=$languages output=$languages selected=$tag.language}
			</td>
		</tr>
		<tr class="highlight_row">
			<td>{$lang.domain}:</td>
			<td>
				<select name="domainid" style="width:90%;">
					<option value="0">{$lang.all}</option>
					{foreach from=$domains item=domain}
						<option value="{$domain.domainid}"{if $domain.domainid == $tag.domainid} selected="selected"{/if}>{$domain.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	</table>
	<p>
		<input type="hidden" name="action" value="{$action}" />
		<input type="hidden" name="tagid" value="{$tagid}" />
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
</form>