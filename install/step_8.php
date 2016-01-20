<div class="headline">Step 8: Setup</div>

<p>Setup sets up now LPM IV database and config file ... please wait.</p>

<div id="file" class="install">
	<?php
	echo "Writing config file ... ";
	$handle = fopen('./config/database.config.php', 'w');
	fwrite($handle, "<?php
	
	define('MYSQL_HOST', '".$_POST['host']."', true);
	define('MYSQL_USER', '".$_POST['user']."', true);
	define('MYSQL_PASSWORD', '".$_POST['password']."', true);
	define('MYSQL_DATABASE', '".$_POST['database']."', true);
	
	define('MYSQL_TABLE_PREFIX', '".$_POST['table_prefix']."', true);
	
?>"
	);
	fclose($handle);
	echo "Done.<br />";
	?>
</div>

<div id="verifying" class="install">
	
	<?php
	echo 'Veryfing written config file ... ';
	
	global $setup_running;
	$setup_running = true;
	
	// include core
	include('./core.php');
	include('./core/mod.core.php');
	$mod = new Mod($user, $menu, $login, $smarty, $mobile, $notify, $lang, $breadcrumbs, 
					$rights, $config, $db, $avatar, $pages, $bbcode, $comments, $content, 
					$credit, $log, $backup, $favorites);
	
	if ($_POST['host'] == MYSQL_HOST && $_POST['user'] == MYSQL_USER && $_POST['password'] == MYSQL_PASSWORD && $_POST['database'] == MYSQL_DATABASE)
	{
		echo 'Done.<br />';
	} else {
		echo '<font color="#CC0000">Failed!<br />';
		die('Writing config file failed!');
	}
?>
	
</div>

<div id="core_tables" class="install">
	<?php
	
	// ################## //
	echo "Setting up core tables ... ";
	require_once('./core/setup.core.php');
	echo "Done.<br />";

	// ################## //
	
	echo "Setting up config table ... ";
	$config->setup();
	$login->setup();
	echo "Done.<br />";
	
	// ################## //
	
	echo "Setting up user tables ... ";
	$user->setup();
	echo "Done.<br />";
	
	echo "Setting up menu table ... ";
	$menu->setup();
	echo "Done.<br />";
	
	echo "Setting up content table ... ";
	$content->setup();
	echo "Done.<br />";
	
	echo "Setting up credit table ... ";
	$credit->setup();
	echo "Done.<br />";

	// ################## //
	
	echo "Setting up right management tables ... ";
	$rights->setup();
	echo "Done.<br />";
	
	// ################## //
	
	echo "Setting up comment tables ... ";
	$comments->setup();
	echo "Done.<br />";

	// ################## //
	
	echo "Setting up log table ... ";
	$log->setup();
	echo "Done.<br />";
	
	// ################## //
	
	echo "Setting up stat table ... ";
	$stat->setup();
	echo "Done.<br />";
	
	// ################## //
	
	echo "Setting up notify table ... ";
	$notify->setup();
	echo "Done.<br />";
	
	// ################## //
	
	echo "Setting up boxes table ... ";
	$boxes->setup();
	echo "Done.<br />";
	
	// ################## //
	
	echo "Setting up favorites table ... ";
	$favorites->setup();
	echo "Done.<br />";
	
	?>
</div>

<div id="mod_tables" class="install">
	<?php
	echo "Setting up default modules tables ... ";
	
	$mod->installMod('login');
	$mod->installMod('pmbox');
	$mod->installMod('admin');
	$mod->installMod('usercp');
	$mod->installMod('404');
	$mod->installMod('imprint');
	$mod->installMod('content');
	$mod->installMod('maintenance');
	$mod->installMod('profile');
	$mod->installMod('credit');
	$mod->installMod('contact');
	
	echo "Done.<br />";
	?>
</div>

<div id="password_salt" class="install">
	<?php
		echo "Generating password salt ... ";
		
		$config->set('core', 'password-salt', randomPassword(11));
		
		echo "Done.<br />";
	?>
</div>

<?php
	
	if ($_POST['admin_create'] == '1')
	{
	?>
	<div id="admin_user" class="install"><?php
	
	echo "Creating admin user ... ";
	
	$user->createUser($_POST['admin_email'], $_POST['admin_password'], $_POST['admin_nickname'], '-', '-', strtotime('-1 Year'));
	$group = $rights->createGroup('Admin');
	
	$rights->assignRightToGroup($group, 'admin', 'groups');
	$rights->assignRightToGroup($group, 'admin', 'sessions');
	$rights->assignRightToGroup($group, 'admin', 'users');
	$rights->assignRightToGroup($group, 'admin', 'personal_fields');
	$rights->assignRightToGroup($group, 'admin', 'menu');
	$rights->assignRightToGroup($group, 'admin', 'mod');
	$rights->assignRightToGroup($group, 'admin', 'config');
	$rights->assignRightToGroup($group, 'admin', 'comments');
	$rights->assignRightToGroup($group, 'admin', 'content');
	$rights->assignRightToGroup($group, 'admin', 'log');
	$rights->assignRightToGroup($group, 'admin', 'groupware');
	$rights->assignRightToGroup($group, 'admin', 'contact');
	$rights->assignRightToGroup($group, 'admin', 'boxes');
	$rights->assignRightToGroup($group, 'admin', 'backup');
	
	$rights->assignRightToGroup($group, 'imprint', 'manage');
	
	$rights->assignUserToGroup($group, 1);
	
	echo "Done.<br />";
	?></div><?php
	}
?>

<div id="menu" class="install">
	<?php
	echo "Setting up default menu ... ";
	
	if ($_POST['menu_login'] == '1')
		$menu->addElement('Login', 'login', 0, 0, 0, '', 1);
	
	if ($_POST['menu_admin'] == '1')
		$menu->addElement('Admin', 'admin', 1);
	
	if ($_POST['menu_usercp'] == '1')
		$menu->addElement('User Center', 'usercp', 1);
		
	if ($_POST['menu_pmbox'] == '1')
		$menu->addElement('PM Box', 'pmbox', 1);
		
	if ($_POST['menu_guestlist'] == '1')
		$menu->addElement('Guestlist', 'guestlist');
	
	echo "Done.<br />";
	?>
</div>

<div id="menu" class="install">
	<?php
	echo "Setting up language ... ";
	
	$config->set('core', 'language', $_POST['language']);
	
	echo "Done.<br />";
	?>
</div>

<form action="" method="post">

	<input type="hidden" name="step" value="8" />
	<input type="submit" name="back" value="&lt; Back" disabled />
	<input type="submit" name="next" value="Next &gt;" />
	
</form>