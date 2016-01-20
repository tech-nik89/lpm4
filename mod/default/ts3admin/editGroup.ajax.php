<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	getVSID();
	
	checkStdRightIssues();
	
	if($ts3vserver_rights["r_rename_group"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	$smarty->assign('ajaxcallback', false);
	
	if(isset($_GET["save"])) {
		try {
			$type = TeamSpeak3::GROUP_DBTYPE_TEMPLATE;
			switch($_GET["type"]){
				case "clients":
					$type = TeamSpeak3::GROUP_DBTYPE_REGULAR;
					break;
				case "query":
					$type = TeamSpeak3::GROUP_DBTYPE_SERVERQUERY;
					break;
				default:
					$type = TeamSpeak3::GROUP_DBTYPE_TEMPLATE;
			}
			
			$ts3server->serverGroupRename($_GET["sgid"], $_GET["name"]);
			$smarty->assign('ajaxcallback', true);	
		}catch(Exception $e) {
			$notify->raiseError("",$e);	
		}
	}
	
	$sgroups = $ts3server->serverGroupList();
	
	foreach($sgroups as $g) {
		if($_GET["sgid"]==$g["sgid"]) {
			$smarty->assign('sgid', $g["sgid"]);
			$smarty->assign('groupname', $g["name"]);
			$smarty->assign('grouptype', $g["type"]);
		}
	}
	$smarty->display('../mod/default/ts3admin/editGroup.tpl');
?>