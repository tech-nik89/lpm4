<div class="headline">{$lang.write}</div>

{literal}
<script type="text/javascript">
  function contentloader() {
    $("#result").load("ajax_request.php?mod=pmbox&file=userlist&search="+$("#reciever").val());
  }
  function setUser(user) {
	$("#reciever").val(user);
	$("#result").html("");
  }
</script>
{/literal}

<form action="" method="post">
	
	<table width="100%" border="0">
		
		<tr>
			<td width="120" valign="top"><strong>{$lang.reciever}:</strong></td>
			<td>
				<input type="text" name="reciever" style="width:70%" value="{$reciever}" id="reciever" onkeyup="contentloader();" autocomplete="off" />
				<div id="result">
					
				</div>
			</td>
		</tr>
		
		<tr>
			<td><strong>{$lang.subject}:</strong></td>
			<td><input type="text" name="subject" style="width:70%;" value="{$subject}" autocomplete="off" /></td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td><textarea style="width:100%; height:200px;" name="message">{$message}</textarea></td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td><p align="right"><input type="submit" name="send" value="{$lang.send}" /></p></td>
		</tr>
		
	</table>
	

</form>