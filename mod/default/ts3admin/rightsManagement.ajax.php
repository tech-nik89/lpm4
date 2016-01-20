<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if($ts3vserver_rights["r_view_grouprights"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	$permlist = $ts3_ServerInstance->permissionList();
	$curperm = NULL;
	if(isset($_GET["permid"])) {
		foreach($permlist as $perm){
			if ($perm["permid"]==$_GET["permid"]) {
				$curperm = $perm;
				break;
			}
		}
	}
	
	$smarty->assign('ajaxcallback', false);
	$smarty->assign('ajaxask', false);
	if(isset($_GET["askdelete"]) || isset($_GET["askadd"])) {
		$smarty->assign('ajaxask', true);
		$smarty->assign('sgid', $_GET["sgid"]);
		$smarty->assign('permid', $_GET["permid"]);
		
		if(isset($_GET["askdelete"])) {
			if($ts3vserver_rights["r_remove_group"]!=1) {
				$smarty->display('../mod/default/ts3admin/notallowed.tpl');
				die();
			}
			$smarty->assign('ask_msg', $lang->get("askdelright"));
			$smarty->assign('rightaction', 'delete');
		}else{
			if($ts3vserver_rights["r_add_group"]!=1) {
				$smarty->display('../mod/default/ts3admin/notallowed.tpl');
				die();
			}
			if(strpos($curperm["permname"],"i_")===0) {
				$smarty->assign('inputint', true);	
			}else{
				$smarty->assign('inputint', false);		
			}
			$smarty->assign('ask_msg', $lang->get("askaddright"));
			$smarty->assign('rightaction', 'add');
		}
	}else if(isset($_GET["delete"])) {
		if($ts3vserver_rights["r_remove_group"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
		$smarty->assign('sgid', $_GET["sgid"]);
		$smarty->assign('permid', $_GET["permid"]);
		$smarty->assign('isint', strpos($curperm["permname"],"i_")===0);
		$smarty->assign('val', 'undefined');
		$ts3server->serverGroupGetById($_GET["sgid"])->permRemove($_GET["permid"]);
		$smarty->assign('action', 'del');
		$smarty->assign('rmanage_msg', $lang->get("permremoved"));
		$smarty->assign('ajaxcallback', true);
	}else if(isset($_GET["add"])) {
		if($ts3vserver_rights["r_add_group"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
		$smarty->assign('sgid', $_GET["sgid"]);
		$smarty->assign('permid', $_GET["permid"]);
		$group = $ts3server->serverGroupGetById($_GET["sgid"]);
		$smarty->assign('isint', strpos($curperm["permname"],"i_")===0);
		$smarty->assign('val', $_GET["val"]);
		if(strpos($curperm["permname"],"i_")===0) {
			$group->permAssign($_GET["permid"],$_GET["val"]);
		}else{
			$group->permAssign($_GET["permid"],TRUE);
		}
		$smarty->assign('action', 'add');
		$smarty->assign('rmanage_msg', $lang->get("permadded"));
		$smarty->assign('ajaxcallback', true);
	} else {
	
		//Default
	
		$sgroups = $ts3server->serverGroupList(array());
		$groups=array();
		foreach($sgroups as $g) {
			$groups[$g["sgid"]]=array();
			$groups[$g["sgid"]]["sgid"] = $g["sgid"];
			$groups[$g["sgid"]]["name"] = $g["name"];
			$groups[$g["sgid"]]["type"] = $g["type"];
			$groups[$g["sgid"]]["iconid"] = $g["iconid"];
			$groups[$g["sgid"]]["savedb"] = $g["savedb"];
			$groups[$g["sgid"]]["sortid"] = $g["sortid"];
			$groups[$g["sgid"]]["namemode"] = $g["namemode"];
			$groups[$g["sgid"]]["perms"] = $g->permList();
		}
		
		$smarty->assign('groups', $groups);
		$smarty->assign('grouptypes', array($lang->get("type0_template"),$lang->get("type1_clients"),$lang->get("type2_query")));
		
		$smarty->assign('permlist', $permlist);
	}
	
	$smarty->display('../mod/default/ts3admin/rightsManagement.tpl');
?>