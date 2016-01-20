<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if($ts3vserver_rights["r_view_complaints"]!=1) {
		$smarty->display('../mod/default/ts3admin/notallowed.tpl');
		die();
	}
	
	if(isset($_GET["clearcomplist"]) || isset($_GET["clearcomplistack"]) || isset($_GET["deletecomp"]) || isset($_GET["deletecompack"])) {
		if($ts3vserver_rights["r_remove_complaints"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}	
	}
	
	if(isset($_GET["clearcomplist"])) {
		$smarty->assign('clearcomplist', true);
	} else if(isset($_GET["clearcomplistack"])) {
		foreach($ts3server->complaintList() as $complain) {
			$ts3server->complaintDelete($complain["tcldbid"],$complain["fcldbid"]);
		}
		$smarty->assign('clearedcomplist', true);
	} else if(isset($_GET["deletecomp"])) {
		$smarty->assign('deletecomp', true);
		$smarty->assign('tcldbid', $_GET["tcldbid"]);
		$smarty->assign('fcldbid', $_GET["fcldbid"]);
	} else if(isset($_GET["deletecompack"])) {
		$ts3server->complaintDelete($_GET["tcldbid"],$_GET["fcldbid"]);
		$smarty->assign('tcldbid', $_GET["tcldbid"]);
		$smarty->assign('fcldbid', $_GET["fcldbid"]);
		$smarty->assign('deletedcomp', true);
	} else {
		try {
			$complains = $ts3server->complaintList();
		}catch(Exception $e) {}
		
		if(!isset($complains) or count($complains)==0) {
				$smarty->assign('nocomplains', true);
				$notify->add($lang->get('complains'), $lang->get('nocomplainsfound'));
		} else {
			$smarty->assign('complains', $complains);
		}
	}
	$smarty->assign('notify', $notify->getAll());
	$smarty->display('../mod/default/ts3admin/complains.tpl');
?>