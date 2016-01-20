<div class="headline">{$lang.editcategory}</div>

<form method="post" action="">
	<table width="100%" border="0">
		<tr>
			<td width="150">{$lang.name}:</td>
			<td><input type="text" name="name" value="{$category.name}" style="width:100%;" /></td>
		</tr>
		<tr class="highlight_row">
			<td>{$lang.language}:</td>
			<td>
				<select name="language" style="width:100%;">
					<option value="">-</option>
					{foreach from=$languages item=language}
						<option value="{$language}"{if $language == $category.language} selected="selected"{/if}>{$language}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top">{$lang.visible_for}:</td>
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
								<label for="group_{$group.groupid}">{$group.name}</label>
							</td>
						</tr>
					{/foreach}
				</table>
				<p>{$lang.no_selection_means_all}</p>
			</td>
		</tr>
	</table>
	<p><input type="submit" name="save" value="{$lang.save}" /></p>
</form>