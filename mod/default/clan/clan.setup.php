<?php

	$config->register('clan', 'prefix-seperator', '|', 'string', 'Specifies the character which is used to seperate the clan prefix from the nickname.');
	$config->register('clan', 'enable-prefix', '0', 'bool', 'Enables or disables the clan prefixes. Use with caution!');
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."clan` (
			`clanid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`prefix` VARCHAR( 5 ) NOT NULL ,
			`name` VARCHAR( 255 ) NOT NULL ,
			`description` TEXT NOT NULL ,
			`leaderid` INT NOT NULL , 
			`password` VARCHAR ( 255 )
			) ENGINE = MYISAM ;";
	
	$db->query($sql);
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."clan_member` (
			`clanid` INT NOT NULL ,
			`userid` INT NOT NULL
			) ENGINE = MYISAM ;";
	
	$db->query($sql);
	
?>