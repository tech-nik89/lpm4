<?php
	//Does some init
	
	$tbl = MYSQL_TABLE_PREFIX . 'ts3admin';
	
	
	$lang->addModSpecificLocalization('ts3admin');
	$smarty->assign('lang', $lang->getAll());
	
	$smarty->assign('imgsrc', 'mod/default/ts3admin/images');
	
	/* load framework library */
	require_once("TeamSpeak3/TeamSpeak3.php");
	/* initialize */
	TeamSpeak3::init();
	$smarty->assign('ts3lib_version', TeamSpeak3::LIB_VERSION);
	
	
	//Defaults
	$sid = -1;
	$vsid = -1;
	$server_data = NULL;
	$ts3_ServerInstance = NULL;
	$ts3server = NULL;
	
	// ts3server rights
	//default rights
	$ts3server_rights  = array("r_view_server"=>0, "r_edit_server"=>0, "r_add_vservers"=>0, "r_remove_vservers"=>0);
	$ts3vserver_rights = array("r_view_vserver"=>0, "r_control_vserver"=>0, "r_edit_vserver"=>0, "r_view_grouprights"=>0, "r_edit_grouprights"=>0, "r_rename_group"=>0, "r_add_group"=>0
							, "r_remove_group"=>0, "r_view_clients"=>0, "r_msg_client"=>0, "r_kick_client"=>0, "r_ban_client"=>0, "r_change_servergroup"=>0, "r_view_clientdetails"=>0
							, "r_edit_clientdetails"=>0, "r_view_bans"=>0, "r_remove_bans"=>0, "r_view_complaints"=>0, "r_remove_complaints"=>0, "r_view_log"=>0, "r_view"=>0, "r_edit_channel"=>0
							, "r_remove_channel"=>0, "r_add_channel"=>0, "r_move_client"=>0, "r_change_channelgroup"=>0);
	
	// read rights
	if (isset($_GET["sid"])) {
		$ts3server_rights = getTS3Rights($_GET["sid"],-1);
		if (isset($_GET["vsid"])) {
			$ts3vserver_rights = getTS3Rights($_GET["sid"],$_GET["vsid"]);
		}
	}
	
	$smarty->assign('ts3server_rights',  $ts3server_rights);
	$smarty->assign('ts3vserver_rights', $ts3vserver_rights);
	
	///////////////////////////////
	// functions
	///////////////////////////////
	
	function getTS3Rights($sid, $vsid) {
		global $login, $db, $rights;
		if ($vsid==-1) {
			$fields = "MAX(r_view_server) as r_view_server, MAX(r_edit_server) as r_edit_server, MAX(r_add_vservers) as r_add_vservers, MAX(r_remove_vservers) as r_remove_vservers"; 
			
			$where = "serverid=".$sid." and ((type=1 and uid=".$login->currentUserID().") or";
			foreach($rights->getGroups($login->currentUserID()) as $grp) {
				$where .= "(type=0 and uid=".$grp["groupid"].") or";	
			}
			$where = substr($where,0,strlen($where)-2);
			
			$where .= ") GROUP BY uid";
			return $db->selectOneRow(MYSQL_TABLE_PREFIX."ts3admin_server_rights", $fields, $where);		
		} else {
			$fields = "MAX(r_view_vserver) as r_view_vserver, MAX(r_control_vserver) as r_control_vserver, MAX(r_edit_vserver) as r_edit_vserver, MAX(r_view_grouprights) as r_view_grouprights,
					MAX(r_edit_grouprights) as r_edit_grouprights, MAX(r_rename_group) as r_rename_group, MAX(r_add_group) as r_add_group, MAX(r_remove_group) as r_remove_group,
					MAX(r_view_clients) as r_view_clients, MAX(r_msg_client) as r_msg_client, MAX(r_kick_client) as r_kick_client, MAX(r_ban_client) as r_ban_client,
					MAX(r_change_servergroup) as r_change_servergroup, MAX(r_view_clientdetails) as r_view_clientdetails, MAX(r_edit_clientdetails) as r_edit_clientdetails,
					MAX(r_view_bans) as r_view_bans, MAX(r_remove_bans) as r_remove_bans, MAX(r_view_complaints) as r_view_complaints, MAX(r_remove_complaints) as r_remove_complaints,
					MAX(r_view_log) as r_view_log, MAX(r_view) as r_view, MAX(r_edit_channel) as r_edit_channel, MAX(r_remove_channel) as r_remove_channel, MAX(r_add_channel) as r_add_channel,
					MAX(r_move_client) as r_move_client, MAX(r_change_channelgroup) as r_change_channelgroup"; 
			
			$where = "serverid=".$sid." and vserverid=".$vsid." and ((type=1 and uid=".$login->currentUserID().") or";
			foreach($rights->getGroups($login->currentUserID()) as $grp) {
				$where .= "(type=0 and uid=".$grp["groupid"].") or";	
			}
			$where = substr($where,0,strlen($where)-2);
			
			$where .= ") GROUP BY uid";
			return $db->selectOneRow(MYSQL_TABLE_PREFIX."ts3admin_vserver_rights", $fields, $where);
		}
	}
	
	function checkStdRightIssues() {
		global $smarty, $ts3server_rights, $ts3vserver_rights;
		if($ts3server_rights["r_view_server"]!=1 || $ts3vserver_rights["r_view_vserver"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
	}
	
	function getSID() {
		global $db, $ts3_ServerInstance, $smarty, $tbl, $notify, $sid, $lang, $server_data;
		if (!isset($_GET["sid"])) {
			$notify->raiseError("",$lang->get("wrong_server_id"));	
			$smarty->display('../mod/default/ts3admin/chooseVServer.tpl');
			die();
		}
		$sid = $_GET["sid"];
		
		$server_data = $db->selectOneRow($tbl . "_servers","address,query,usr,pw","ID=".$sid);
		try {
			$ts3_ServerInstance = TeamSpeak3::factory("serverquery://".$server_data["usr"].":".$server_data["pw"]."@".$server_data["address"].":".$server_data["query"]."/?use_offline_as_virtual=1");
		}catch(Exception $e) {}
		
		if ($ts3_ServerInstance == NULL) {
			$notify->raiseError("",$lang->get("connection_failed"));	
			$smarty->display('../mod/default/ts3admin/chooseVServer.tpl');
			die();
		}
		
		//don't get baned
		$cmds = $ts3_ServerInstance["serverinstance_serverquery_flood_commands"];
		$time = $ts3_ServerInstance["serverinstance_serverquery_flood_time"];
		$minAnitFlood=200;
		if($cmds/$time<$minAnitFlood) {
			$modi = array("serverinstance_serverquery_flood_commands"=>(int)($minAnitFlood*$time));
			$notify->add("",$lang->get("server_flood_edited"));
			$ts3_ServerInstance->modify($modi);
		}
		$smarty->assign('sid', $sid);
	}
	
	function getVSID() {
		global $ts3_ServerInstance, $smarty, $tbl, $notify, $vsid, $lang, $ts3server;
		if (!isset($_GET["vsid"])) {
			$notify->raiseError("",$lang->get("wrong_server_id"));	
			$smarty->display('../mod/default/ts3admin/chooseVServer.tpl');
			die();
		}
		$vsid = $_GET["vsid"];
		
		$ts3server = GetVServer($ts3_ServerInstance, $vsid);
		$smarty->assign('vsid', $vsid);	
	}
	
	
	function GetVServer($ts3_ServerInstance, $vsid) {
		global $ts3_ServerInstance, $smarty, $notify, $lang;
		$ts3server = NULL;
		
		foreach($ts3_ServerInstance as $ts3_VirtualServer)
		{
			try {
				$try=$ts3_VirtualServer["virtualserver_name"];
				if($ts3_VirtualServer["virtualserver_id"]==$vsid) {
					$ts3server = $ts3_VirtualServer;
					break;
				}
			}catch(Exception $e) {}
		}
		
		if ($ts3server == NULL) {
			$notify->raiseError("",$lang->get("connection_failed"));	
			$smarty->display('../mod/default/ts3admin/chooseVServer.tpl');
			die();
		}
		
		return 	$ts3server;
	}
	
	
	function SecToStr($totalsec) {
		global $lang;
		$sec = $totalsec%60;
		$totalmin = ($totalsec-$sec)/60;
		$ret = $sec;
		if ($sec==1)
			$ret = $ret." ".$lang->get("second");
		else
			$ret = $ret." ".$lang->get("seconds");
			
		if ($totalmin > 0) {
			$min = $totalmin%60;
			$totalhr = ($totalmin-$min)/60;
			if ($min==1)
				$ret = $min." ".$lang->get("minute")." ".$ret;
			else
				$ret = $min." ".$lang->get("minutes")." ".$ret;
			
			if ($totalhr > 0) {
				$hr = $totalhr%24;
				$totalday = ($totalhr-$hr)/24;
				if ($hr==1)
					$ret = $hr." ".$lang->get("hour")." ".$ret;
				else
					$ret = $hr." ".$lang->get("hours")." ".$ret;
				
				if ($totalday > 0) {
					$day = $totalday%30;
					$totalmon = ($totalday-$day)/30;
					if ($day==1)
						$ret = $day." ".$lang->get("day")." ".$ret;
					else
						$ret = $day." ".$lang->get("days")." ".$ret;
				
					if ($totalmon > 0) {
						$mon = $totalmon%12;
						$totalyr = ($totalmon-$mon)/12;
						if ($mon==1)
							$ret = $mon." ".$lang->get("month")." ".$ret;
						else
							$ret = $mon." ".$lang->get("months")." ".$ret;
						
						if ($totalyr > 0) {
							if ($totalyr==1)
								$ret = $totalyr." ".$lang->get("year")." ".$ret;
							else
								$ret = $totalyr." ".$lang->get("years")." ".$ret;
						}
					}
				}	
			}	
		}		
		return $ret;
	}
	
	function selectFromQuery($sql){
		global $db;
		$list = array();
		
		// puzzle the query string
		$result = $db->query($sql);
		
		// append all rows to an array
		while ($row = mysql_fetch_assoc($result)) {
			$list[] = $row;
		}

		return $list;
	}
	
?>