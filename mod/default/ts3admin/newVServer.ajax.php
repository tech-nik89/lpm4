<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	if($ts3server_rights["r_add_vservers"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	$smarty->assign('ajaxcallback', false);
	
	if(isset($_GET["save"])) {
		try {
			$ret = $ts3_ServerInstance->serverCreate(array("virtualserver_name"=> $_GET["name"],"virtualserver_maxclients"=> $_GET["maxclients"],"virtualserver_password"=> $_GET["passw"],"virtualserver_autostart"=> $_GET["autostart"]));
			$smarty->assign('newvserver', $ret);	
			$smarty->assign('ajaxcallback', true);	
		}catch(Exception $e) {
			$notify->raiseError("",$e);	
		}
	}
	
	$smarty->display('../mod/default/ts3admin/newVServer.tpl');
?>