<?php
	$rights->registerRight('poll', 'editor');

	$config->register('poll', 'polls-per-page', 5, 'int', 'Sets the number of polls displayed on one page.');
	$config->register('poll', 'maximum-questions', 10, 'int', 'Sets the max number of questions.');
	$config->register('poll', 'maxbarlength', 300, 'int', 'Sets the max length of the bar.');
	$config->register('poll', 'barcolor', "#00ff00,#00dd00,#00bb00,#009900,#007700", 'string', 'A list of the bar colors.');
	$config->register('poll', 'box-show-bar-in-second-row', 1, 'bool', 'Defines if the bar should be shown in a seperate row.');
	$config->register('poll', 'box-layout', "boxes", 'list', '', 'lines,boxes');
	$config->register('poll', 'main-layout', "boxes", 'list', '', 'lines,boxes');

	$db->query("CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "poll` (
 		 `ID` int(10) NOT NULL auto_increment,
  		 `name` varchar(500) collate utf8_unicode_ci NOT NULL,
 		 `button` varchar(50) collate utf8_unicode_ci NOT NULL default 'radio',
  		 `date` varchar(50) collate utf8_unicode_ci NOT NULL,
  		 `active` int(1) NOT NULL default '1',
		 `voted` text collate utf8_unicode_ci NOT NULL,
  		 `votes` int(10) NOT NULL default '0',
 		  PRIMARY KEY  (`ID`)
		 ) ENGINE=MyISAM;"
		 );
		 		
	$db->query("CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "poll_questions` (
  		`ID` int(10) NOT NULL auto_increment,
  		`pollID` int(10) NOT NULL,
  		`text` varchar(500) collate utf8_unicode_ci NOT NULL,
  		`count` int(10) NOT NULL default '0',
  		PRIMARY KEY  (`ID`)
		) ENGINE=MyISAM;");
	
?>