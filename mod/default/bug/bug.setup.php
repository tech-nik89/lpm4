<?php
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "bugtracker_projects` (
			`projectid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`name` VARCHAR( 255 ) NOT NULL ,
			`description` VARCHAR( 2047 ) NOT NULL
			) ENGINE = MYISAM ;";
			
	$db->query($sql);
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "bugtracker_categories` (
			`categoryid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`name` VARCHAR( 255 ) NOT NULL
			) ENGINE = MYISAM ;";
	$db->query($sql);
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "bugtracker_issues` (
			`issueid` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`projectid` INT NOT NULL , 
			`categoryid` INT NOT NULL ,
			`reproducible` INT NOT NULL ,
			`effect` INT NOT NULL ,
			`priority` INT NOT NULL ,
			`summary` VARCHAR( 2047 ) NOT NULL ,
			`description` TEXT NOT NULL ,
			`additional` TEXT NOT NULL ,
			`userid` INT NOT NULL , 
			`timestamp` INT NOT NULL , 
			`state` INT NOT NULL , 
			`assignedto` INT NOT NULL
			) ENGINE = MYISAM ;
		";
	$db->query($sql);
	
	$rights->registerRight('bug', 'manage');
	
	$config->register('bug', 'send-mail', 0, 'bool', 'Enables or disables email notifications.');
?>