<?php

	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "sponsor` (
			`sponsorid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`name` VARCHAR(64) NOT NULL ,
			`description` VARCHAR(256) NOT NULL ,
			`homepage` VARCHAR(64) NOT NULL ,
			`image` VARCHAR(64) NOT NULL
			) ENGINE = MYISAM ;

	";
	
	$db->query($sql);
	
	$rights->registerRight('sponsor', 'manage');

?>