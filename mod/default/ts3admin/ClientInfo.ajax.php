<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if($ts3vserver_rights["r_view_clientdetails"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	// Get client object
	$cuid = $_GET["cid"];
	$clients = $ts3server->clientList();
	$client = NULL;
	foreach($clients as $clt) {
		if($clt["client_unique_identifier"]==$cuid) {
			$client=$clt;
			break;
		}
	}
	
	if($client==NULL) {
		$notify->raiseError("",$lang->get("cantfindclient"));	
		$smarty->display('../mod/default/ts3admin/chooseVServer.tpl');
		die();	
	}
	
	if(isset($_GET["save"])) {
		if($ts3vserver_rights["r_edit_clientdetails"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
		
		$modi = array();
		if (isset($_GET["nickname"])) $modi["client_nickname"]=$_GET["nickname"];
		if (isset($_GET["istalker"])) $modi["client_is_talker"]=$_GET["istalker"];
		if (isset($_GET["description"])) $modi["client_description"]=$_GET["description"];
		
		if (count($modi)>0) {
			$client->modifyDb($modi);
		}
	}
	
	$queryclient = false;
	try {
		$try = $client["client_default_channel"];	
	}catch (Exception $e) {
		$queryclient = true;
	}
	
	
	$smarty->assign('client_unique_identifier', 						$client["client_unique_identifier"]);
	$smarty->assign('client_nickname', 									$client["client_nickname"]);
	$smarty->assign('client_version', 									$client["client_version"]);
	$smarty->assign('client_platform', 									$client["client_platform"]);
	$smarty->assign('client_input_muted', 								$client["client_input_muted"]);
	$smarty->assign('client_output_muted', 								$client["client_output_muted"]);
	$smarty->assign('client_input_hardware', 							$client["client_input_hardware"]);
	$smarty->assign('client_output_hardware', 							$client["client_output_hardware"]);
	$smarty->assign('client_database_id', 								$client["client_database_id"]);
	$smarty->assign('client_created', 									$client["client_created"]);
	$smarty->assign('client_away', 										$client["client_away"]);
	$smarty->assign('client_away_message', 								$client["client_away_message"]);
	$smarty->assign('client_talk_power', 								$client["client_talk_power"]);
	$smarty->assign('client_is_talker', 								$client["client_is_talker"]);
	$smarty->assign('client_is_priority_speaker', 						$client["client_is_priority_speaker"]);
	$smarty->assign('client_lastconnected', 							$client["client_lastconnected"]);
	$smarty->assign('client_icon_id', 									$client["client_icon_id"]);
	
	if(!$queryclient) {
		$smarty->assign('client_default_channel', 							$client["client_default_channel"]);
		$smarty->assign('client_login_name', 								$client["client_login_name"]);
		$smarty->assign('client_flag_avatar', 								$client["client_flag_avatar"]);
		$smarty->assign('client_talk_request', 								$client["client_talk_request"]);
		$smarty->assign('client_talk_request_msg', 							$client["client_talk_request_msg"]); 
		$smarty->assign('client_month_bytes_downloaded', 					$client["client_month_bytes_downloaded"]);
		$smarty->assign('client_month_bytes_uploaded', 						$client["client_month_bytes_uploaded"]);
		$smarty->assign('client_total_bytes_downloaded', 					$client["client_total_bytes_downloaded"]);
		$smarty->assign('client_total_bytes_uploaded', 						$client["client_total_bytes_uploaded"]);
		$smarty->assign('client_unread_messages', 							$client["client_unread_messages"]);
		$smarty->assign('client_nickname_phonetic', 						$client["client_nickname_phonetic"]);
		$smarty->assign('client_description', 								$client["client_description"]);
		$smarty->assign('client_needed_serverquery_view_power', 			$client["client_needed_serverquery_view_power"]);
		$smarty->assign('client_totalconnections', 							$client["client_totalconnections"]);
		$smarty->assign('connection_filetransfer_bandwidth_sent', 			$client["connection_filetransfer_bandwidth_sent"]);
		$smarty->assign('connection_filetransfer_bandwidth_received', 		$client["connection_filetransfer_bandwidth_received"]);
		$smarty->assign('connection_packets_sent_total', 					$client["connection_packets_sent_total"]);
		$smarty->assign('connection_packets_received_total', 				$client["connection_packets_received_total"]);
		$smarty->assign('connection_bytes_sent_total', 						$client["connection_bytes_sent_total"]);
		$smarty->assign('connection_bytes_received_total', 					$client["connection_bytes_received_total"]);
	}
	
	
	
	// Useless
	//
	//$smarty->assign('connection_bandwidth_sent_last_second_total', 		$client["connection_bandwidth_sent_last_second_total"]);
	//$smarty->assign('connection_bandwidth_sent_last_minute_total', 		$client["connection_bandwidth_sent_last_minute_total"]);
	//$client["connection_bandwidth_received_last_second_total"]);
	//$client["connection_bandwidth_received_last_minute_total"]);
	
	$smarty->assign('cuid',$cuid);
	$smarty->assign('queryclient',$queryclient);
	$smarty->display('../mod/default/ts3admin/ClientInfo.tpl');
?>