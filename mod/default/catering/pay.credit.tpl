<script type="text/javascript">
	function refreshPayCreditUserList() {
		var find = $("#FindUserTextBox").val();
		if (find.length > 1) {
			$("#PayCreditResultsDiv").load('ajax_request.php?mod=catering&file=user.find.ajax&find=' + find);
		}
		else {
			$("#PayCreditResultsDiv").html('');
		}
	}
	
	function payCredit(userid) {
		if (confirm('{$lang.pay_ask}')) {
			$("#CreditUserId").val(userid);
			document.PayCreditForm.submit();
		}
	}
</script>

<div style="width:400px;">
	<form action="" method="post" name="PayCreditForm" id="PayCreditForm">
		<input type="hidden" name="CreditUserId" value="0" id="CreditUserId" />
		<div class="headline">{$lang.pay_credit}</div>

		<p>
			<strong>{$lang.find}:</strong>
			<input type="text" name="FindUserTextBox" id="FindUserTextBox" value="" style="width:300px;" onkeyup="refreshPayCreditUserList();" autocomplete="off" />
		</p>
		
		<div id="PayCreditResultsDiv"></div>
	</form>
</div>