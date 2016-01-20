<?php
	
	$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "beamer_list` (
		`beamerid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`name` VARCHAR( 255 ) NOT NULL ,
		`description` VARCHAR( 511 ) NOT NULL
		) ENGINE = MYISAM ;";

	$db->query($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "beamer_mod` (
		`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`beamerid` INT NOT NULL , 
		`order` INT NOT NULL ,
		`duration` INT NOT NULL ,
		`url` VARCHAR( 511 ) NOT NULL
		) ENGINE = MYISAM ;";
	
	$db->query($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."beamer_message` (
		`messageid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`beamerid` INT NOT NULL ,
		`text` TEXT NOT NULL
		) ENGINE = MYISAM ;";
	
	$db->query($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."beamer_movie` (
		`movieid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`beamerid` INT NOT NULL ,
		`file` VARCHAR( 255 ) NOT NULL
		) ENGINE = MYISAM ;";
	
	$db->query($sql);
	
	$rights->registerRight('beamer', 'configure');
	
?>