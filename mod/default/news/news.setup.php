<?php
	
	$rights->registerRight('news', 'manage');
	
	$config->register('news', 'news-per-page', 5, 'int', 'Sets the number of news displayed on one page.');
	$config->register('news', 'news-box-entries', 5, 'int', 'Specifies the number of entries displayed in the news box.');
	$config->register('news', 'preview-char-length', '500', 'int', 'Sets the length of the preview news.');
	$config->register('news', 'rss', '0', 'bool', 'Enables or disables the rss feed.');
	$config->register('news', 'hide-time', '0', 'bool', 'Hides or shows the time.');
	$config->register('news', 'hide-author', '0', 'bool', 'Hides or shows the author of a news entry.');
	
	$sql = "
		 CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "news` (
			`newsid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`title` VARCHAR(64) NOT NULL ,
			`text` TEXT NOT NULL ,
			`preview`TEXT NOT NULL ,
			`userid` INT NOT NULL ,
			`timestamp` INT NOT NULL , 
			`edit_count` INT NOT NULL ,
			`language` VARCHAR ( 4 ) NOT NULL ,
			`domainid` INT NOT NULL
			) ENGINE = MYISAM 
	";
	
	$db->query($sql);


	
?>