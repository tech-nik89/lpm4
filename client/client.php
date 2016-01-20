<?php
	
	$tbl_b = MYSQL_TABLE_PREFIX.'buddylist';
	$tbl_u = MYSQL_TABLE_PREFIX.'users';
	
	require_once('../core/common.core.php');
	
	// Check Login
	if ($login->passwordValid($_GET['mail'], generatePasswordHash($_GET['password']))) {
		$myLogin['valid'] = true;
		$myUser = $user->getUserByEmail($_GET['mail']);
		$myLogin['userid'] = $myUser['userid'];
	}
	else {
		$myLogin['valid'] = false;
	}
	
	// Assign Login variable
	$smarty->assign('login', $myLogin);
	
	if ($myLogin['valid']) {
		// Read Buddies
		$buddies = array();
		$buddies = $db->selectList($tbl_b . "`, `" . $tbl_u, "*", 
			"((
				`".$tbl_b."`.`userid`=".$myUser['userid']." 
				AND `".$tbl_b."`.`buddyid` = `".$tbl_u."`.`userid`
			)
			OR (
				`".$tbl_b."`.`buddyid`=".$myUser['userid']." 
				AND `".$tbl_b."`.`userid` = `".$tbl_u."`.`userid`
			)
			) AND `".$tbl_b."`.`accepted`=1");
		$smarty->assign('buddies', $buddies);
		
		// Set online state
		if (isset($_GET['login'])) {
			if ($_GET['login'] == 'login')
				$value = 1;
			if ($_GET['login'] == 'logout')
				$value = 0;
			$db->update('buddylist', "`client_online`=".$value, "`userid`=".$myLogin['userid']);
		}
	}
	
	// Load template and display
	$smarty->display('../client/client.tpl');
	
?>