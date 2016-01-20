<?php
	
	$db->query('
		CREATE TABLE IF NOT EXISTS `'.MYSQL_TABLE_PREFIX.'movies` (
		`movieid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`title` VARCHAR( 255 ) NOT NULL ,
		`description` VARCHAR( 1023 ) NOT NULL ,
		`urlid` VARCHAR( 255 ) NOT NULL ,
		`order` INT NOT NULL ,
		`thumbnail` INT NOT NULL ,
		`language` VARCHAR ( 2 ) NOT NULL ,
		`hidden` INT ( 1 ) NOT NULL ,
		`domainid` INT NOT NULL
		) ENGINE = MYISAM ;');
	
	$rights->registerRight('movies', 'manage');
	$config->register('movies', 'high-definition', '1', 'bool', 'Enables or disables auto-hd for the movies.');
	$config->register('movies', 'autoplay', '0', 'bool', 'Enables or disables autoplay for the movies.');
	$config->register('movies', 'columns', '2', 'int', 'Specifies the number of columns.');
	
?>