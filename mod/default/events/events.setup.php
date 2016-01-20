<?php 
	
	// create table
	$sql = "
	
		 CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "events` (
			`eventid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`name` VARCHAR(64) NOT NULL ,
			`description` VARCHAR(512) NOT NULL ,
			`start` INT NOT NULL ,
			`end` INT NOT NULL , 
			`reg_start` INT NOT NULL , 
			`reg_end` INT NOT NULL , 
			`min_age` INT NOT NULL ,
			`agb` TEXT NOT NULL , 
			`last_check` INT NOT NULL , 
			`login_active` INT(1) NOT NULL ,
			`seats` INT NOT NULL , 
			`free` INT(1) NOT NULL , 
			`credits` INT NOT NULL
			) ENGINE = MYISAM 
	
	";
	
	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "register` (
			`userid` INT NOT NULL ,
			`eventid` INT NOT NULL ,
			`payed` INT(1) NOT NULL ,
			`appeared` INT(1) NOT NULL
			) ENGINE = MYISAM ;
	";
	
	$db->query($sql);
	
	// set rights
	$rights->registerRight('events', 'manage');
	
	// set config
	$config->register('events', 'bar-width', 90, 'int', 'Sets the width of the register bar.');
?>