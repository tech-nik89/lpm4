<?php
	
	//create table for ts3 server connections
	$sql = " CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "ts3admin_servers` (
		`ID` int(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(64) NOT NULL,
		`address` VARCHAR(16) NOT NULL,
		`query` int(5) NOT NULL,
		`usr` VARCHAR(64) NOT NULL,
		`pw` VARCHAR(64) NOT NULL,
		PRIMARY KEY ( `ID` )
		) ENGINE = MYISAM ";
	$db->query($sql);
	
	//create table for ts3 server rights
	$sql = " CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "ts3admin_server_rights` (
		`serverid` INT NOT NULL ,
		`type` INT( 1 ) NOT NULL ,
		`uid` BIGINT NOT NULL ,
		`r_view_server` INT( 1 ) NOT NULL ,
		`r_edit_server` INT( 1 ) NOT NULL ,
		`r_add_vservers` INT( 1 ) NOT NULL ,
		`r_remove_vservers` INT( 1 ) NOT NULL
		) ENGINE = MYISAM ;";
	$db->query($sql);
	
	$sql = "ALTER TABLE `" . MYSQL_TABLE_PREFIX . "ts3admin_server_rights` ADD PRIMARY KEY ( `serverid` , `type` , `uid` ) ;";
	$db->query($sql);
	
	
	//create table for ts3 vserver rights
	$sql = " CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "ts3admin_vserver_rights` (
		`serverid` INT NOT NULL, 
		`vserverid` INT NOT NULL, 
		`type` INT(1) NOT NULL, 
		`uid` BIGINT NOT NULL, 
		`r_view_vserver` INT(1) NOT NULL, 	
		`r_control_vserver` INT(1) NOT NULL, 
		`r_edit_vserver` INT(1) NOT NULL, 
		`r_view_grouprights` INT(1) NOT NULL, 
		`r_edit_grouprights` INT(1) NOT NULL, 
		`r_rename_group` INT(1) NOT NULL, 
		`r_add_group` INT(1) NOT NULL, 
		`r_remove_group` INT(1) NOT NULL, 
		`r_view_clients` INT(1) NOT NULL, 
		`r_msg_client` INT(1) NOT NULL, 
		`r_kick_client` INT(1) NOT NULL, 
		`r_ban_client` INT(1) NOT NULL, 
		`r_change_servergroup` INT(1) NOT NULL, 
		`r_view_clientdetails` INT(1) NOT NULL, 
		`r_edit_clientdetails` INT(1) NOT NULL, 
		`r_view_bans` INT(1) NOT NULL, 
		`r_remove_bans` INT(1) NOT NULL, 
		`r_view_complaints` INT(1) NOT NULL, 
		`r_remove_complaints` INT(1) NOT NULL, 
		`r_view_log` INT(1) NOT NULL, 
		`r_view` INT(1) NOT NULL, 
		`r_edit_channel` INT(1) NOT NULL, 
		`r_remove_channel` INT(1) NOT NULL, 
		`r_add_channel` INT(1) NOT NULL, 
		`r_move_client` INT(1) NOT NULL,  
		`r_change_channelgroup` INT(1) NOT NULL
		) ENGINE = MYISAM ;";
	$db->query($sql);
	
	$sql = "ALTER TABLE `" . MYSQL_TABLE_PREFIX . "ts3admin_vserver_rights` ADD PRIMARY KEY ( `serverid` , `vserverid` , `type` , `uid` ) ;";
	$db->query($sql);
	
	//create table for ts3 server box
	$sql = " CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "ts3admin_server_box` (
		`serverid` INT NOT NULL ,
		`vserverid` INT NOT NULL ,
		`show` INT( 1 ) NOT NULL ,
		`join` INT( 1 ) NOT NULL 
		) ENGINE = MYISAM ;";
	$db->query($sql);
	
	$sql = "ALTER TABLE `" . MYSQL_TABLE_PREFIX . "ts3admin_server_box` ADD PRIMARY KEY ( `serverid` , `vserverid` ) ;";
	$db->query($sql);
	
	$rights->registerRight('ts3admin', 'manage_rights');
	$rights->registerRight('ts3admin', 'manage_servers');
	$rights->registerRight('ts3admin', 'manage_box');
	
?>
