<?php
	
	// Create tables
	$sql = "
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "media_categories` (
			`categoryid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`parentid` INT NOT NULL ,
			`name` VARCHAR(64) NOT NULL , 
			`uniqid` VARCHAR (255) NOT NULL ,
			`language` VARCHAR ( 4 ) NOT NULL
			) ENGINE = MYISAM ;
	";
	$db->query($sql);
	
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."media_categories_permissions` (
			`categoryid` INT NOT NULL ,
			`groupid` INT NOT NULL 
			) ENGINE = MYISAM ;";
	$db->query($sql);
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "media_downloads` (
		`downloadid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`name` VARCHAR(64) NOT NULL ,
		`description` VARCHAR(1023) NOT NULL ,
		`version` VARCHAR(64) NOT NULL ,
		`file` VARCHAR(256) NOT NULL ,
		`userid` INT NOT NULL ,
		`timestamp` INT NOT NULL ,
		`categoryid` INT NOT NULL ,
		`counter` INT NOT NULL ,
		`release_notes` TEXT NOT NULL ,
		`thumbnail` VARCHAR ( 511 ) NOT NULL ,
		`disabled` INT ( 1 ) NOT NULL
		) ENGINE = MYISAM ;
		";
	$db->query($sql);
	
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."media_downloads_counter` (
		`downloadid` INT NOT NULL ,
		`timestamp` INT NOT NULL
		) ENGINE = MYISAM ;
	";
	$db->query($sql);
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "media_movies` (
		`movieid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`name` VARCHAR(64) NOT NULL ,
		`file` VARCHAR(64) NOT NULL ,
		`categoryid` INT NOT NULL ,
		`userid` INT NOT NULL ,
		`timestamp` INT NOT NULL ,
		`description` VARCHAR(256) NOT NULL 
		) ENGINE = MYISAM ;
		";
	$db->query($sql);
	
	// Register rights
	$rights->registerRight('media', 'manage');
	$rights->registerRight('media', 'upload');
	
	// Register config values
	$config->register('media', 'pictures-per-row', 3, 'int', 'Sets the number of images displayed on one page.');
	$config->register('media', 'thumbnailwidth', 200, 'int', 'Sets the thumbnail width of image previews.');
	$config->register('media', 'download-login-required', 0, 'bool', 'Enables if a login is required to download a file.');
	$config->register('media', 'max-upload-size', 10485760, 'int', 'Specifies the maximum file size in bytes you can upload.');
	$config->register('media', 'number-of-uploads', 10, 'int', 'Specifies the number of upload forms displayed in the image upload dialog.');
	$config->register('media', 'auto-resize', 0, 'bool', 'Enables or disables the auto resize functionality of the uploaded images.');
	$config->register('media', 'auto-resize-width', 1024, 'int', 'Specifies the width of the images to which they will be resized to.');
	$config->register('media', 'hide-submedia', 0, 'bool', 'Hides or displays the number of subcategories and media of a category.');
	$config->register('media', 'mail-notification-address', '', 'string', 'Mail address where download notifications are sent to.');
	$config->register('media', 'hide-upload-author', '0', 'bool', 'Hides or shows the user who uploaded content.');
	$config->register('media', 'hide-upload-date', '0', 'bool', 'Hides or shows the upload date.');
	
?>