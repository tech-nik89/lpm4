<div class="headline">{$lang.editmovie}</div>

<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
		<tr>
			<td>{$lang.name}:</td>
			<td><input type="text" name="name" value="{$movie.name}" /></td>
		</tr>
		
		<tr>
			<td>{$lang.file}:</td>
			<td>
				<input type="text" name="file" value="{$movie.file}" /><br />
				{$lang.adddownload_wheretoput} <strong>media/movie/</strong>
			</td>
		</tr>
		
		<tr>
			<td>{$lang.description}:</td>
			<td><textarea name="description" style="width:100%; height:150px;">{$movie.description}</textarea></td>
		</tr>
		
	</table>
	
	<p><input type="submit" name="save" value="{$lang.save}" /></p>
	
</form>