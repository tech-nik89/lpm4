<?php
	
	$rights->registerRight('article', 'manage');
	$rights->registerRight('article', 'publish');
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."article_categories` (
			`categoryid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`parentid` INT NOT NULL ,
			`title` VARCHAR( 255 ) NOT NULL ,
			`description` VARCHAR( 2047 ) NOT NULL
			) ENGINE = MYISAM ;
		";
	$db->query($sql);
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."article` (
			`articleid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`categoryid` INT NOT NULL ,
			`authorid` INT NOT NULL ,
			`timestamp` INT NOT NULL ,
			`title` VARCHAR( 255 ) NOT NULL ,
			`preview` VARCHAR( 2047 ) NOT NULL ,
			`text` TEXT NOT NULL ,
			`published` INT ( 1 ) NOT NULL
			) ENGINE = MYISAM ;
		";
	$db->query($sql);
?>