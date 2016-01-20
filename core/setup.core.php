<?php
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "mod` (
			`mod` VARCHAR (64) NOT NULL
			) ENGINE = MYISAM ;
			
		";
		
	$db->query($sql);
	
	
		$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "personal_fields` (
		 `fieldid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`value` VARCHAR (64) NOT NULL
		) ENGINE = MYISAM ;
	
	";
	
	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "personal_data` (
		`userid` INT NOT NULL ,
		`fieldid` INT NOT NULL ,
		`value` VARCHAR (256) NOT NULL
		) ENGINE = MYISAM ;";
		
	
	$db->query($sql);
		
?>