<div class="headline">Add Config Key</div>

<form action="" method="post">
	<table width="100%" border="0">
		<tr>
			<td width="100">Mod:</td>
			<td><input type="text" name="key_mod" /></td>
		</tr>
		<tr>
			<td>Key:</td>
			<td><input type="text" name="key_key" /></td>
		</tr>
		<tr>
			<td>Type:</td>
			<td>
				<select name="key_type">
					<option>bool</option>
					<option>string</option>
					<option>text</option>
					<option>int</option>
					<option>list</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Value:</td>
			<td><input type="text" name="key_value" />
		</tr>
		<tr>
			<td>Description:</td>
			<td><input type="text" name="key_descr" />
		</tr>
	</table>
	<p>
		<input type="submit" name="submit" value="Save" />
	</p>
</form>