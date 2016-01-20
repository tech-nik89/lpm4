<div class="headline">Step 6: Language</div>

<p>Specify the language for your installation.</p>
<form action="" method="post">
<?php
	
	// list all available languages
	function listLanguages()
	{
		// scan mod directory
		$path = './lang/';
		$list = scandir($path);
		
		// remove . and ..
		foreach ($list as $val)
		{
			if ($val != '.'
				&& $val != '..'
				&& $val != '.svn')
			{	
				
				// remove lang_
				$x = substr($val, 5);
				
				// remove .php
				$x = substr($val, 0, -4);
				
				$out[] = substr($x, 5);
				
			}
		}
		
		return $out;
	}
	
?>

<select name="language">
	<?php
		$lnglist = listLanguages();
		foreach ($lnglist as $lng) {
			if ($_POST['language'] == $lng) {
				echo '<option value="'.$lng.'" selected="selected">'.$lng.'</option>';
			}
			else {
				echo '<option value="'.$lng.'">'.$lng.'</option>';
			}
		}
	?>
</select>

<p>&nbsp;</p>

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
	
	<input type="hidden" name="step" value="6" />
	<input type="submit" name="back" value="&lt; Back" />
	<input type="submit" name="next" value="Next &gt;" />
	
</form>