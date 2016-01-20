<div class="headline">Step 1: Welcome</div>

<p>This is the setup procedure to prepare the database for use with the LAN Party Manager IV - Higher For Hire.</p>
<p>The installation is very easy, just follow this few steps. Needed is a MySQL database and a webserver with php support.</p>

<p>&nbsp;</p>
<p>&nbsp;</p>

<p>
	<form action="" method="post">
	
		<!-- Database connection -->
		<input type="hidden" name="host" value="<?php echo $_POST['host']; ?>" />
		<input type="hidden" name="user" value="<?php echo $_POST['user']; ?>" />
		<input type="hidden" name="password" value="<?php echo $_POST['password']; ?>" />
		<input type="hidden" name="database" value="<?php echo $_POST['database']; ?>" />
		<input type="hidden" name="table_prefix" value="<?php echo $_POST['table_prefix']; ?>" />
		
		<!-- Admin user -->
		<input type="hidden" name="admin_create" value="1" />
		<input type="hidden" name="admin_nickname" value="<?php echo $_POST['admin_nickname']; ?>" />
		<input type="hidden" name="admin_email" value="<?php echo $_POST['admin_email']; ?>" />
		<input type="hidden" name="admin_password" value="<?php echo $_POST['admin_password']; ?>" />
		
		<!-- Menu -->
		<input type="hidden" name="menu_login" value="1" />
		<input type="hidden" name="menu_admin" value="1" />
		<input type="hidden" name="menu_usercp" value="1" />
		<input type="hidden" name="menu_pmbox" value="1" />
		<input type="hidden" name="menu_guestlist" value="" />
		
		<!-- Language -->
		<input type="hidden" name="language" value="<?php echo $_POST['language']; ?>" />	
		
		<input type="hidden" name="step" value="1" />
		<input type="submit" name="back" value="&lt; Back" disabled />
		<input type="submit" name="next" value="Next &gt;" />
	</form>
</p>