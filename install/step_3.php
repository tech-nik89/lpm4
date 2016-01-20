<div class="headline">Step 3: Database</div>
<p>Please enter here the access data for your MySQL database.</p>

<script type="text/javascript">
  function contentloader() {
    $("#result").load("install/mysql_connection_check.php?host="+$("#host").val()+"&user="+$("#user").val()+"&password="+$("#password").val()+"&db="+$("#database").val());
  }
</script>

<form action="" method="post">

<table width="100%" border="0" cellspacing="1" cellpadding="5">
	<tr>
		<td width="200"><strong>Hostname</strong></td>
		<td><input name="host" type="text" id="host" value="<?php if ($_POST['host'] == '') echo 'localhost'; else echo $_POST['host']; ?>"></td>
	</tr>
	<tr>
		<td><strong>Username</strong></td>
		<td><input type="text" name="user" id="user" value="<?php echo $_POST['user']; ?>"></td>
	</tr>
	<tr>
		<td><strong>Password</strong></td>
		<td><input type="password" name="password" id="password" value="<?php echo $_POST['password']; ?>"></td>
	</tr>
	<tr>
		<td><strong>Database</strong></td>
		<td><input type="text" name="database" id="database" value="<?php echo $_POST['database']; ?>"></td>
	</tr>
	<tr>
		<td><strong>Table Prefix</strong></td>
		<td><input name="table_prefix" type="text" id="table_prefix" value="<?php if ($_POST['table_prefix'] == '') echo 'hfh_'; else echo $_POST['table_prefix']; ?>"></td>
	</tr>
</table>

<input type="button" name="connection_check" value="Check database connection now" onclick="javascript:contentloader();" />

<div id="result" style="padding:4px;">
	&nbsp;
</div>

<p>&nbsp;</p>

<p>
	<!-- Admin user -->
	<input type="hidden" name="admin_create" value="<?php echo $_POST['admin_create']; ?>" />
	<input type="hidden" name="admin_nickname" value="<?php echo $_POST['admin_nickname']; ?>" />
	<input type="hidden" name="admin_email" value="<?php echo $_POST['admin_email']; ?>" />
	<input type="hidden" name="admin_password" value="<?php echo $_POST['admin_password']; ?>" />
	
	<!-- Menu -->
	<input type="hidden" name="menu_login" value="<?php echo $_POST['menu_login']; ?>" />
	<input type="hidden" name="menu_admin" value="<?php echo $_POST['menu_admin']; ?>" />
	<input type="hidden" name="menu_usercp" value="<?php echo $_POST['menu_usercp']; ?>" />
	<input type="hidden" name="menu_pmbox" value="<?php echo $_POST['menu_pmbox']; ?>" />
	<input type="hidden" name="menu_guestlist" value="<?php echo $_POST['menu_guestlist']; ?>" />
	
	<!-- Language -->
	<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>" />	
	
	<input type="hidden" name="step" value="3" />
	<input type="submit" name="back" value="&lt; Back" />
	<input type="submit" name="next" value="Next &gt;" id="next" disabled />
</p>

</form>