<?php

	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "inbox` (
			`pmid` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`senderid` INT NOT NULL ,
			`recieverid` INT NOT NULL ,
			`timestamp` INT NOT NULL ,
			`subject` VARCHAR(64) NOT NULL ,
			`message` TEXT NOT NULL ,
			`read` INT( 1 ) NOT NULL ,
			`notified` INT( 1 ) NOT NULL
			) ENGINE = MYISAM ;
	";
	
	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "outbox` (
			`pmid` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`senderid` INT NOT NULL ,
			`recieverid` INT NOT NULL ,
			`timestamp` INT NOT NULL ,
			`subject` VARCHAR(64) NOT NULL ,
			`message` TEXT NOT NULL 
			) ENGINE = MYISAM ;
	";
	
	$db->query($sql);
	
	$config->register('pmbox', 'email-notification', 0, 'bool', 'Enables or disables the email notification for new pms');
	$config->register('pmbox', 'inbox_limit', 35, 'int', 'Sets the max number of messages stored in the inbox.');
	$notify->registerFile('pmbox.notify.php');
	
?>