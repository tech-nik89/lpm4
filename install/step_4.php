<div class="headline">Step 4: Admin User</div>
<p>You have to set up an administration user.</p>

<form action="" method="post">

<p>
	<label>
		<input type="checkbox" name="admin_create" id="admin_create" value="1" <?php if ($_POST['admin_create'] == '1') echo 'checked'; ?> />
		Create an admin user. (recommended)
	</label>
</p>

<table width="100%" border="0" cellspacing="1" cellpadding="5">
	<tr>
		<td width="200"><strong>Nickname</strong></td>
		<td><input type="text" name="admin_nickname" id="admin_nickname" value="<?php if ($_POST['admin_nickname'] == '') echo 'admin'; else echo $_POST['admin_nickname']; ?>"></td>
	</tr>
	<tr>
		<td><strong>E-mail adress</strong></td>
		<td><input type="text" name="admin_email" id="admin_email" value="<?php echo $_POST['admin_email']; ?>"></td>
	</tr>
	<tr>
		<td><strong>Password</strong></td>
		<td><input type="password" name="admin_password" id="admin_password" value="<?php echo $_POST['admin_password']; ?>"></td>
	</tr>
</table>

<p>

	<!-- Database connection -->
	<input type="hidden" name="host" value="<?php echo $_POST['host']; ?>" />
	<input type="hidden" name="user" value="<?php echo $_POST['user']; ?>" />
	<input type="hidden" name="password" value="<?php echo $_POST['password']; ?>" />
	<input type="hidden" name="database" value="<?php echo $_POST['database']; ?>" />
	<input type="hidden" name="table_prefix" value="<?php echo $_POST['table_prefix']; ?>" />
	
	<!-- Menu -->
	<input type="hidden" name="menu_login" value="<?php echo $_POST['menu_login']; ?>" />
	<input type="hidden" name="menu_admin" value="<?php echo $_POST['menu_admin']; ?>" />
	<input type="hidden" name="menu_usercp" value="<?php echo $_POST['menu_usercp']; ?>" />
	<input type="hidden" name="menu_pmbox" value="<?php echo $_POST['menu_pmbox']; ?>" />
	<input type="hidden" name="menu_guestlist" value="<?php echo $_POST['menu_guestlist']; ?>" />	
	
	<!-- Language -->
	<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>" />	
	
	<input type="hidden" name="step" value="4" />
	<input type="submit" name="back" value="&lt; Back" />
	<input type="submit" name="next" value="Next &gt;" />
</p>

</form>