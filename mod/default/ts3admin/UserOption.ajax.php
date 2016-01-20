<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
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
	
	
	//check rights
	if(isset($_GET["asksendmsg"]) || isset($_GET["askpoke"]) || isset($_GET["sendmsg"]) || isset($_GET["sendmsg"])  ) {
		if($ts3vserver_rights["r_msg_client"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
	}else if(isset($_GET["askkickfromchannel"]) || isset($_GET["askkickfromserver"]) || isset($_GET["kickfromchannel"]) || isset($_GET["kickfromserver"])   ) {
		if($ts3vserver_rights["r_kick_client"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}	
	}else if(isset($_GET["askban"]) || isset($_GET["ban"])   ) {
		if($ts3vserver_rights["r_ban_client"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}	
	}else if(isset($_GET["askeditdescription"]) || isset($_GET["editdescription"])   ) {
		if($ts3vserver_rights["r_edit_clientdetails"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}	
	}else if(isset($_GET["asksetservergroups"]) || isset($_GET["setservergroups"])   ) {
		if($ts3vserver_rights["r_change_servergroup"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}	
	}else if(isset($_GET["asksetchannelgroup"]) || isset($_GET["setchannelgroup"])   ) {
		if($ts3vserver_rights["r_change_channelgroup"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}	
	}else if(isset($_GET["askclientmove"]) || isset($_GET["clientmove"])   ) {
		if($ts3vserver_rights["r_move_client"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}	
	}
	
	
	if(isset($_GET["asksendmsg"])) {
		$smarty->assign("asksendmsg",true);
	}else if(isset($_GET["askpoke"])) {
		$smarty->assign("askpoke",true);
	}else if(isset($_GET["askcomplaint"])) {
		$smarty->assign("askcomplaint",true);
	}else if(isset($_GET["askkickfromchannel"])) {
		$smarty->assign("askkickfromchannel",true);
	}else if(isset($_GET["askkickfromserver"])) {
		$smarty->assign("askkickfromserver",true);
	}else if(isset($_GET["askban"])) {
		$smarty->assign("askban",true);
	}else if(isset($_GET["askeditdescription"])) {
		$smarty->assign("askeditdescription",true);	
		$smarty->assign("description",$client["client_description"]);	
	}else if(isset($_GET["asksetservergroups"])) {
		$smarty->assign("asksetservergroups",true);
		$sgroups = $ts3server->serverGroupList(array());
		$groups=array();
		foreach($sgroups as $g) {
			if($g["type"]==1) {
				$groups[$g["sgid"]]=array();
				$groups[$g["sgid"]]["sgid"] = $g["sgid"];
				$groups[$g["sgid"]]["name"] = $g["name"];
				$groups[$g["sgid"]]["iconid"] = $g["iconid"];
			}
		}
		$smarty->assign('sgroups', $groups);
		$smarty->assign('servergroups', explode(",",$client["client_servergroups"]));
	}else if(isset($_GET["asksetchannelgroup"])) {
		$smarty->assign("asksetchannelgroup",true);
		$cgroups = $ts3server->channelGroupList();
		$groupc=array();
		foreach($cgroups as $gc) {
			if($gc["type"]==1) {
				$groupc[$gc["cgid"]]=array();
				$groupc[$gc["cgid"]]["cgid"] = $gc["cgid"];
				$groupc[$gc["cgid"]]["name"] = $gc["name"];
				$groupc[$gc["cgid"]]["iconid"] = $gc["iconid"];
			}
		}	
		$smarty->assign('cgroups', $groupc);
		$smarty->assign('channelgroup', $client["client_channel_group_id"]);	
		
		foreach($ts3server->channelList() as $c) {
			try {
				$clients = $c->clientList();
				foreach($clients as $clt) {
					if($clt["client_database_id"]==$client["client_database_id"])
						$smarty->assign('channelid', $c["cid"]);	
				}
			}catch(Exception $e){}	
		}
	}else if(isset($_GET["askclientmove"])) {
		$smarty->assign("askclientmove",true);
		$channels = $ts3server->channelList();
		foreach ($channels as $chn) {
			$channels[$chn["cid"]]=array();
			$channels[$chn["cid"]]["cid"] = $chn["cid"];
			$channels[$chn["cid"]]["pid"] = $chn["pid"];
			$channels[$chn["cid"]]["channel_name"] = $chn["channel_name"];
		}
		$smarty->assign('channels',$channels);
		$smarty->assign('channelcount',count($channels));
		foreach($ts3server->channelList() as $c) {
			try {
				$clients = $c->clientList();
				foreach($clients as $clt) {
					if($clt["client_database_id"]==$client["client_database_id"])
						$smarty->assign('channelid', $c["cid"]);	
				}
			}catch(Exception $e){}	
		}
		
		
		
	}else if(isset($_GET["sendmsg"])) {
		$smarty->assign("sendmsg",true);
		$client->message($_GET["msg"]);
	}else if(isset($_GET["poke"])) {
		$smarty->assign("poke",true);
		$client->poke($_GET["msg"]);
	}else if(isset($_GET["complaint"])) {
		$smarty->assign("complaint",true);
		$ts3server->complaintCreate($client["client_database_id"],$_GET["msg"]);
	}else if(isset($_GET["kickfromchannel"])) {
		$smarty->assign("kickfromchannel",true);
		$client->kick(TeamSpeak3::KICK_CHANNEL,$_GET["msg"]);
	}else if(isset($_GET["kickfromserver"])) {
		$smarty->assign("kickfromserver",true);
		$client->kick(TeamSpeak3::KICK_SERVER,$_GET["msg"]);
	}else if(isset($_GET["ban"])) {
		$smarty->assign("ban",true);
		$duration = (int)$_GET["duration"];
		if ($duration==0) $duration=NULL;
		$msg = $_GET["msg"];
		if (strlen($msg)==0) $msg=NULL;
		$client->ban($duration,$msg);
	}else if(isset($_GET["editdescription"])) {
		$smarty->assign("editeddescription",true);
		$client->modify(array("client_description"=>$_GET["text"]));
	}else if(isset($_GET["setservergroups"])) {
		$smarty->assign("setservergroups",true);
		$sgroups = $ts3server->serverGroupList(array());
		$groups=explode(",",$client["client_servergroups"]);
		foreach($sgroups as $g) {
			if($g["type"]==1) {
				if($_GET["sg".$g["sgid"]]=="true") {
					if(!in_array($g["sgid"],$groups)) {
						$client->addServerGroup($g["sgid"]);
					}
				}else{
					if(in_array($g["sgid"],$groups)) {
						$client->remServerGroup($g["sgid"]);
					}
				}
			}
		}
	}else if(isset($_GET["setchannelgroup"])) {
		$smarty->assign("setchannelgroup",true);
		$client->setChannelGroup ($_GET["channelid"], $_GET["cg"]);
	}else if(isset($_GET["clientmove"])) {
		$smarty->assign("clientmove",true);
		$client->move($_GET["chn"]);
	}
	
	$smarty->assign('cuid',$cuid);
	$smarty->display('../mod/default/ts3admin/UserOption.tpl');
?>