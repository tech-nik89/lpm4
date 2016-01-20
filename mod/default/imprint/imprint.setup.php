<?php
//$lang->addModSpecificLocalization($mod);

$rights->registerRight('imprint', 'manage');

$db->query("CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."imprint` (
			`owner_name` VARCHAR( 255 ) NOT NULL ,
			`owner_street` VARCHAR( 255 ) NOT NULL ,
			`owner_loc` VARCHAR( 255 ) NOT NULL ,
			`owner_tel` VARCHAR( 255 ) NOT NULL ,
			`owner_mail` VARCHAR( 255 ) NOT NULL ,
			`cont_name` VARCHAR( 255 ) NOT NULL ,
			`cont_street` VARCHAR( 255 ) NOT NULL ,
			`cont_loc` VARCHAR( 255 ) NOT NULL ,
			`court` VARCHAR( 255 ) NOT NULL ,
			`imprint` TEXT NOT NULL
			) ENGINE = MYISAM ;");

//$db->query("INSERT INTO `".MYSQL_TABLE_PREFIX."imprint` (imprint) VALUES ('".$lang->get('imprint_pre_defined')."')"); Standardimpressum
?>