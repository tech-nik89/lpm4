<div class="headline">{$lang.thread_add}</div>

<script type="text/javascript">
	
	function toggleBB() {
		if (!$("#divBBCodes").is(':visible')) {
			$("#divBBCodes").load('templates/default/bbcode.html');
			$("#divBBCodes").show();
		}
		else {
			$("#divBBCodes").hide();
		}
	}
	
</script>

<form method="post" action="">

	<table width="100%" border="0" cellspacing="1" cellpadding="5">

		<tr>
			<td width="20%"><strong>{$lang.thread}:</strong></td>
			<td><input type="text" name="thread" style="width:70%;" /></td>
		</tr>
			
		<tr>
			<td><strong>{$lang.post}:</strong></td>
			<td><textarea style="width:100%; height:150px;" name="post"></textarea></td>
		</tr>

	</table>
	
	<p align="right">
		<a href="javascript:toggleBB();">BB Codes</a>
	</p>
	<p><input type="submit" name="add" value="{$lang.add}" />
	
	<div id="divBBCodes" style="display:none;"></div>
	
</form>