<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	if($ts3server_rights["r_view_server"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	$servers = array();
	$serverindex = 0;
	
	foreach($ts3_ServerInstance as $ts3_VirtualServer)
    {
		try {
			$try=$ts3_VirtualServer["virtualserver_name"];
			$servers[$serverindex++] = array( "virtualserver_name" => $ts3_VirtualServer["virtualserver_name"], "id" => $ts3_VirtualServer["virtualserver_id"] );
		}catch(Exception $e) {}
    }
	
	$smarty->assign('sid', $sid);
	
	$serv = array();
	foreach($servers as $s) {
		$r=getTS3Rights($sid,$s["id"]);
		if($r["r_view_vserver"]==1)
			$serv[]=$s;
	}
	
	$smarty->assign('vservers', $serv);
	
	$smarty->display('../mod/default/ts3admin/chooseVServer.tpl');
?>