<div class="headline">Step 9: User import</div>
<p>Here you can import users from an old LPM 3 installation.</p>

<script type="text/javascript">
  function listusers() {
    $("#import").load("install/lpm3_listusers.php?host="+$("#host").val()+"&user="+$("#user").val()+"&password="+$("#password").val()+"&db="+$("#database").val());
  }
  function import() {
	  $("#import").load("install/lpm3_import.php?host="+$("#host").val()+"&user="+$("#user").val()+"&password="+$("#password").val()+"&db="+$("#database").val());
	  $("#next").val("Next >");
  }
</script>

<div id="import">
<table width="100%" border="0" cellspacing="1" cellpadding="5">
	<tr>
		<td width="200"><strong>Hostname</strong></td>
		<td><input name="host" type="text" id="host" value="localhost"></td>
	</tr>
	<tr>
		<td><strong>Username</strong></td>
		<td><input type="text" name="user" id="user"></td>
	</tr>
	<tr>
		<td><strong>Password</strong></td>
		<td><input type="password" name="password" id="password"></td>
	</tr>
	<tr>
		<td><strong>Database</strong></td>
		<td><input type="text" name="database" id="database"></td>
	</tr>
</table>
<p>
	<input type="button" name="listusers" value="List users" onClick="javascript:listusers();" />
</p>
</div>

<form action="" method="post">
	
	<p>
	<input type="hidden" name="step" value="9" />
	<input type="submit" name="back" value="&lt; Back" disabled />
	<input type="submit" name="next" id="next" value="Skip &gt;" />
	</p>
</form>