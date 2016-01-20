<?php
	
	$rights->registerRight('minitools', 'admin');
	
	$db->query("CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "minitools` (
			`modename` VARCHAR (127) NOT NULL
			) ENGINE = MYISAM ");
	
?>