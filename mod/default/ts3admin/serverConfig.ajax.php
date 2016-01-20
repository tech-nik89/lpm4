<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if (isset($_GET["save"])) {
		if($ts3server_rights["r_edit_server"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
		
		$modi = array();	
		if(isset($_GET["port"])) {
			$modi["serverinstance_filetransfer_port"]=$_GET["port"];
		}
		if(isset($_GET["floodcmds"])) {
			$modi["serverinstance_serverquery_flood_commands"]=$_GET["floodcmds"];
		}
		if(isset($_GET["floodtime"])) {
			$modi["serverinstance_serverquery_flood_time"]=$_GET["floodtime"];
		}
		if(isset($_GET["bantime"])) {
			$modi["serverinstance_serverquery_ban_time"]=$_GET["bantime"];
		}
		if(isset($_GET["tplcagrp"])) {
			$modi["serverinstance_template_channeladmin_group"]=$_GET["tplcagrp"];
		}
		if(isset($_GET["tplcdgrp"])) {
			$modi["serverinstance_template_channeldefault_group"]=$_GET["tplcdgrp"];
		}
		if(isset($_GET["tplsdgrp"])) {
			$modi["serverinstance_template_serverdefault_group"]=$_GET["tplsdgrp"];
		}
		if(isset($_GET["tplsagrp"])) {
			$modi["serverinstance_template_serveradmin_group"]=$_GET["tplsagrp"];
		}
		if(isset($_GET["gsqgrp"])) {
			$modi["serverinstance_guest_serverquery_group"]=$_GET["gsqgrp"];
		}
		
		$ts3_ServerInstance->modify($modi);
	}
	
	
	$groups = $ts3server->serverGroupList(array());
	$smarty->assign('groups', $groups);
	$smarty->assign('grouptypes', array($lang->get("type0_template"),$lang->get("type1_clients"),$lang->get("type2_query")));
	
	
	$smarty->assign('instance_uptime', 									SecToStr($ts3_ServerInstance["instance_uptime"]));
	$smarty->assign('host_timestamp_utc', 								$ts3_ServerInstance["host_timestamp_utc"]);
	$smarty->assign('virtualservers_running_total', 					$ts3_ServerInstance["virtualservers_running_total"]);
	$smarty->assign('serverinstance_database_version', 					$ts3_ServerInstance["serverinstance_database_version"]);
	$smarty->assign('connection_filetransfer_bandwidth_sent', 			$ts3_ServerInstance["connection_filetransfer_bandwidth_sent"]);
	$smarty->assign('connection_filetransfer_bandwidth_received', 		$ts3_ServerInstance["connection_filetransfer_bandwidth_received"]);
	$smarty->assign('connection_packets_sent_total', 					$ts3_ServerInstance["connection_packets_sent_total"]);
	$smarty->assign('connection_packets_received_total', 				$ts3_ServerInstance["connection_packets_received_total"]);
	$smarty->assign('connection_bytes_sent_total', 						$ts3_ServerInstance["connection_bytes_sent_total"]);
	$smarty->assign('connection_bytes_received_total',		 			$ts3_ServerInstance["connection_bytes_received_total"]);
	$smarty->assign('serverinstance_guest_serverquery_group', 			$ts3_ServerInstance["serverinstance_guest_serverquery_group"]);
	$smarty->assign('serverinstance_template_serveradmin_group', 		$ts3_ServerInstance["serverinstance_template_serveradmin_group"]);
	$smarty->assign('serverinstance_template_serverdefault_group', 		$ts3_ServerInstance["serverinstance_template_serverdefault_group"]);
	$smarty->assign('serverinstance_template_channeldefault_group', 	$ts3_ServerInstance["serverinstance_template_channeldefault_group"]);
	$smarty->assign('serverinstance_template_channeladmin_group', 		$ts3_ServerInstance["serverinstance_template_channeladmin_group"]);
	$smarty->assign('serverinstance_filetransfer_port', 				$ts3_ServerInstance["serverinstance_filetransfer_port"]);
	$smarty->assign('serverinstance_serverquery_flood_commands', 		$ts3_ServerInstance["serverinstance_serverquery_flood_commands"]);
	$smarty->assign('serverinstance_serverquery_flood_time', 			$ts3_ServerInstance["serverinstance_serverquery_flood_time"]);
	$smarty->assign('serverinstance_serverquery_ban_time', 				$ts3_ServerInstance["serverinstance_serverquery_ban_time"]);
	$smarty->assign('virtualservers_total_maxclients', 					$ts3_ServerInstance["virtualservers_total_maxclients"]);
	$smarty->assign('virtualservers_total_clients_online', 				$ts3_ServerInstance["virtualservers_total_clients_online"]);
	$smarty->assign('virtualservers_total_channels_online', 			$ts3_ServerInstance["virtualservers_total_channels_online"]);
	
	
	// Useless
	//$smarty->assign('connection_bandwidth_sent_last_second_total', 		$ts3_ServerInstance["connection_bandwidth_sent_last_second_total"]);
	//$smarty->assign('connection_bandwidth_received_last_second_total', 	$ts3_ServerInstance["connection_bandwidth_received_last_second_total"]);
	//$smarty->assign('connection_bandwidth_sent_last_minute_total', 		$ts3_ServerInstance["connection_bandwidth_sent_last_minute_total"]);
	//$smarty->assign('connection_bandwidth_received_last_minute_total', 	$ts3_ServerInstance["connection_bandwidth_received_last_minute_total"]);
	
	// Not working
	//
	//$smarty->assign('serverinstance_max_upload_total_bandwitdh', 		$ts3_ServerInstance["serverinstance_max_upload_total_bandwitdh"]);
	//$smarty->assign('serverinstance_max_download_total_bandwitdh', 		$ts3_ServerInstance["serverinstance_max_download_total_bandwitdh"]);
	
	
	$smarty->display('../mod/default/ts3admin/serverConfig.tpl');
?>