<?php
	
	$db->query("
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."tagcloud` (
		`tagid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`title` VARCHAR( 255 ) NOT NULL ,
		`url` TEXT NOT NULL ,
		`weight` INT NOT NULL ,
		`language` VARCHAR ( 4 ) NOT NULL ,
		`domainid` INT NOT NULL
		) ENGINE = MYISAM ;
	");
	
	$rights->registerRight('tagcloud', 'manage');
	$config->register('tagcloud', 'top-text-de', '', 'text', 'Specifies the text that will be display above the cloudtag.');
	$config->register('tagcloud', 'top-text-en', '', 'text', 'Specifies the text that will be display above the cloudtag.');
	$config->register('tagcloud', 'top-text-fr', '', 'text', 'Specifies the text that will be display above the cloudtag.');
	$config->register('tagcloud', 'header-de', '', 'string', 'Specifies the text that will be displayed as headline.');
	$config->register('tagcloud', 'header-en', '', 'string', 'Specifies the text that will be displayed as headline.');
	$config->register('tagcloud', 'header-fr', '', 'string', 'Specifies the text that will be displayed as headline.');
	$config->register('tagcloud', 'justify', '0', 'bool', 'Specifies if the tags will be displayed justified.');
	
?>