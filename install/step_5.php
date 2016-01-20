<div class="headline">Step 5: Menu</div>
<p>Which of the following menu entries would you like to have in your menu later?</p>

<form action="" method="post">

<table width="100%" border="0" cellspacing="1" cellpadding="5">
	<tr>
		<td width="25"><input name="menu_login" type="checkbox" id="menu_login" value="1" <?php if ($_POST['menu_login'] == '1') echo 'checked'; ?>></td>
		<td><label for="menu_login">Login (recommended)</label></td>
	</tr>
	<tr>
		<td><input name="menu_admin" type="checkbox" id="menu_admin" value="1" <?php if ($_POST['menu_admin'] == '1') echo 'checked'; ?>></td>
		<td><label for="menu_admin">Admin (recommended)</label></td>
	</tr>
	<tr>
		<td><input name="menu_usercp" type="checkbox" id="menu_usercp" value="1" <?php if ($_POST['menu_usercp'] == '1') echo 'checked'; ?>></td>
		<td><label for="menu_usercp">User Center (recommended)</label></td>
	</tr>
	<tr>
		<td><input name="menu_pmbox" type="checkbox" id="menu_pmbox" value="1" <?php if ($_POST['menu_pmbox'] == '1') echo 'checked'; ?>></td>
		<td><label for="menu_pmbox">PM Box (recommended)</label></td>
	</tr>
	<tr>
		<td><input name="menu_guestlist" type="checkbox" id="menu_guestlist" value="1" <?php if ($_POST['menu_guestlist'] == '1') echo 'checked'; ?>></td>
		<td><label for="menu_guestlist">Guestlist</label></td>
	</tr>
</table>

	<p>

		<!-- Database connection -->
		<input type="hidden" name="host" value="<?php echo $_POST['host']; ?>" />
		<input type="hidden" name="user" value="<?php echo $_POST['user']; ?>" />
		<input type="hidden" name="password" value="<?php echo $_POST['password']; ?>" />
		<input type="hidden" name="database" value="<?php echo $_POST['database']; ?>" />
		<input type="hidden" name="table_prefix" value="<?php echo $_POST['table_prefix']; ?>" />
		
		<!-- Admin user -->
		<input type="hidden" name="admin_create" value="<?php echo $_POST['admin_create']; ?>" />
		<input type="hidden" name="admin_nickname" value="<?php echo $_POST['admin_nickname']; ?>" />
		<input type="hidden" name="admin_email" value="<?php echo $_POST['admin_email']; ?>" />
		<input type="hidden" name="admin_password" value="<?php echo $_POST['admin_password']; ?>" />
		
		<!-- Language -->
		<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>" />	
		
		<input type="hidden" name="step" value="5" />
		<input type="submit" name="back" value="&lt; Back" />
		<input type="submit" name="next" value="Next &gt;" />
	</p>

</form>