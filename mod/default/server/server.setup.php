<?php

	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."servers` (
			`serverid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`name` VARCHAR( 255 ) NOT NULL ,
			`description` VARCHAR( 255 ) NOT NULL ,
			`game` VARCHAR( 255 ) NOT NULL ,
			`gameq` VARCHAR ( 255 ) NOT NULL ,
			`ipadress` VARCHAR( 255 ) NOT NULL ,
			`port` INT NOT NULL ,
			`userid` INT NOT NULL
			) ENGINE = MYISAM ;
		";
	
	$db->query($sql);
	
	$rights->registerRight('server', 'manage');
?>