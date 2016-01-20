<?php
	$rights->registerRight('tetris', 'admin');
	
	$sql = "CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."tetris_attack` (
	  `nickname` varchar(255) NOT NULL,
	  `rows` int(11) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
	$db->query($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."tetris_chat` (
	  `chatid` bigint(20) NOT NULL AUTO_INCREMENT,
	  `type` int(3) NOT NULL, 
	  `nickname` varchar(255) NOT NULL,
	  `text` varchar(255) NOT NULL,
	  PRIMARY KEY (`chatid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;";
	$db->query($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."tetris_highscore` (
	  `nickname` varchar(255) NOT NULL,
	  `score` bigint(20) NOT NULL,
	  `lines` bigint(20) NOT NULL,
	  `level` int(11) NOT NULL,
	  `timestamp` int(11) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
	$db->query($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."tetris_player` (
	  `nickname` varchar(255) NOT NULL,
	  `ipadress` varchar(31) NOT NULL,
	  `score` bigint(20) NOT NULL,
	  `level` int(11) NOT NULL,
	  `last_action` int(11) NOT NULL,
	  `last_real_action` INT(11) NOT NULL,
	  `alive` int(1) NOT NULL,
	  `master` int(1) NOT NULL,
	  `field` VARCHAR (512) NOT NULL,
	  `uniquid` VARCHAR (100) NOT NULL,
	  `games` int(5) NOT NULL,
	  `wins` int(5) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
	$db->query($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX."tetris_start` (
	  `nickname` varchar(255) NOT NULL,
	  `seed` int(10) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
	$db->query($sql);

	
?>