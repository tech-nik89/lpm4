<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if($ts3vserver_rights["r_view_clients"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	$sgroups = $ts3server->serverGroupList(array());
	$groups=array();
	foreach($sgroups as $g) {
		$groups[$g["sgid"]]=array();
		$groups[$g["sgid"]]["sgid"] = $g["sgid"];
		$groups[$g["sgid"]]["name"] = $g["name"];
		$groups[$g["sgid"]]["type"] = $g["type"];
	}
	$smarty->assign('groups', $groups);
	$smarty->assign('grouptypes', array($lang->get("type0_template"),$lang->get("type1_clients"),$lang->get("type2_query")));
	
	$smarty->assign('clients', $ts3server->clientList());
	
	$smarty->display('../mod/default/ts3admin/userTokens.tpl');
?>