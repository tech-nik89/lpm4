<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if($ts3vserver_rights["r_view_bans"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	if(isset($_GET["clearbanlist"]) || isset($_GET["clearbanlistack"]) || isset($_GET["deleteban"]) || isset($_GET["deletebanack"])) {
		if($ts3vserver_rights["r_remove_bans"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
	}
	
	if(isset($_GET["clearbanlist"])) {
		$smarty->assign('clearbanlist', true);
	} else if(isset($_GET["clearbanlistack"])) {
		$ts3server->banListClear();
		$smarty->assign('clearedbanlist', true);
	} else if(isset($_GET["deleteban"])) {
		$smarty->assign('bandel', true);
		$smarty->assign('bid', $_GET["bid"]);
	} else if(isset($_GET["deletebanack"])) {
		$ts3server->banDelete($_GET["bid"]);
		$ts3server->banDelete(((int)$_GET["bid"])+1);
		$smarty->assign('bid', $_GET["bid"]);
		$smarty->assign('bandeleted', true);
	} else {
		try {
			$bans = $ts3server->banList();
		}catch(Exception $e) {}
		
		if(!isset($bans) or count($bans)==0) {
				$smarty->assign('nobans', true);
				$notify->add($lang->get('bans'), $lang->get('nobansfound'));
		} else {
			$banlist = array();
			$index = 0;
			$lastban = NULL;
			foreach($bans as $curban) {
				if($index++%2==0) {
					$lastban = $curban;	
					continue;
				}
				$lastban["ip"] = $curban["ip"];
				$cid = $ts3server->clientFindDb($lastban["uid"],"-uid");
				$info = $ts3server->clientInfoDb($cid[0]);
				$lastban["name"] = $info["client_nickname"];
				if($lastban["duration"]>0) {
					$lastban["remaining"] = SecToStr((int)$lastban["duration"] - (time() - (int)$lastban["created"]));
					$lastban["duration"] = SecToStr($lastban["duration"]);
				}else{
					$lastban["duration"] = $lang->get("infinite");
					$lastban["remaining"] = "---";
				}
				$banlist[count($banlist)] = $lastban;	
			}
			$smarty->assign('banlist', $banlist);
		}
	}
	$smarty->assign('notify', $notify->getAll());
	$smarty->display('../mod/default/ts3admin/banlist.tpl');
?>