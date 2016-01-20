<?php
	
	$config->register('ad', 'standard_image_width', 200, 'int', 'The default image width.');
	$config->register('ad', 'standard_image_height', 200, 'int', 'The default image height.');
		
	$sql = "CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."ad` (
				`adid` int(10) NOT NULL AUTO_INCREMENT,
				`img` varchar(500) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
				`url` varchar(500) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
				PRIMARY KEY (`adid`)
				) ENGINE=MyISAM;";
	$db->query($sql);

	
?>