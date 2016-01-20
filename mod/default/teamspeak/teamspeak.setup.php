<?php

	$sql = " CREATE TABLE IF NOT EXISTS  `" . MYSQL_TABLE_PREFIX . "teamspeak` (
		`ID` int(11) NOT NULL AUTO_INCREMENT,
		`order` int(11) NOT NULL,
		`address` VARCHAR(16) NOT NULL,
		`tcp` int(5) NOT NULL,
		`udp` int(5) NOT NULL,
		`pw` VARCHAR(64) NOT NULL,
		PRIMARY KEY ( `ID` )
		) ENGINE = MYISAM ";

	$db->query($sql);
	$rights->registerRight('teamspeak', 'manage');

?>
