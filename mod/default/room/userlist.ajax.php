<?php
		
	$tbl_users = MYSQL_TABLE_PREFIX . 'users';
	$tbl_register = MYSQL_TABLE_PREFIX . 'register';
	
	$event = $db->selectOneRow($tbl_register, "*", "eventid=".(int)$_GET['eventid']);	
		
	$result = $db->query("SELECT * FROM `" . $tbl_users . "` AS U 
					WHERE 
					(INSTR(U.`nickname`, '" . secureMySQL($_GET['search_string']) . "') > 0
					OR INSTR(U.`prename`, '" . secureMySQL($_GET['search_string']) . "') > 0
					OR INSTR(U.`lastname`, '" . secureMySQL($_GET['search_string']) . "') > 0)
					LIMIT 5;");
	
	while ($row = mysql_fetch_assoc($result)){
		$dummy = $row;
		$registered = $db->selectOneRow($tbl_register, '*', "userid=".$dummy['userid']." AND eventid=".(int)$_GET['eventid']);
		
		$dummy['reserve']=(@$event['free']==1 OR $registered['payed']>0);
		$dummy['sitdown']=$registered['appeared']!=0;
		
		$l[]=$dummy;
	}
	$lang->addModSpecificLocalization('room');
	
	$smarty->assign('seatable', $lang->get('seatable')); 
	
	$smarty->assign('search_string', $_GET['search_string']);
	$smarty->assign('list', @$l);
	$smarty->display('../mod/default/room/userlist.tpl');
	
?>