<div class="headline">{$lang.addmovie}</div>

<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
		<tr>
			<td>{$lang.name}:</td>
			<td><input type="text" name="name" /></td>
		</tr>
		
		<tr>
			<td>{$lang.file}:</td>
			<td>
				<select name="file">
					{section name=i loop=$files}
						<option value="{$files[i]}">{$files[i]}</option>
					{/section}
				</select>
				<br />
				{$lang.adddownload_wheretoput} <strong>media/movie/</strong>
			</td>
		</tr>
		
		<tr>
			<td>{$lang.description}:</td>
			<td><textarea name="description" style="width:100%; height:150px;"></textarea></td>
		</tr>
		
	</table>
	
	<p><input type="submit" name="add" value="{$lang.add}" /></p>
	
</form>