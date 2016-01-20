<div class="headline">{$lang.categories}</div>

<form action="" method="post">
	<table width="100%" border="0" cellspacing="0">
		<tr>
			<th>ID</th>
			<th>{$lang.parent}</th>
			<th>{$lang.name}</th>
			<th>{$lang.description}</th>
			<th>{$lang.delete}</th>
		</tr>
		{foreach from=$categories item=category}
			<tr class="highlight_row">
				<td>{$category.categoryid}</td>
				<td align="center">
					<input type="text" name="parent_{$category.categoryid}" value="{$category.parentid}" style="width:35px;" />
				</td>
				<td>
					<input type="text" name="title_{$category.categoryid}" value="{$category.title}" style="width:100%;" />
				</td>
				<td>
					<input type="text" name="description_{$category.categoryid}" value="{$category.description}" style="width:100%;" />
				</td>
				<td align="center">
					<input type="checkbox" name="delete_{$category.categoryid}" value="1" />
				</td>
			</tr>
			{foreach from=$category.subcategories item=subcategory}
				<tr>
					<td>{$subcategory.categoryid}</td>
					<td align="center">
						<input type="text" name="parent_{$subcategory.categoryid}" value="{$subcategory.parentid}" style="width:35px;" />
					</td>
					<td>
						<input type="text" name="title_{$subcategory.categoryid}" value="{$subcategory.title}" style="width:100%;" />
					</td>
					<td>
						<input type="text" name="description_{$subcategory.categoryid}" value="{$subcategory.description}" style="width:100%;" />
					</td>
					<td align="center">
						<input type="checkbox" name="delete_{$subcategory.categoryid}" value="1" />
					</td>
				</tr>
				
			{/foreach}
		{/foreach}
		<tr class="highlight_row">
			<td>{$lang.new}</td>
			<td align="center">
				<input type="text" name="parent_new" value="0" style="width:35px;" />
			</td>
			<td>
				<input type="text" name="title_new" value="" style="width:100%;" />
			</td>
			<td>
				<input type="text" name="description_new" value="" style="width:100%;" />
			</td>
			<td align="center">
				&nbsp;
			</td>
		</tr>
	</table>
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
</form>