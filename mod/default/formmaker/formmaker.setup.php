<?php
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."formmaker` (
			`formid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`title` VARCHAR( 255 ) NOT NULL ,
			`key` VARCHAR( 255 ) NOT NULL ,
			`description` TEXT NOT NULL , 
			`action` VARCHAR( 255 ) NOT NULL ,
			`address` VARCHAR( 1023 ) NOT NULL ,
			`submit` VARCHAR ( 255 ) ,
			`submit_message` VARCHAR ( 1023 )
			) ENGINE = MYISAM ;
	";
	$db->query($sql);
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."formmaker_elements` (
			`elementid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`formid` INT NOT NULL ,
			`type` INT NOT NULL ,
			`title` VARCHAR( 511 ) NOT NULL ,
			`values` TEXT NOT NULL ,
			`order` INT NOT NULL ,
			`required` INT ( 1 ) NOT NULL
			) ENGINE = MYISAM ;
	";
	$db->query($sql);
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."formmaker_data` (
			`submitid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`formid` INT NOT NULL ,
			`timestamp` INT NOT NULL ,
			`ipaddress` VARCHAR( 255 ) NOT NULL ,
			`content` TEXT NOT NULL
			) ENGINE = MYISAM ;
	";
	$db->query($sql);
	
	$rights->registerRight('formmaker', 'manage');
	
?>