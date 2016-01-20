<div class="headline">{$lang.addpictures}</div>

<form action="" method="post" enctype="multipart/form-data" name="upload">

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		{foreach from=$forms item=form}
			<tr>
				<td>{$lang.file} {$form}:</td>
				<td><input type="file" name="file[]" /></td>
			</tr>
		{/foreach}
	</table>
    <p>
		<input type="submit" name="add" value="{$lang.add}" />
	</p>
    
</form>