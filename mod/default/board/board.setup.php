<?php
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "board` (
			`boardid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`board` VARCHAR(64) NOT NULL ,
			`order` INT NOT NULL ,
			`description` VARCHAR(64) NOT NULL ,
			`assigned_groupid` INT NOT NULL
			) ENGINE = MYISAM ;

	";
	
	$db->query($sql);
	
	$sql = "
		
		 CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "thread` (
			`threadid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`boardid` INT NOT NULL ,
			`thread` VARCHAR(64) NOT NULL ,
			`userid` INT NOT NULL ,
			`lastpost` INT NOT NULL ,
			`sticky` INT ( 1 ) NOT NULL ,
			`hits` INT NOT NULL ,
			`closed` INT ( 1 ) NOT NULL
			) ENGINE = MYISAM 
	
	";
	
	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "post` (
			`postid` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`threadid` INT NOT NULL ,
			`post` TEXT NOT NULL ,
			`userid` INT NOT NULL ,
			`timestamp` INT NOT NULL ,
			`attachments` VARCHAR ( 255 )
			) ENGINE = MYISAM ;
		";
		
	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `"  .MYSQL_TABLE_PREFIX . "newposts` (
			`userid` INT NOT NULL ,
			`threadid` INT NOT NULL
			) ENGINE = MYISAM ;
		";
		
	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "thread_abo` (
			`userid` INT NOT NULL ,
			`threadid` INT NOT NULL
			) ENGINE = MYISAM ;

		";
	
	$db->query($sql);
	
	$rights->registerRight('board', 'manage');
	$config->register('board', 'posts-per-page', 20, 'int', 'Sets the number of posts displayed on one page.');
	$config->register('board', 'threads-per-page', 20, 'int', 'Sets the number of threads displayed on one page.');
	$config->register('board', 'convert-urls', 1, 'bool', 'Enables or disables the automatic url converted.');
	$config->register('board', 'box-thread-once', 1, 'bool', 'Sets if a thread is only displayed once in the board box.');
	$config->register('board', 'box-posts', 5, 'int', 'Sets the number of posts displayed in the board box.');
	$config->register('board', 'enable-subscriptions', 0, 'bool', 'Enables or disables thread subscribtions.');
	$config->register('board', 'disable-number-of-posts', 0, 'bool', 'Enables or disables the number of posts for an user.')
	
?>