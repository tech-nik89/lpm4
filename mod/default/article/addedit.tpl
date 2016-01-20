<div class="headline">{$lang.create_edit}</div>

<form action="" method="post">
	<table width="100%" border="0">
		<tr>
			<td>
				{$lang.published}:
			</td>
			<td>
				<input type="checkbox" name="published" value="1"{if $article.published} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<td width="100">
				{$lang.category}:
			</td>
			<td>
				<select name="categoryid">
					{foreach from=$categories item=category}
						<optgroup label="{$category.title}">
							{foreach from=$category.subcategories item=subcategory}
								<option value="{$subcategory.categoryid}"{if $article.categoryid == $subcategory.categoryid} selected="selected"{/if}>{$subcategory.title}</option>
							{/foreach}
						</optgroup>
					{/foreach}
				</select>
			</td>
		<tr>
		<tr>
			<td>
				{$lang.headline}:
			</td>
			<td>
				<input type="text" name="title" value="{$article.title}" style="width:100%;" />
			</td>
		<tr>
		<tr>
			<td valign="top">{$lang.preview}:</td>
			<td>
				<textarea name="preview" style="width:100%; height:50px;">{$article.preview}</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea name="text" style="width:100%; height:300px;">{$article.text}</textarea>
			</td>
		</tr>
	</table>
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
</form>