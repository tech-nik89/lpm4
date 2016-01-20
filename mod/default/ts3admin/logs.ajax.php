<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if($ts3vserver_rights["r_view_log"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	$limit = 500;
	if(isset($_GET["limit"]) && ((int)$_GET["limit"])>0 && ((int)$_GET["limit"])<=500){
		$limit = (int)$_GET["limit"];
	}
	
	try {
		$logs = $ts3server->logView($limit);
	}catch(Exception $e) {}
	
	if(!isset($logs) or count($logs)==0) {
			$smarty->assign('nologs', true);
			$notify->add($lang->get('logs'), $lang->get('nologsfound'));
	} else {
		$smarty->assign('limit', $limit);
		$smarty->assign('logs', $logs);
	}
	
	$smarty->display('../mod/default/ts3admin/logs.tpl');
?>