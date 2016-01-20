<div class="headline">{$headline}</div>

<a href="ajax_request.php?mod=calendar&file=categories_edit" class="fancy_edit_categories">{$lang.add}</a>

<table width="100%" border="0" cellspacing="1" cellpadding="5">
	<tr	>
		<th>
			{$lang.title}
		</th>
		<th>
			{$lang.description}
		</th>
		<th>
			{$lang.edit}
		</th>
	</tr>
	{foreach from=$categories item=category}
		<tr style="background-color:{$category.backgroundcolor}; color:{$category.fontcolor}">
			<td>
				{$category.title}
			</td>
			<td>{$category.description}</td>
			<td>
				<a href="ajax_request.php?mod=calendar&file=categories_edit&categoryId={$category.categoryId}" class="fancy_edit_categories">
					#
				</a>
			</td>
		</tr>
	{/foreach}
</table>

<script type="text/javascript">
	$(".fancy_edit_categories").fancybox();
</script>