<?php
	//init
	require_once("libraries/ts3init.php");
	
	$manage_rights_allowed = $rights->isallowed("ts3admin", 'manage_rights');
	if(!$manage_rights_allowed) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	$action = $_GET["action"];
	$type = $_GET["type"];
	$sid = $_GET["sid"];	
	$vsid = $_GET["vsid"];
	$uid = $_GET["uid"];
	
	if($action=="remove") {
		if($type=="user" && $vsid==-1)
			$db->query("DELETE FROM `".MYSQL_TABLE_PREFIX."ts3admin_server_rights` WHERE `".MYSQL_TABLE_PREFIX."ts3admin_server_rights`.`serverid` = ".
				$sid." AND `".MYSQL_TABLE_PREFIX."ts3admin_server_rights`.`type` = 1 AND `".MYSQL_TABLE_PREFIX."ts3admin_server_rights`.`uid` = ".$uid." LIMIT 1;");
		else if($type=="group" && $vsid==-1)
			$db->query("DELETE FROM `".MYSQL_TABLE_PREFIX."ts3admin_server_rights` WHERE `".MYSQL_TABLE_PREFIX."ts3admin_server_rights`.`serverid` = ".
				$sid." AND `".MYSQL_TABLE_PREFIX."ts3admin_server_rights`.`type` = 0 AND `".MYSQL_TABLE_PREFIX."ts3admin_server_rights`.`uid` = ".$uid." LIMIT 1;");
		
		else if($type=="user")
			$db->query("DELETE FROM `".MYSQL_TABLE_PREFIX."ts3admin_vserver_rights` WHERE `".MYSQL_TABLE_PREFIX."ts3admin_vserver_rights`.`serverid` = ".
				$sid." AND `".MYSQL_TABLE_PREFIX."ts3admin_vserver_rights`.`type` = 1 AND `".MYSQL_TABLE_PREFIX."ts3admin_vserver_rights`.`uid` = ".$uid." LIMIT 1;");
		else if($type=="group")
			$db->query("DELETE FROM `".MYSQL_TABLE_PREFIX."ts3admin_vserver_rights` WHERE `".MYSQL_TABLE_PREFIX."ts3admin_vserver_rights`.`serverid` = ".
				$sid." AND `".MYSQL_TABLE_PREFIX."ts3admin_vserver_rights`.`type` = 0 AND `".MYSQL_TABLE_PREFIX."ts3admin_vserver_rights`.`uid` = ".$uid.
				" AND `".MYSQL_TABLE_PREFIX."ts3admin_vserver_rights`.`vserverid` = ".$vsid." LIMIT 1;");
		$action="refresh";
	}else if($action=="askadd") {
		if($type=="user") {
			$name = $db->selectOne(MYSQL_TABLE_PREFIX."users","nickname","userid=".$uid);
		} else {
 			$name = $db->selectOne(MYSQL_TABLE_PREFIX."groups","name","groupid=".$uid);
		}
		if($vsid==-1) {
			$rights = array("r_view_server"=>0, "r_edit_server"=>0, "r_add_vservers"=>0, "r_remove_vservers"=>0);
		}else{
			$rights = array("r_view_vserver"=>0, "r_control_vserver"=>0, "r_edit_vserver"=>0, "r_view_grouprights"=>0, "r_edit_grouprights"=>0, "r_rename_group"=>0, "r_add_group"=>0
							, "r_remove_group"=>0, "r_view_clients"=>0, "r_msg_client"=>0, "r_kick_client"=>0, "r_ban_client"=>0, "r_change_servergroup"=>0, "r_view_clientdetails"=>0
							, "r_edit_clientdetails"=>0, "r_view_bans"=>0, "r_remove_bans"=>0, "r_view_complaints"=>0, "r_remove_complaints"=>0, "r_view_log"=>0, "r_view"=>0, "r_edit_channel"=>0
							, "r_remove_channel"=>0, "r_add_channel"=>0, "r_move_client"=>0, "r_change_channelgroup"=>0);
		}
		
		$smarty->assign('name', $name);
		$smarty->assign('edit', true);
		$smarty->assign('rights', $rights);
		$action="add";
	}else if($action=="askedit") {
		if($type=="user") {
			$name = $db->selectOne(MYSQL_TABLE_PREFIX."users","nickname","userid=".$uid);
		} else {
 			$name = $db->selectOne(MYSQL_TABLE_PREFIX."groups","name","groupid=".$uid);
		}
		$typei=0;
		if($type=="user") $typei=1;
		if($vsid==-1) {
			$rights = $db->selectOneRow(MYSQL_TABLE_PREFIX."ts3admin_server_rights","r_view_server, r_edit_server, r_add_vservers, r_remove_vservers", "serverid=".$sid." and type=".$typei." and uid=".$uid);
		}else{
			$fields = "r_view_vserver, r_control_vserver, r_edit_vserver, r_view_grouprights, r_edit_grouprights, r_rename_group, r_add_group, r_remove_group, ".
						"r_view_clients, r_msg_client, r_kick_client, r_ban_client, r_change_servergroup, r_view_clientdetails, r_edit_clientdetails, r_view_bans, ".
						"r_remove_bans, r_view_complaints, r_remove_complaints, r_view_log, r_view, r_edit_channel, r_remove_channel, r_add_channel, r_move_client, r_change_channelgroup";
			$rights = $db->selectOneRow(MYSQL_TABLE_PREFIX."ts3admin_vserver_rights",$fields, "serverid=".$sid." and vserverid= ".$vsid." and type=".$typei." and uid=".$uid);
		}
		
		$smarty->assign('name', $name);
		$smarty->assign('edit', true);
		$smarty->assign('rights', $rights);
		$action="edit";
	
	
	
	}else if($action=="add") {
		$typei=0;
		if($type=="user") $typei=1;
		if($vsid==-1) {	
			$fields = array("serverid","type","uid", "r_view_server", "r_add_vservers", "r_remove_vservers", "r_edit_server");
			$values = array($sid,$typei,$uid, (int)($_GET["r0"]=="true"), (int)($_GET["r1"]=="true"), (int)($_GET["r2"]=="true"), (int)($_GET["r3"]=="true"));
			$db->insert(MYSQL_TABLE_PREFIX."ts3admin_server_rights",$fields,$values);
		}else{
			$fields = array("serverid","vserverid","type","uid", "r_view_vserver", "r_control_vserver", "r_edit_vserver", "r_view_grouprights", "r_edit_grouprights", "r_rename_group", "r_add_group", 
						"r_remove_group", "r_view_clients", "r_msg_client", "r_kick_client", "r_ban_client", "r_change_servergroup", "r_change_channelgroup", "r_view_clientdetails", 
						"r_edit_clientdetails", "r_view_bans", "r_remove_bans", "r_view_complaints", "r_remove_complaints", "r_view_log", "r_view", 
						"r_edit_channel", "r_remove_channel", "r_add_channel", "r_move_client");
			$values = array($sid,$vsid,$typei,$uid, (int)($_GET["r0"]=="true"), (int)($_GET["r1"]=="true"), (int)($_GET["r2"]=="true"), (int)($_GET["r3"]=="true"), (int)($_GET["r4"]=="true")
							, (int)($_GET["r5"]=="true"), (int)($_GET["r6"]=="true"), (int)($_GET["r7"]=="true"), (int)($_GET["r8"]=="true"), (int)($_GET["r9"]=="true")
							, (int)($_GET["r10"]=="true"), (int)($_GET["r11"]=="true"), (int)($_GET["r12"]=="true"), (int)($_GET["r13"]=="true"), (int)($_GET["r14"]=="true")
							, (int)($_GET["r15"]=="true"), (int)($_GET["r16"]=="true"), (int)($_GET["r17"]=="true"), (int)($_GET["r18"]=="true"), (int)($_GET["r19"]=="true")
							, (int)($_GET["r20"]=="true"), (int)($_GET["r21"]=="true"), (int)($_GET["r22"]=="true"), (int)($_GET["r23"]=="true"), (int)($_GET["r24"]=="true"), (int)($_GET["r25"]=="true"));
			$db->insert(MYSQL_TABLE_PREFIX."ts3admin_vserver_rights",$fields,$values);
		}
		
		
		
		$action="refresh";
	}else if($action=="edit") {
		$typei=0;
		if($type=="user") $typei=1;
		if($vsid==-1) {
			$fields = "r_view_server=".(int)($_GET["r0"]=="true").",r_add_vservers=".(int)($_GET["r1"]=="true").",r_remove_vservers=".(int)($_GET["r2"]=="true").
						",r_edit_server=".(int)($_GET["r3"]=="true");
			$db->update(MYSQL_TABLE_PREFIX."ts3admin_server_rights",$fields,"`serverid`=".$sid." AND `type`=".$typei." AND `uid`=".$uid);
		}else{
			$fields = "r_view_vserver=".(int)($_GET["r0"]=="true").",r_control_vserver=".(int)($_GET["r1"]=="true").",r_edit_vserver=".(int)($_GET["r2"]=="true")
			.",r_view_grouprights=".(int)($_GET["r3"]=="true").",r_edit_grouprights=".(int)($_GET["r4"]=="true").",r_rename_group=".(int)($_GET["r5"]=="true")
			.",r_add_group=".(int)($_GET["r6"]=="true").",r_remove_group=".(int)($_GET["r7"]=="true").",r_view_clients=".(int)($_GET["r8"]=="true")
			.",r_msg_client=".(int)($_GET["r9"]=="true").",r_kick_client=".(int)($_GET["r10"]=="true").",r_ban_client=".(int)($_GET["r11"]=="true")
			.",r_change_servergroup=".(int)($_GET["r12"]=="true").",r_change_channelgroup=".(int)($_GET["r13"]=="true").",r_view_clientdetails=".(int)($_GET["r14"]=="true")
			.",r_edit_clientdetails=".(int)($_GET["r15"]=="true").",r_view_bans=".(int)($_GET["r16"]=="true").",r_remove_bans=".(int)($_GET["r17"]=="true")
			.",r_view_complaints=".(int)($_GET["r18"]=="true").",r_remove_complaints=".(int)($_GET["r19"]=="true").",r_view_log=".(int)($_GET["r20"]=="true")
			.",r_view=".(int)($_GET["r21"]=="true").",r_edit_channel=".(int)($_GET["r22"]=="true").",r_remove_channel=".(int)($_GET["r23"]=="true")
			.",r_add_channel=".(int)($_GET["r24"]=="true").",r_move_client=".(int)($_GET["r25"]=="true");
			
			$db->update(MYSQL_TABLE_PREFIX."ts3admin_vserver_rights",$fields,"`serverid`=".$sid." AND `vserverid`=".$vsid." AND `type`=".$typei." AND `uid`=".$uid);
		}
		
		$action="refresh";
	}
	
	$smarty->assign('action', $action);
	$smarty->assign('type', $type);
	$smarty->assign('sid', $sid);
	$smarty->assign('vsid', $vsid);
	$smarty->assign('uid', $uid);
	$smarty->display('../mod/default/ts3admin/RightOption.tpl');
?>