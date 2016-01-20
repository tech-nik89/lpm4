<div class="headline">Step 7: Summary</div>

<p>Here you see a list of all settings you made in the last steps summarized. Have a last look at it and press <strong>Install</strong>.</p>

<div style="border:1px solid #969696; padding-left:5px;">
<p><strong>Database connection</strong></p>
<p>
	Hostname: <?php echo $_POST['host']; ?><br />
	Username: <?php echo $_POST['user']; ?><br />
	Database: <?php echo $_POST['database']; ?><br />
	Table prefix: <?php echo $_POST['table_prefix']; ?><br />
</p>
<?php if ($_POST['admin_create'] == '1') { ?>
<p><strong>Admin User</strong></p>
<p>
	Nickname: <?php echo $_POST['admin_nickname']; ?><br />
	E-mail adress: <?php echo $_POST['admin_email']; ?><br />
</p>
<?php } ?>
<p><strong>Installed menu entries</strong></p>
<p><ul>
	<?php
		if ($_POST['menu_login'] == '1') echo '<li>Login</li>';
		if ($_POST['menu_admin'] == '1') echo '<li>Admin</li>';
		if ($_POST['menu_usercp'] == '1') echo '<li>User Center</li>';
		if ($_POST['menu_pmbox'] == '1') echo '<li>PM Box</li>';
		if ($_POST['menu_guestlist'] == '1') echo '<li>Guestlist</li>';
	?>
</ul></p>
<p><strong>Language</strong></p>
<p><?php echo $_POST['language']; ?></p>
</div>

<p>&nbsp;</p>

<form action="" method="post">

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
	
	<!-- Menu -->
	<input type="hidden" name="menu_login" value="<?php echo $_POST['menu_login']; ?>" />
	<input type="hidden" name="menu_admin" value="<?php echo $_POST['menu_admin']; ?>" />
	<input type="hidden" name="menu_usercp" value="<?php echo $_POST['menu_usercp']; ?>" />
	<input type="hidden" name="menu_pmbox" value="<?php echo $_POST['menu_pmbox']; ?>" />
	<input type="hidden" name="menu_guestlist" value="<?php echo $_POST['menu_guestlist']; ?>" />	
	
	<!-- Language -->
	<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>" />	
	
	<input type="hidden" name="step" value="7" />
	<input type="submit" name="back" value="&lt; Back" />
	<input type="submit" name="next" value="Install &gt;" />
	
</form>