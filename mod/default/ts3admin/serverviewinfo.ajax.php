<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	if($ts3server_rights["r_view_server"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	$smarty->assign('virtualserver_name', $ts3server["virtualserver_name"]);
	$smarty->assign('virtualserver_maxclients', $ts3server["virtualserver_maxclients"]);
	$smarty->assign('virtualserver_clientsonline', $ts3server["virtualserver_clientsonline"]);
	$smarty->assign('virtualserver_channelsonline', $ts3server["virtualserver_channelsonline"]);
	$smarty->assign('virtualserver_queryclientsonline', $ts3server["virtualserver_queryclientsonline"]);
	$smarty->assign('virtualserver_platform', $ts3server["virtualserver_platform"]);
	$smarty->assign('virtualserver_version', $ts3server["virtualserver_version"]);
	$smarty->assign('virtualserver_port', $ts3server["virtualserver_port"]);
	$smarty->assign('virtualserver_hostbanner_url', $ts3server["virtualserver_hostbanner_url"]);
	$smarty->assign('virtualserver_hostbanner_gfx_url', $ts3server["virtualserver_hostbanner_gfx_url"]);
	$smarty->assign('virtualserver_hostbanner_gfx_interval', $ts3server["virtualserver_hostbanner_gfx_interval"]);
	$smarty->assign('virtualserver_uptime', SecToStr($ts3server["virtualserver_uptime"]));
	
	
	$smarty->assign('virtualserver_address', $server_data["address"]);
	
	
	$smarty->display('../mod/default/ts3admin/serverviewinfo.tpl');
?>