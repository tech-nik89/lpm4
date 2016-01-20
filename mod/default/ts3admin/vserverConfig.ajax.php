<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if (isset($_GET["save"])) {
		if($ts3vserver_rights["r_edit_vserver"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
		
		$modi = array();
		if (isset($_GET["vservername"])) $modi["virtualserver_name"]=$_GET["vservername"];
		if (isset($_GET["vserverport"])) $modi["virtualserver_port"]=$_GET["vserverport"];
		if (isset($_GET["vservermaxclients"])) $modi["virtualserver_maxclients"]=$_GET["vservermaxclients"];
		if (isset($_GET["vserverautostart"])) $modi["virtualserver_autostart"]=$_GET["vserverautostart"];
		if (isset($_GET["vserverphoneticname"])) $modi["virtualserver_name_phonetic"]=$_GET["vserverphoneticname"];
		if (isset($_GET["vserverbannerurl"])) $modi["virtualserver_hostbanner_url"]=$_GET["vserverbannerurl"];
		if (isset($_GET["vserverbannergfx"])) $modi["virtualserver_hostbanner_gfx_url"]=$_GET["vserverbannergfx"];
		if (isset($_GET["vserverbannerinterval"])) $modi["virtualserver_hostbanner_gfx_interval"]=$_GET["vserverbannerinterval"];
		if (isset($_GET["vserverpassw"])) $modi["virtualserver_password"]=$_GET["vserverpassw"];
		if (isset($_GET["vserverhbuttontooltip"])) $modi["virtualserver_hostbutton_tooltip"]=$_GET["vserverhbuttontooltip"];
		if (isset($_GET["vserverhbuttongfx"])) $modi["virtualserver_hostbutton_gfx_url"]=$_GET["vserverhbuttongfx"];
		if (isset($_GET["vserverbuttonurl"])) $modi["virtualserver_hostbutton_url"]=$_GET["vserverbuttonurl"];
		if (isset($_GET["vserverdlquota"])) $modi["virtualserver_download_quota"]=$_GET["vserverdlquota"];
		if (isset($_GET["vserverulquota"])) $modi["virtualserver_upload_quota"]=$_GET["vserverulquota"];
		if (isset($_GET["vserverwkmsg"])) $modi["virtualserver_welcomemessage"]=$_GET["vserverwkmsg"];
		if (isset($_GET["vserverhostmsg"])) $modi["virtualserver_hostmessage"]=$_GET["vserverhostmsg"];
		if (isset($_GET["vserverhostmsgmode"])) $modi["virtualserver_hostmessage_mode"]=$_GET["vserverhostmsgmode"];
		if (isset($_GET["vserverdimmmod"])) $modi["virtualserver_priority_speaker_dimm_modificator"]=$_GET["vserverdimmmod"];
		if (isset($_GET["vserverminclientversion"])) $modi["virtualserver_min_client_version"]=$_GET["vserverminclientversion"];
		if (isset($_GET["vserversecuritylevel"])) $modi["virtualserver_needed_identity_security_level"]=$_GET["vserversecuritylevel"];
		if (isset($_GET["vserverreservedslots"])) $modi["virtualserver_reserved_slots"]=$_GET["vserverreservedslots"];
		if (isset($_GET["vserverforcesilence"])) $modi["virtualserver_min_clients_in_channel_before_forced_silence"]=$_GET["vserverforcesilence"];
		if (isset($_GET["vservercomplaincount"])) $modi["virtualserver_complain_autoban_count"]=$_GET["vservercomplaincount"];
		if (isset($_GET["vservercomplaintime"])) $modi["virtualserver_complain_autoban_time"]=$_GET["vservercomplaintime"];
		if (isset($_GET["vservercomplainremove"])) $modi["virtualserver_complain_remove_time"]=$_GET["vservercomplainremove"];
		if (isset($_GET["vserverpointsreduce"])) $modi["virtualserver_antiflood_points_tick_reduce"]=$_GET["vserverpointsreduce"];
		if (isset($_GET["vserverpointswarning"])) $modi["virtualserver_antiflood_points_needed_warning"]=$_GET["vserverpointswarning"];
		if (isset($_GET["vserverpointskick"])) $modi["virtualserver_antiflood_points_needed_kick"]=$_GET["vserverpointskick"];
		if (isset($_GET["vserverpointsban"])) $modi["virtualserver_antiflood_points_needed_ban"]=$_GET["vserverpointsban"];
		if (isset($_GET["vserverbantime"])) $modi["virtualserver_antiflood_ban_time"]=$_GET["vserverbantime"];
		if (isset($_GET["vserverlogclient"])) $modi["virtualserver_log_client"]=$_GET["vserverlogclient"];
		if (isset($_GET["vserverlogquery"])) $modi["virtualserver_log_query"]=$_GET["vserverlogquery"];
		if (isset($_GET["vserverlogchannel"])) $modi["virtualserver_log_channel"]=$_GET["vserverlogchannel"];
		if (isset($_GET["vserverlogperm"])) $modi["virtualserver_log_permissions"]=$_GET["vserverlogperm"];
		if (isset($_GET["vserverlogserver"])) $modi["virtualserver_log_server"]=$_GET["vserverlogserver"];
		if (isset($_GET["vserverlogft"])) $modi["virtualserver_log_filetransfer"]=$_GET["vserverlogft"];
		
		if (count($modi)>0) {
			$ts3server->modify($modi);
		}
		
	}else if (isset($_GET["stop"])) {		
		if($ts3vserver_rights["r_control_vserver"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
		
		$ts3server->stop();
		sleep(2);
		$ts3server = GetVServer($ts3_ServerInstance, $vsid);
		
	}else if (isset($_GET["start"])) {
		if($ts3vserver_rights["r_control_vserver"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
		
		$ts3server->start();
		sleep(2);
		$ts3server = GetVServer($ts3_ServerInstance, $vsid);
	}
	
	$smarty->assign('virtualserver_name', 											$ts3server["virtualserver_name"]);
	$smarty->assign('virtualserver_welcomemessage', 								$ts3server["virtualserver_welcomemessage"]);
	$smarty->assign('virtualserver_maxclients', 									$ts3server["virtualserver_maxclients"]);
	$smarty->assign('virtualserver_password', 										$ts3server["virtualserver_password"]);
	$smarty->assign('virtualserver_flag_password', 									$ts3server["virtualserver_flag_password"]);
	$smarty->assign('virtualserver_clientsonline', 									$ts3server["virtualserver_clientsonline"]);
	$smarty->assign('virtualserver_queryclientsonline', 							$ts3server["virtualserver_queryclientsonline"]);
	$smarty->assign('virtualserver_channelsonline', 								$ts3server["virtualserver_channelsonline"]);
	$smarty->assign('virtualserver_created', 										$ts3server["virtualserver_created"]);
	$smarty->assign('virtualserver_uptime', 										SecToStr($ts3server["virtualserver_uptime"]));
	$smarty->assign('virtualserver_hostmessage', 									$ts3server["virtualserver_hostmessage"]);
	$smarty->assign('virtualserver_hostmessage_mode', 								$ts3server["virtualserver_hostmessage_mode"]);
	$smarty->assign('virtualserver_platform', 										$ts3server["virtualserver_platform"]);
	$smarty->assign('virtualserver_version', 										$ts3server["virtualserver_version"]);
	$smarty->assign('virtualserver_hostbanner_url', 								$ts3server["virtualserver_hostbanner_url"]);
	$smarty->assign('virtualserver_hostbanner_gfx_url', 							$ts3server["virtualserver_hostbanner_gfx_url"]);
	$smarty->assign('virtualserver_hostbanner_gfx_interval', 						$ts3server["virtualserver_hostbanner_gfx_interval"]);
	$smarty->assign('virtualserver_complain_autoban_count', 						$ts3server["virtualserver_complain_autoban_count"]);
	$smarty->assign('virtualserver_complain_autoban_time', 							$ts3server["virtualserver_complain_autoban_time"]);
	$smarty->assign('virtualserver_complain_remove_time', 							$ts3server["virtualserver_complain_remove_time"]);
	$smarty->assign('virtualserver_min_clients_in_channel_before_forced_silence', 	$ts3server["virtualserver_min_clients_in_channel_before_forced_silence"]);
	$smarty->assign('virtualserver_priority_speaker_dimm_modificator', 				$ts3server["virtualserver_priority_speaker_dimm_modificator"]);
	$smarty->assign('virtualserver_antiflood_points_tick_reduce', 					$ts3server["virtualserver_antiflood_points_tick_reduce"]);
	$smarty->assign('virtualserver_antiflood_points_needed_warning', 				$ts3server["virtualserver_antiflood_points_needed_warning"]);
	$smarty->assign('virtualserver_antiflood_points_needed_kick', 					$ts3server["virtualserver_antiflood_points_needed_kick"]);
	$smarty->assign('virtualserver_antiflood_points_needed_ban', 					$ts3server["virtualserver_antiflood_points_needed_ban"]);
	$smarty->assign('virtualserver_antiflood_ban_time', 							$ts3server["virtualserver_antiflood_ban_time"]);
	$smarty->assign('virtualserver_client_connections', 							$ts3server["virtualserver_client_connections"]);
	$smarty->assign('virtualserver_query_client_connections', 						$ts3server["virtualserver_query_client_connections"]);
	$smarty->assign('virtualserver_hostbutton_tooltip', 							$ts3server["virtualserver_hostbutton_tooltip"]);
	$smarty->assign('virtualserver_hostbutton_gfx_url', 							$ts3server["virtualserver_hostbutton_gfx_url"]);
	$smarty->assign('virtualserver_hostbutton_url', 								$ts3server["virtualserver_hostbutton_url"]);
	$smarty->assign('virtualserver_download_quota', 								$ts3server["virtualserver_download_quota"]);
	$smarty->assign('virtualserver_upload_quota', 									$ts3server["virtualserver_upload_quota"]);
	$smarty->assign('virtualserver_month_bytes_downloaded', 						$ts3server["virtualserver_month_bytes_downloaded"]);
	$smarty->assign('virtualserver_month_bytes_uploaded', 							$ts3server["virtualserver_month_bytes_uploaded"]);
	$smarty->assign('virtualserver_total_bytes_downloaded', 						$ts3server["virtualserver_total_bytes_downloaded"]);	
	$smarty->assign('virtualserver_total_bytes_uploaded', 							$ts3server["virtualserver_total_bytes_uploaded"]);
	$smarty->assign('virtualserver_unique_identifier', 								$ts3server["virtualserver_unique_identifier"]);
	$smarty->assign('virtualserver_id', 											$ts3server["virtualserver_id"]);
	$smarty->assign('virtualserver_port', 											$ts3server["virtualserver_port"]);
	$smarty->assign('virtualserver_autostart',									 	$ts3server["virtualserver_autostart"]);
	$smarty->assign('connection_filetransfer_bandwidth_sent', 						$ts3server["connection_filetransfer_bandwidth_sent"]);
	$smarty->assign('connection_filetransfer_bandwidth_received',				 	$ts3server["connection_filetransfer_bandwidth_received"]);
	$smarty->assign('connection_packets_sent_total', 								$ts3server["connection_packets_sent_total"]);
	$smarty->assign('connection_packets_received_total', 							$ts3server["connection_packets_received_total"]);
	$smarty->assign('connection_bytes_sent_total',								 	$ts3server["connection_bytes_sent_total"]);
	$smarty->assign('connection_bytes_received_total', 								$ts3server["connection_bytes_received_total"]);
	$smarty->assign('virtualserver_status', 										$ts3server["virtualserver_status"]);
	$smarty->assign('virtualserver_log_client', 									$ts3server["virtualserver_log_client"]);
	$smarty->assign('virtualserver_log_query', 										$ts3server["virtualserver_log_query"]);
	$smarty->assign('virtualserver_log_channel', 									$ts3server["virtualserver_log_channel"]);
	$smarty->assign('virtualserver_log_permissions', 								$ts3server["virtualserver_log_permissions"]);
	$smarty->assign('virtualserver_log_server', 									$ts3server["virtualserver_log_server"]);
	$smarty->assign('virtualserver_log_filetransfer', 								$ts3server["virtualserver_log_filetransfer"]);
	$smarty->assign('virtualserver_min_client_version', 							$ts3server["virtualserver_min_client_version"]);
	$smarty->assign('virtualserver_needed_identity_security_level', 				$ts3server["virtualserver_needed_identity_security_level"]);
	$smarty->assign('virtualserver_name_phonetic', 									$ts3server["virtualserver_name_phonetic"]);
	$smarty->assign('virtualserver_icon_id', 										$ts3server["virtualserver_icon_id"]);
	$smarty->assign('virtualserver_reserved_slots', 								$ts3server["virtualserver_reserved_slots"]);

	$smarty->assign('virtualserver_default_server_group', 							$ts3server["virtualserver_default_server_group"]);
	$smarty->assign('virtualserver_default_channel_group', 							$ts3server["virtualserver_default_channel_group"]);
	$smarty->assign('virtualserver_default_channel_admin_group', 					$ts3server["virtualserver_default_channel_admin_group"]);
	
	
	// Useless
	//
	//$smarty->assign('connection_bandwidth_sent_last_second_total',					$ts3server["connection_bandwidth_sent_last_second_total"]);
	//$smarty->assign('connection_bandwidth_received_last_second_total',			 	$ts3server["connection_bandwidth_received_last_second_total"]);
	//$smarty->assign('connection_bandwidth_sent_last_minute_total', 					$ts3server["connection_bandwidth_sent_last_minute_total"]);
	//$smarty->assign('connection_bandwidth_received_last_minute_total',			 	$ts3server["connection_bandwidth_received_last_minute_total"])
	
	$smarty->display('../mod/default/ts3admin/vserverConfig.tpl');
?>