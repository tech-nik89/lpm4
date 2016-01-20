<?php
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."shoutbox` (
			`shoutid` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`userid` INT NOT NULL ,
			`timestamp` INT NOT NULL ,
			`text` VARCHAR( 255 ) NOT NULL
			) ENGINE = MYISAM ;";
	$db->query($sql);
	
	$rights->registerRight('shoutbox', 'manage');
	$config->register('shoutbox', 'posts', 7, 'int', 'Number of posts shown in the shoutbox.');
	$config->register('shoutbox', 'lock-time', 15, 'int', 'The time in seconds an user cannot post again.');
	$config->register('shoutbox', 'reverse', 0, 'bool', 'Reverses the shoutbox.');
	
?>