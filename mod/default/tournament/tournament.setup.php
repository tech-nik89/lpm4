<?php
	
	/* Tournament setup file */
	
	$rights->registerRight('tournament', 'submit_results');
	$rights->registerRight('tournament', 'add_remove_edit');
	
	$config->register('tournament', 'tree-encounter-width', 80, 'int', 'Sets the width of an encounter box.');
	$config->register('tournament', 'group-only-leader-can-submit', 0, 'bool', 'Specifies, if only the leader of a group is allowed to submit results.');
	$config->register('tournament', 'box_number_of_results', 3, 'int', 'Sets the number of tournaments shown in the box.');
	$config->register('tournament', 'allow_undoing_encounter_points', 1, 'bool', 'Specifies, if you can undo set encounters.');
	$config->register('tournament', 'start_with_break', 1, 'bool', 'Specifies, if a tournament starts with a break.');

	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tournamentlist` (
			`tournamentid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`eventid` INT NOT NULL ,
			`title` VARCHAR (255) NOT NULL ,
			`playerlimit` INT NOT NULL ,
			`game` VARCHAR (255) NOT NULL ,
			`mappool` VARCHAR (255) NOT NULL ,
			`finalmappool` VARCHAR(500) NOT NULL ,
			`mode` INT NOT NULL ,
			`playerperteam` INT NOT NULL , 
			`picture` VARCHAR (255) NOT NULL ,
			`credits` INT NOT NULL ,
			`wwclgameid` INT NOT NULL ,
			`rules` VARCHAR (255) NOT NULL ,
			`state` INT( 1 ) NOT NULL ,
			`start` INT NOT NULL ,
			`duration` INT NOT NULL ,
			`breaktime` INT NOT NULL
			) ENGINE = MYISAM ;
		";
	
	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tournamentsettings` (
			`tournamentid` INT NOT NULL ,
			`settingid` INT NOT NULL ,
			`value` VARCHAR (255) NOT NULL
			) ENGINE = MYISAM ;
		
			";
			
	$db->query($sql);
	
	$sql = "
		
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tournamentgroups` (
			`groupid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`tournamentid` INT NOT NULL , 
			`name` VARCHAR (255) NOT NULL ,
			`password` VARCHAR (255) NOT NULL ,
			`founderid` INT NOT NULL ,
			`description` VARCHAR (255) NOT NULL
			) ENGINE = MYISAM ;
			";
			
	$db->query($sql);
	
	$sql = "
		
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tournamentregister` (
			`tournamentid` INT NOT NULL ,
			`memberid` INT NOT NULL
			) ENGINE = MYISAM ;
		
			";
			
	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tournamentencounters` (
			`tournamentid` INT NOT NULL ,
			`roundid` INT NOT NULL ,
			`encounterid` INT NOT NULL ,
			`player1id` INT NOT NULL ,
			`player2id` INT NOT NULL ,
			`points1` INT NOT NULL ,
			`points2` INT NOT NULL ,
			`state` INT NOT NULL , 
			`alias` INT NOT NULL ,
			`start` INT NOT NULL ,
			`duration` INT NOT NULL 
			) ENGINE = MYISAM ;
		
		";

	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tournamentgroupregister` (
			`groupid` INT NOT NULL ,
			`tournamentid` INT NOT NULL ,
			`memberid` INT NOT NULL
			) ENGINE = MYISAM ;
	";
	
	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tournamentgroupenc` (
			`tournamentid` INT NOT NULL ,
			`participantid` INT NOT NULL ,
			`group` INT NOT NULL ,
			`round` INT NOT NULL ,
			`rank` INT NOT NULL
			) ENGINE = MYISAM ;

	";
	
	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tournamentpoints` (
			`tournamentid` INT NOT NULL ,
			`participantid` INT NOT NULL ,
			`points` INT NOT NULL
			) ENGINE = MYISAM ;

	";
	
	$db->query($sql);
	
	$sql = "
	
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tournamentcredit` (
			`userid` INT NOT NULL ,
			`eventid` INT NOT NULL , 
			`credits` INT NOT NULL ,
			`timestamp` INT NOT NULL
			) ENGINE = MYISAM ;

	";
	
	$db->query($sql);
	
	$sql = "
		
		CREATE TABLE IF NOT EXISTS `".MYSQL_TABLE_PREFIX . "tournamentcomm` (
			`tournamentid` INT NOT NULL ,
			`userid` INT NOT NULL ,
			`encid` INT NOT NULL ,
			`roundid` INT NOT NULL ,
			`text` VARCHAR( 500 ) NOT NULL ,
			`timestamp` INT NOT NULL
			) ENGINE = MYISAM ;

		";
		
	$db->query($sql);
	
?>