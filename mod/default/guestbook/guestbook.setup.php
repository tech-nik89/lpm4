<?php
	$config->register('guestbook', 'entries-per-page', 20, 'int', 'Sets the number of entries displayed on one page.');
	$config->register('guestbook', 'ip-blocker-enable', 0, 'bool', 'Enables or disables the ip blocker.');
	$config->register('guestbook', 'ip-blocker-timelimit', 180, 'int', 'Sets the ip blocker timelimit.');
	
	$rights->registerRight('guestbook', 'manage');
	$tbl = MYSQL_TABLE_PREFIX . 'guestbook';
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".$tbl."` (
			`guestbookid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`author` VARCHAR(64) NOT NULL ,
			`timestamp` INT NOT NULL ,
			`message` VARCHAR(512) NOT NULL , 
			`ipadress` VARCHAR(16) NOT NULL
			) ENGINE = MYISAM ;
		";
	$db->query($sql);
?>