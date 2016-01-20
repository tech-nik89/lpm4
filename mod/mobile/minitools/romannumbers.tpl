<script type="text/javascript">
	function deletedec() {
		$("#decn").val("");
	}
	function deleterom() {
		$("#romn").val("");
	}
</script>

<div class="headline">
	{$lang.roman}
</div>

<form action="" enctype="multipart/form-data" method="POST">
	<table>
		<tr>
			<th>
				{$lang.decnumber}:
			</th>
			<td>
				<input name="decnumber" type="number" id="decn" value="{$decnumber}" style="width:100px;" onFocus="deleterom();" />
			</td>
		</tr>
		<tr>
			<th>
				{$lang.romannumber}:
			</th>
			<td>
				<input name="romannumber" type="text" id="romn" value="{$romannumber}" style="width:100px;" onFocus="deletedec();" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="hidden" name="send" value="1" />
				<input type="submit" value="{$lang.numberconvert}" />
			</td>
		</tr>
	</table>
</form>