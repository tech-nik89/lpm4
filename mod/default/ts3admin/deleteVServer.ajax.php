<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if($ts3server_rights["r_remove_vservers"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	$smarty->assign('ajaxcallback', false);
	if(isset($_GET["delete"])) {
		try {
			$ts3server->stop();
			sleep(2);
			$ts3server = GetVServer($ts3_ServerInstance, $vsid);
			$ts3server->delete();
			
			$vsid=1;
			foreach($ts3_ServerInstance as $ts3_VirtualServer)
			{
				try {
					$try=$ts3_VirtualServer["virtualserver_name"];
					$vsid=$ts3_VirtualServer["id"];
					break;
				}catch(Exception $e) {}
			}
			
			$smarty->assign('vsid', $vsid);	
			$smarty->assign('ajaxcallback', true);	
		}catch(TeamSpeak3_Adapter_ServerQuery_Exception $e) {
			$notify->raiseError("",$e);	
		}
	}
	
	$smarty->display('../mod/default/ts3admin/deleteVServer.tpl');
?>