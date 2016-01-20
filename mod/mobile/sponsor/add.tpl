<div class="headline">{$lang.sponsor_add}</div>

<form action="" method="post">

	<table width="100%" border="0" cellspacing="1" cellpadding="5">

		<tr>
			<td width="20%"><strong>{$lang.name}:</strong></td>
			<td><input type="text" name="name" /></td>
		</tr>
			
		<tr>
			<td><strong>{$lang.homepage}:</strong></td>
			<td><input type="text" name="homepage" /></td>
		</tr>
			
		<tr>
			<td><strong>{$lang.description}:</strong></td>
			<td><input type="text" name="description" style="width:100%;" /></td>
		</tr>
		
		<tr>
			<td><strong>{$lang.image}:</strong></td>
			<td>
				<input type="text" name="image" value=""  id="image" />
				{$upload_button}
			</td>
		</tr>

	</table>
	
	<p>
		<input type="submit" name="add" value="{$lang.add}" />
	</p>
	
</form>