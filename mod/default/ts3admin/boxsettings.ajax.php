<?php
	//init
	require_once("libraries/ts3init.php");
	
	$manage_box_allowed = $rights->isallowed("ts3admin", 'manage_box');
	if(!$manage_box_allowed) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	if(isset($_GET["action"]))
		$action = $_GET["action"];
	else
		$action="";
	if($action=="save") {
		$servers = $db->selectList($tbl . "_server_box", "serverid,vserverid","true");
		foreach($servers as $server_data) {
			$db->update($tbl . "_server_box", "`join`=".$_GET[$server_data["serverid"]."_".$server_data["vserverid"]."_join"].
											  ",`show`=".$_GET[$server_data["serverid"]."_".$server_data["vserverid"]."_show"],
												"`serverid`=".$server_data["serverid"]." and `vserverid`=".$server_data["vserverid"]);
		}
		$smarty->assign('ajaxcallback',true);
	} else {
		$servers = $db->selectList($tbl . "_servers", "ID,name,address,usr,pw,query","true","ID");
	
		$server_settings = array();
		$serverindex=0;
		
		foreach($servers as $server_data) {
			try {
				$ts3_ServerInstance = TeamSpeak3::factory("serverquery://".$server_data["usr"].":".$server_data["pw"]."@".$server_data["address"].":".$server_data["query"]."/?use_offline_as_virtual=1");
				$server_settings[$serverindex] = array();
				$server_settings[$serverindex]["ID"] = $server_data["ID"];
				$server_settings[$serverindex]["name"] = $server_data["name"];
				$server_settings[$serverindex]["address"] = $server_data["address"];
				$server_settings[$serverindex]["vservers"] = array();
				$vserverindex=0;
				foreach($ts3_ServerInstance as $ts3_VirtualServer) {
					try {
						$try=$ts3_VirtualServer["virtualserver_name"];
						$server_settings[$serverindex]["vservers"][$vserverindex] = array( "virtualserver_name" => $ts3_VirtualServer["virtualserver_name"], "id" => $ts3_VirtualServer["virtualserver_id"] );
						$settings = $db->selectOneRow($tbl."_server_box","`show`,`join`,`vserverid`,`serverid`","`serverid`=".$server_data["ID"]." and `vserverid`=".$ts3_VirtualServer["virtualserver_id"]);
						if($settings!=NULL) {
							$server_settings[$serverindex]["vservers"][$vserverindex]["settings"] = $settings;
						} else {
							$server_settings[$serverindex]["vservers"][$vserverindex]["settings"] = array("show"=>0,"join"=>0,"vserverid"=>$ts3_VirtualServer["virtualserver_id"],"serverid"=>$server_data["ID"]);
							$db->insert($tbl."_server_box", array("show","join","vserverid","serverid"),array(0,0,$ts3_VirtualServer["virtualserver_id"],$server_data["ID"]));
						}
						$vserverindex++;
					}catch(Exception $e) {}
				}
				$serverindex++;
			}catch(Exception $e) {}
		}
		
		$smarty->assign('servers',$servers);
		$smarty->assign('server_settings',$server_settings);
	}
	$smarty->display('../mod/default/ts3admin/boxsettings.tpl');
?>