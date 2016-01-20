<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if($ts3vserver_rights["r_remove_group"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	$smarty->assign('ajaxcallback', false);
	
	if(isset($_GET["delete"])) {
		try {
			$ts3server->serverGroupDelete($_GET["sgid"]);
			$smarty->assign('ajaxcallback', true);	
		}catch(Exception $e) {
			$notify->raiseError("",$e);	
		}
	}
	
	$smarty->display('../mod/default/ts3admin/delGroup.tpl');
?>