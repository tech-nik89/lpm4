<div class="headline">{$lang.createcategory}</div>

<form method="post" action="">
	<table width="100%" border="0">
		<tr>
			<td width="150">{$lang.name}:</td>
			<td><input type="text" name="name" style="width:100%;" /></td>
		</tr>
		<tr class="highlight_row">
			<td>{$lang.language}:</td>
			<td>
				<select name="language" style="width:100%;">
					<option value="">-</option>
					{foreach from=$languages item=language}
						<option value="{$language}">{$language}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top">{$lang.visible_for}:</td>
			<td>
				<table width="100%" border="0">
					{section name=i loop=$groups}
						<tr>
							<td width="30">
								<input type="checkbox" name="group_{$groups[i].groupid}" id="group_{$groups[i].groupid}" value="1" />
							</td>
							<td>
								<label for="group_{$groups[i].groupid}">{$groups[i].name}</label>
							</td>
						</tr>
					{/section}
				</table>
				<p>{$lang.no_selection_means_all}</p>
			</td>
		</tr>
		
	</table>
	<p><input type="submit" name="create" value="{$lang.create}" /></p>
</form>