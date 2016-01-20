<?php
	//init
	require_once("libraries/ts3init.php");
	
	$servers = $db->selectList($tbl . "_servers", "ID,name,address,usr,pw,query","true","ID");
	
	$boxservers = array();
	$vserverindex=-1;
	
	foreach($servers as $server_data) {
		try {
			$ts3_ServerInstance = TeamSpeak3::factory("serverquery://".$server_data["usr"].":".$server_data["pw"]."@".$server_data["address"].":".$server_data["query"]."/?use_offline_as_virtual=1");
			$servers_settings = $db->selectList($tbl . "_server_box", "`vserverid`,`join`","`serverid`=".$server_data["ID"]." and `show`=1","`vserverid`");
			
			foreach($ts3_ServerInstance as $ts3_VirtualServer) {
				try {
					$try=$ts3_VirtualServer["virtualserver_name"];
					foreach($servers_settings as $settings) {
						if($ts3_VirtualServer["virtualserver_id"]==$settings["vserverid"]) {
							$vserverindex+=1;
							$boxservers[$vserverindex]=array();
							$boxservers[$vserverindex]["name"]=$ts3_VirtualServer["virtualserver_name"];
							$boxservers[$vserverindex]["maxusers"]=$ts3_VirtualServer["virtualserver_maxclients"];
							$boxservers[$vserverindex]["usersonline"]=$ts3_VirtualServer["virtualserver_clientsonline"];
							$boxservers[$vserverindex]["join"]=$settings["join"];
							$boxservers[$vserverindex]["joinlink"]="ts3server://".$server_data["address"].":".$ts3_VirtualServer["virtualserver_port"];
						}
					}
				}catch(Exception $e) {}
			}
			
		}catch(Exception $e) {}
	}
	
	$smarty->assign('boxservers',$boxservers);
	
	$smarty->display('../mod/default/ts3admin/serverboxinfo.tpl');
?>
