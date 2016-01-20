<?php
	$rights->registerRight('faq', 'manage');
	
	$db->query("CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."faq` (
				`id` int(8) NOT NULL AUTO_INCREMENT,
			 	`faqorder` int(3) NOT NULL,
			  	`question` text NOT NULL,
			  	`answer` text NOT NULL,
			  	PRIMARY KEY (`id`)
				) ENGINE = MYISAM ;");
?>