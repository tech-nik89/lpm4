<div class="headline">{$lang.news_edit}</div>

<form action="" method="post">

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr class="highlight_row">
			<td width="130"><strong>{$lang.news_title}:</strong></td>
			<td><input type="text" name="title" style="width:50%;" value="{$news.title}" /></td>
		</tr>
		<tr>
			<td><strong>{$lang.language}:</strong></td>
			<td>{html_options name=language values=$languages output=$languages selected=$news.language}</td>
		</tr>
		<tr class="highlight_row">
			<td><strong>{$lang.domain}:</strong></td>
			<td>
				<select name="domainid">
					<option value="0">{$lang.all}</option>
					{foreach from=$domains item=domain}
						<option value="{$domain.domainid}"{if $news.domainid == $domain.domainid} selected="selected"{/if}>{$domain.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><textarea style="width:100%; height:350px;" name="text" id="textarea_id">{$news.text}</textarea></td>
		</tr>
	</table>
	
	<p><input type="submit" name="save" value="{$lang.edit}" /></p>

</form>

<!--
<script type="text/javascript">
	CKEDITOR.replace( 'textarea_id', {
		skin : 'office2003'
	});
</script>
-->