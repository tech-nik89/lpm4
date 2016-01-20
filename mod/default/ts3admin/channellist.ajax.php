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
	$groupc=array();
	foreach($cgroups as $gc) {
		$groupc[$gc["cgid"]]=array();
		$groupc[$gc["cgid"]]["cgid"] = $gc["cgid"];
		$groupc[$gc["cgid"]]["name"] = $gc["name"];
		$groupc[$gc["cgid"]]["iconid"] = $gc["iconid"];
	}	
	$smarty->assign('cgroups', $groupc);
	
	$smarty->assign('virtualserver_name', $ts3server["virtualserver_name"]);
	
	$channels = $ts3server->channelList();
	
	foreach ($channels as $chn) {
		$channels[$chn["cid"]]=array();
		$channels[$chn["cid"]]["cid"] = $chn["cid"];
		$channels[$chn["cid"]]["pid"] = $chn["pid"];
		$channels[$chn["cid"]]["channel_name"] = $chn["channel_name"];
		$channels[$chn["cid"]]["channel_flag_default"] = $chn["channel_flag_default"];
		$channels[$chn["cid"]]["channel_order"] = $chn["channel_order"];
		$channels[$chn["cid"]]["channel_haspw"] = $chn["channel_flag_password"];
		$channels[$chn["cid"]]["channel_codec"] = $chn["channel_codec"];
		$channels[$chn["cid"]]["channel_icon"] = $chn["channel_icon_id"];
		$channels[$chn["cid"]]["clients"] = array();
		foreach ($chn->clientList() as $client) {
			if ($client["client_type"]=="0") {				
				$channels[$chn["cid"]]["clients"][$client.client_unique_identifier]["cid"] = $client["client_unique_identifier"];
				$channels[$chn["cid"]]["clients"][$client.client_unique_identifier]["dbid"] = $client["client_database_id"];
				$channels[$chn["cid"]]["clients"][$client.client_unique_identifier]["nick"] = $client["client_nickname"];
				$channels[$chn["cid"]]["clients"][$client.client_unique_identifier]["inmute"] = $client["client_input_muted"];
				$channels[$chn["cid"]]["clients"][$client.client_unique_identifier]["outmute"] = $client["client_output_muted"];
				$channels[$chn["cid"]]["clients"][$client.client_unique_identifier]["servergroups"] = explode(",",$client["client_servergroups"]);
				$channels[$chn["cid"]]["clients"][$client.client_unique_identifier]["channelgroup"] = $client["client_channel_group_id"];
				$channels[$chn["cid"]]["clients"][$client.client_unique_identifier]["priospeaker"] = $client["client_is_priority_speaker"];
				$channels[$chn["cid"]]["clients"][$client.client_unique_identifier]["channelcommander"] = $client["client_is_channel_commander"];
			}
		}
	}
	
	$smarty->assign('channels',$channels);
	$smarty->display('../mod/default/ts3admin/channellist.tpl');
?>