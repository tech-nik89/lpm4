<?php
	//init
	require_once("libraries/ts3init.php");
	
	$sid = $_GET["sid"];
	$vsid = $_GET["vsid"];
	
	checkStdRightIssues();
	
	// Do nothing here... just the menu
	
	$smarty->assign('sid', $sid);
	$smarty->assign('vsid', $vsid);
	
	$smarty->display('../mod/default/ts3admin/manageVServer.tpl');
?>