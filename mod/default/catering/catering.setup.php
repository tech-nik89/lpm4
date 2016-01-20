<?php

	$rights->registerRight('catering', 'manage');
	$rights->registerRight('catering', 'seller');
	
	$db->query("
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."catering_categories` (
		`categoryid` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`rank` INT( 10 ) NOT NULL ,
		`name` VARCHAR( 255 ) NOT NULL ,
		`visible` TINYINT( 1 ) NOT NULL
		) ENGINE = MYISAM;");
	
	$db->query("
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."catering_products` (
		`productid` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`categoryid` INT( 10 ) NOT NULL ,
		`sellerid` INT( 10 ) NOT NULL ,
		`name` VARCHAR( 255 ) NOT NULL ,
		`description` TEXT NOT NULL ,
		`price` INT( 10 ) NOT NULL ,
		`amount` INT( 10 ) NOT NULL ,
		`visible` TINYINT( 1 ) NOT NULL ,
		`configurable` TINYINT( 1 ) NOT NULL ,
		`amount_sold` INT( 10 ) NOT NULL
		) ENGINE = MYISAM ;");
		
	$db->query("
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."catering_orders` (
		`orderid` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`ordererid` INT( 10 ) NOT NULL ,
		`date` INT( 20 ) NOT NULL ,
		`isold` INT ( 1 ) NOT NULL
		) ENGINE = MYISAM ;");
		
	$db->query("
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."catering_items` (
		`itemid` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`orderid` INT( 10 ) NOT NULL ,
		`productid` INT( 10 ) NOT NULL ,
		`amount` INT NOT NULL ,
		`state` INT( 2 ) NOT NULL ,
		`notice` TEXT NOT NULL ,
		`price` INT( 10 ) NOT NULL 
		) ENGINE = MYISAM ;");
		
	$db->query("
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."catering_ingredients` (
		`ingredientid` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`name` VARCHAR( 255 ) NOT NULL ,
		`description` TEXT NOT NULL ,
		`price` INT ( 10 ) NOT NULL ,
		`available` TINYINT( 1 ) NOT NULL
		) ENGINE = MYISAM ;");
		
	$db->query("
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."catering_products_ingredients` (
		`productid` INT( 10 ) NOT NULL ,
		`ingredientid` INT( 10 ) NOT NULL
		) ENGINE = MYISAM ;");

	$db->query("
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."catering_items_ingredients` (
		`itemid` INT( 10 ) NOT NULL ,
		`ingredientid` INT( 10 ) NOT NULL ,
		`price` INT NOT NULL
		) ENGINE = MYISAM ;");
		
	
?>