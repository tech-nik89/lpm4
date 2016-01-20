<?php
	$rights->registerRight('detailedpoll', 'editor');

	//$config->register('poll', 'polls-per-page', 5, 'int', 'Sets the number of polls displayed on one page.');

	$db->query("CREATE TABLE `".MYSQL_TABLE_PREFIX."detailedpoll` (
				`detailedpollid` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`title` VARCHAR( 255 ) NOT NULL ,
				`description` VARCHAR( 1023 ) NOT NULL ,
				`creator` INT( 10 ) NOT NULL ,
				`state` INT( 1 ) NOT NULL ,
				`date` VARCHAR( 12 ) NOT NULL
				);");
				
	$db->query("CREATE TABLE `".MYSQL_TABLE_PREFIX."detailedpoll_questions` (
				`questionid` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`title` VARCHAR( 255 ) NOT NULL ,
				`description` VARCHAR( 1023 ) NOT NULL ,
				`detailedpollid` INT( 10 ) NOT NULL ,
				`rank` INT( 10 ) NOT NULL ,
				`parentid` INT( 10 ) NOT NULL ,
				`percent` INT( 4 ) NOT NULL
				);");
				
	$db->query("CREATE TABLE `".MYSQL_TABLE_PREFIX."detailedpoll_user_answers` (
				`useranswerid` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`userid` VARCHAR( 20 ) NOT NULL ,
				`detailedpollid` INT( 10 ) NOT NULL ,
				`date` VARCHAR( 20 ) NOT NULL
				);");
				
	$db->query("CREATE TABLE `".MYSQL_TABLE_PREFIX."detailedpoll_answers` (
				`answerid` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`useranswerid` INT( 10 ) NOT NULL ,
				`questionid` INT( 10 ) NOT NULL ,
				`value` INT( 10 ) NOT NULL
				);");
?>