<script type="text/javascript">
	var numblockValue = '';
	var numblockFieldId = '{$field_id}';
	
	function numblockButtonPressed(val) {
		numblockValue += val;
		refreshNumblockField();
	}
	
	function correctNumblockField() {
		if (numblockValue.length > 0) {
			numblockValue = numblockValue.substr(0, numblockValue.length - 1);
			refreshNumblockField();
		}
	}
	
	function refreshNumblockField() {
		$("#"+numblockFieldId).val(numblockValue);
		calculateRest();
	}
</script>

<div align="center">
	<table width="" border="0">
		<tr>
			<td align="center">
				<input type="button" name="number_1" value="1" class="numblock_button" onclick="numblockButtonPressed('1');" />
			</td>
			<td align="center">
				<input type="button" name="number_2" value="2" class="numblock_button" onclick="numblockButtonPressed('2');" />
			</td>
			<td align="center">
				<input type="button" name="number_3" value="3" class="numblock_button" onclick="numblockButtonPressed('3');" />
			</td>
		</tr>
		<tr>
			<td align="center">
				<input type="button" name="number_4" value="4" class="numblock_button" onclick="numblockButtonPressed('4');" />
			</td>
			<td align="center">
				<input type="button" name="number_5" value="5" class="numblock_button" onclick="numblockButtonPressed('5');" />
			</td>
			<td align="center">
				<input type="button" name="number_6" value="6" class="numblock_button" onclick="numblockButtonPressed('6');" />
			</td>
		</tr>
		<tr>
			<td align="center">
				<input type="button" name="number_7" value="7" class="numblock_button" onclick="numblockButtonPressed('7');" />
			</td>
			<td align="center">
				<input type="button" name="number_8" value="8" class="numblock_button" onclick="numblockButtonPressed('8');" />
			</td>
			<td align="center">
				<input type="button" name="number_9" value="9" class="numblock_button" onclick="numblockButtonPressed('9');" />
			</td>
		</tr>
		<tr>
			<td align="center">
				<input type="button" name="number_00" value="00" class="numblock_button" onclick="numblockButtonPressed('00');" />
			</td>
			<td align="center">
				<input type="button" name="number_0" value="0" class="numblock_button" onclick="numblockButtonPressed('0');" />
			</td>
			<td align="center">
				<input type="button" name="numblock_go" value="{$lang.go}" class="numblock_button" onclick="numblockSubmitPressed();" />
			</td>
		</tr>
	</table>
</div>