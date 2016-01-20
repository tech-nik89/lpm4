<div class="headline">{$lang.pay_bar}</div>

<form action="" id="PayBarForm" name="PayBarForm" method="post">
	<input type="hidden" name="PayBarFormSubmitted" value="True" />
	<table width="400" cellspacing="7">
		<tr>
			<td><span style="font-size:1.5em;">{$lang.value}:</span></td>
			<td>
				<div id="ValueDiv" style="font-size:1.5em;"></div>
			</td>
		</tr>
		<tr>
			<td><span style="font-size:1.5em;">{$lang.given}:</span></td>
			<td>
				<input type="text" value="000" id="PayValueTextBox" style="width:200px; font-size:1.5em;" onkeyup="calculateRest();" autocomplete="off" />
				<input type="button" value="&lt;" onclick="correctNumblockField();" style="font-size:1.5em;" />
			</td>
		</tr>
		<tr>
			<td><span style="font-size:1.5em;">{$lang.rest}:</span></td>
			<td>
				<div id="RestMoneyDiv" style="font-size:1.5em;"></div>
			</td>
		</tr>
	</table>
	{include file='../mod/default/catering/seller.numblock.tpl' field_id='PayValueTextBox'}
</form>

<script type="text/javascript">
	$("#ValueDiv").html((totalPrice / 100).toFixed(2) + " &euro;");
	var rest = -1;
	
	function calculateRest() {
		var val = parseInt($("#PayValueTextBox").val());
		rest = val - totalPrice;
		var strRest = (rest / 100).toFixed(2) + " &euro;";
		if (rest < 0)
			$("#RestMoneyDiv").html('{$lang.not_enough_bar}');
		else
			$("#RestMoneyDiv").html(strRest);
	}
	
	function numblockSubmitPressed() {
		if (rest < 0) {
			alert('{$lang.not_enough_bar}');
			return;
		}
		document.PayBarForm.submit();
	}

</script>