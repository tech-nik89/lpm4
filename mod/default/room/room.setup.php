<?php
	$rights->registerRight('room', 'manage');
	$rights->registerRight('room', 'move-users');
	
	$config->register('room', 'show-grid', 0, 'bool', 'Enables or displays the grid.');
	$config->register('room', 'show-information', 1, 'bool', 'Enables or disables the information display.');
	$config->register('room', 'show-coordinates', 1, 'bool', 'Shows or hides the coordinates.');
	$config->register('room', 'x-axis-format', 'number', 'list', 'Sets the format of the x-axis scale.', 'alphabet,roman,number,pipes,binary');
	$config->register('room', 'y-axis-format', 'alphabet', 'list', 'Sets the format of the y-axis scale.', 'alphabet,roman,number,pipes,binary');

	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."room` (
			`roomid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`title` VARCHAR( 50 ) NOT NULL ,
			`height` INT NOT NULL ,
			`width` INT NOT NULL ,
			`description` VARCHAR( 500 ) NOT NULL ,
			`eventid`INT NOT NULL
			) ENGINE = MYISAM ;
		";
	$db->query($sql);
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."room_item` (
			`roomid` INT NOT NULL , 
			`x` INT NOT NULL ,
			`y` INT NOT NULL ,
			`type` INT NOT NULL ,
			`value` INT NOT NULL ,
			PRIMARY KEY (`roomid`, `x` , `y` )
			) ENGINE = MYISAM ;
		";
	$db->query($sql);
?>