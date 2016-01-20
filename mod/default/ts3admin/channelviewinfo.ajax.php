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
	
	$smarty->assign('virtualserver_hostbanner_url', $ts3server["virtualserver_hostbanner_url"]);
	$smarty->assign('virtualserver_hostbanner_gfx_url', $ts3server["virtualserver_hostbanner_gfx_url"]);
	$smarty->assign('virtualserver_hostbanner_gfx_interval', $ts3server["virtualserver_hostbanner_gfx_interval"]);
	
	$channels = $ts3server->channelList();
	
	foreach ($channels as $chn) {
		if ($chn["cid"]==$_GET["cid"]) {
			
			$smarty->assign('cid', $chn["cid"]);
			$smarty->assign('channel_name', $chn["channel_name"]);
			$smarty->assign('channel_topic', $chn["channel_topic"]);
			$smarty->assign('channel_codec', $chn["channel_codec"]);
			$smarty->assign('channel_codec_quality', $chn["channel_codec_quality"]);
			if($chn["channel_flag_permanent"]==1)
				$smarty->assign('channel_type', "Perm");
			else if($chn["channel_flag_semi_permanent"]==1)
				$smarty->assign('channel_type', "SemiPerm");
			else if($chn["channel_flag_default"]==1) 
				$smarty->assign('channel_type', "Perm, Def");
			else
				$smarty->assign('channel_type', "Temp");
			$smarty->assign('channel_maxclients', $chn["channel_maxclients"]);
			$smarty->assign('channel_flag_maxclients_unlimited', $chn["channel_flag_maxclients_unlimited"]);
			
			$smarty->assign('channel_flag_maxfamilyclients_unlimited', $chn["channel_flag_maxfamilyclients_unlimited"]);
			$smarty->assign('channel_flag_maxfamilyclients_inherited', $chn["channel_flag_maxfamilyclients_inherited"]);
			
			$clientCount = 0;
			foreach ($chn->clientList() as $client) {
				if ($client["client_type"]=="0") {
					$clientCount++;
				}
			}
			$smarty->assign('channel_clients', $clientCount);
			$smarty->assign('channel_codec_is_unencrypted', $chn["channel_codec_is_unencrypted"]);
			$smarty->assign('channel_description', $chn["channel_description"]);
		}
	}
	
	
	$smarty->display('../mod/default/ts3admin/channelviewinfo.tpl');
?>