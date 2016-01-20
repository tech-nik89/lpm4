<?php
	
	// register rights
	$rights->registerRight('admin', 'sessions');
	$rights->registerRight('admin', 'users');
	$rights->registerRight('admin', 'personal_fields');
	$rights->registerRight('admin', 'groups');
	$rights->registerRight('admin', 'menu');
	$rights->registerRight('admin', 'mod');
	$rights->registerRight('admin', 'config');
	$rights->registerRight('admin', 'comments');
	$rights->registerRight('admin', 'content');
	$rights->registerRight('admin', 'log');
	$rights->registerRight('admin', 'groupware');
	$rights->registerRight('admin', 'contact');
	$rights->registerRight('admin', 'boxes');
	$rights->registerRight('admin', 'backup');
	
	// register config
	$config->register('core', 'maintenance', 0, 'bool', 'Enables or disables the maintenance mode.');
	$config->register('core', 'maintenance_description', '', 'text', 'Sets the description for the maintenance mode.');
	$config->register('login', 'session-time', 240, 'int', 'Sets the time in seconds a session is valid.');
	
	$config->register('core', 'template', 'default', 'string', 'Sets the default template.');
	$config->register('core', 'default_mod', 'login', 'string', 'Sets the default module for the start page.');
	$config->register('core', 'language', 'de', 'string', 'Sets the language.');
	$config->register('admin', 'users-per-page', 20, 'int', 'Sets the number of users displayed on one page.');
	$config->register('admin', 'comments-per-page', 20, 'int', 'Sets the number of comments displayed on one page.');
	$config->register('core', 'mod_rewrite', 0, 'bool', 'Enables or disables mod rewrite.');
	$config->register('core', 'debug', 0, 'bool', 'Enables or disables the debug mode.');
	$config->register('core', 'bbcode', 1, 'bool', 'Enables or disables the bbcode parser.');
	
	$config->register('core', 'img-width', 150, 'int', 'Sets the avatar width in pixel.');
	$config->register('core', 'img-height', 150, 'int', 'Sets the avatar height in pixel.');
	
	$config->register('core', 'timezone', 'Europe/Paris', 'string', 'Sets the server timezone.');
	$config->register('core', 'admin-mail', '', 'string', 'Specifies the email adress of the page administrator.');
	$config->register('core', 'enable-mobile', 0, 'bool', 'Enables or disables the mobile device detection.');
	$config->register('core', 'disable-reflections', 0, 'bool', 'Enables or disables the reflections of the avatars.');
	
	$config->register('core', 'link-mod-to-menu', 0, 'bool', "When enabled, modules without visible menu entry aren't accessable.");
	$config->register('core', 'link-mod-to-menu-exclusions', '', 'string', "List of modules, which are always accessable. Separate with semicolon (;).");
	
	$config->register('profile', 'hide-lastname', 0, 'bool', 'Hides the lastname in profile.');
	
	$config->register('core', 'disable-magic-quotes', 0, 'bool', 'Force disables magic quotes, if php.ini is not accessable.');
	
	$config->register('core', 'include-submenu', 0, 'bool', 'Specifies if the submenu entires are included in main menu tree.');
	$config->register('core', 'hide-logout-menu-entry', 0, 'bool', 'Specifies if the logout-submenu is included in menu tree.');
	
	$config->register('core', 'password-salt', 0, 'string', 'Specifies the string which is used to salt the user passwords.');
		
	$db->query("
				CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "groupware` (
				`groupwareid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`title` TEXT NOT NULL ,
				`description` TEXT NOT NULL ,
				`state` INT( 1 ) NOT NULL ,
				`end` INT NOT NULL ,
				`priority` INT( 1 ) NOT NULL , 
				`contactid` INT , 
				`userid` INT 
				) ENGINE = MYISAM ;");
	
	$db->query("
				CREATE TABLE  `".MYSQL_TABLE_PREFIX."domains` (
				`domainid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`name` VARCHAR( 255 ) NOT NULL ,
				`comment` TEXT NOT NULL ,
				`template` VARCHAR ( 255 ) NOT NULL ,
				`alias` INT NOT NULL,
				`language` VARCHAR( 4 ) NOT NULL
				) ENGINE = MYISAM ;");
	
	$db->query("
				CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."meta` (
				`tagid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`language` VARCHAR( 4 ) NOT NULL ,
				`domainid` INT NOT NULL,
				`name` VARCHAR( 255 ) NOT NULL ,
				`http_equiv` VARCHAR( 255 ) NOT NULL ,
				`content` TEXT NOT NULL 
				) ENGINE = MYISAM ;");
				
?>