<?php
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."buddylist` (
		`userid` INT NOT NULL ,
		`buddyid` INT NOT NULL ,
		`accepted` INT ( 1 ) NOT NULL
		) ENGINE = MYISAM ;";
	$db->query($sql);
	
	$notify->registerFile('buddylist.notify.php');
?>