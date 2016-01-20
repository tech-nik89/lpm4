<div class="headline">{$lang.smartstudies}</div>

<form action="{$url}" method="POST">
	<table>
		<tr>
			<td>
				{$lang.coursename}:
			</td>
			<td>
				<input type="text" name="smartstudies_coursename" />
			</td>
		</tr>
		<tr>
			<td>
				{$lang.dummypassword}:
			</td>
			<td>
				<input type="text" name="smartstudies_dummypassword" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" name="do" value="{$lang.reset}">
			</td>
		</tr>
	</table>
</form>

