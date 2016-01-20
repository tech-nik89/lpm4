<?php
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "contact` (
			`contactid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`userid` INT NOT NULL ,
			`timestamp` INT NOT NULL ,
			`uniqid` VARCHAR(64) NOT NULL ,
			`subject` VARCHAR(64) NOT NULL ,
			`text` TEXT NOT NULL ,
			`read` INT( 1 ) NOT NULL ,
			`done` INT( 1 ) NOT NULL
			) ENGINE = MYISAM ;
		";
	
	$db->query($sql);
	
	$rights->registerRight('admin', 'contact');
	
	$config->register('contact', 'login-required', 0, 'bool', 'Specifies, if a guest has to be logged in to send a request.');
	$config->register('contact', 'title', '', 'string', 'Specifies the title of the form.');
	$config->register('contact', 'description', '', 'text', 'Specifies the description of the form.');
	
	$config->register('contact', 'send-mail', 0, 'bool', 'Sends an email to the specified adress when a new request was sent.');
	$config->register('contact', 'email-adress', '', 'string', 'The email adress the notification will be sent to.');
	
?>