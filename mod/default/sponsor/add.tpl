<div class="headline">{$action}</div>

<form action="" method="post" enctype="multipart/form-data" name="upload">

	<table width="100%" border="0" cellspacing="1" cellpadding="5">

		<tr>
			<td width="20%"><strong>{$lang.name}:</strong></td>
			<td><input type="text" name="name" value="{$sponsor.name}" /></td>
		</tr>
			
		<tr>
			<td><strong>{$lang.homepage}:</strong></td>
			<td><input type="text" name="homepage" value="{$sponsor.homepage}" /></td>
		</tr>
			
		<tr>
			<td><strong>{$lang.description}:</strong></td>
			<td><input type="text" name="description" style="width:100%;" value="{$sponsor.description}" /></td>
		</tr>
		
		<tr>
			<td><strong>{$lang.image}:</strong></td>
			<td>
				<input type="file" name="file" />
			</td>
		</tr>

	</table>
	
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
	
</form>