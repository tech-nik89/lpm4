<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if($ts3vserver_rights["r_view"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	$client = $ts3server->clientGetByUid($_GET["cid"]);
	
	$sgroups = $ts3server->serverGroupList(array());
	$groups=array();
	foreach($sgroups as $g) {
		$groups[$g["sgid"]]=array();
		$groups[$g["sgid"]]["sgid"] = $g["sgid"];
		$groups[$g["sgid"]]["name"] = $g["name"];
		$groups[$g["sgid"]]["iconid"] = $g["iconid"];
	}
	$smarty->assign('sgroups', $groups);
	
	$cgroups = $ts3server->channelGroupList();
	$group=array();
	foreach($cgroups as $gc) {
		if($client["client_channel_group_id"]==$gc["cgid"]) {
			$group["name"] = $gc["name"];
			$group["iconid"] = $gc["iconid"];
		}
	}	
	$smarty->assign('channelgroup', $group);
	
	
	$smarty->assign('virtualserver_hostbanner_url', $ts3server["virtualserver_hostbanner_url"]);
	$smarty->assign('virtualserver_hostbanner_gfx_url', $ts3server["virtualserver_hostbanner_gfx_url"]);
	$smarty->assign('virtualserver_hostbanner_gfx_interval', $ts3server["virtualserver_hostbanner_gfx_interval"]);
	
	$smarty->assign('cid', $client["cid"]);
	$smarty->assign('client_nickname', $client["client_nickname"]);
	$smarty->assign('client_unique_identifier', $client["client_unique_identifier"]);
	$smarty->assign('client_database_id', $client["client_database_id"]);
	$smarty->assign('client_description', $client["client_description"]);
	$smarty->assign('client_version', $client["client_version"]);
	$smarty->assign('client_platform', $client["client_platform"]);
	$smarty->assign('client_totalconnections', $client["client_totalconnections"]);
	$smarty->assign('client_lastconnected', $client["client_lastconnected"]);
	$smarty->assign('client_created', $client["client_created"]);
	$smarty->assign('client_servergroups', explode(",",$client["client_servergroups"]));
	$smarty->assign('client_input_muted', $client["client_input_muted"]);
	$smarty->assign('client_output_muted', $client["client_output_muted"]);
	
	
	$smarty->display('../mod/default/ts3admin/clientviewinfo.tpl');
?>