<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	// Get client object
	$cuid = $_GET["cid"];
	$clients = $ts3server->clientList();
	$client = NULL;
	foreach($clients as $clt) {
		if($clt["client_unique_identifier"]==$cuid) {
			$client=$clt;
			break;	
		}
	}
	
	if($client==NULL) {
		$notify->raiseError("",$lang->get("cantfindclient"));	
		$smarty->display('../mod/default/ts3admin/chooseVServer.tpl');
		die();	
	}
	
	
	// add to group
	$smarty->assign('ajaxcallback', false);
	if(isset($_GET["add"])){
		$client->addServerGroup($_GET["group"]);
		$smarty->assign('groupid',$_GET["group"]);
		$smarty->assign('ajaxcallback', true);
		$smarty->assign('groupaction', "add");
	}
	// rem group
	if(isset($_GET["rem"])){
		$client->remServerGroup($_GET["group"]);
		$smarty->assign('groupid',$_GET["group"]);
		$smarty->assign('ajaxcallback', true);
		$smarty->assign('groupaction', "rem");
	}
	
	//Ask to remove
	if(isset($_GET["remove"])){
		$smarty->assign('removegroup', true);
		$smarty->assign('groupid',$_GET["group"]);
	}
	
	
	//show template
	$smarty->assign('cuid',$cuid);
	$smarty->assign('client',$client);
	$smarty->display('../mod/default/ts3admin/ClientGroup.tpl');
?>