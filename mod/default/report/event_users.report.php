<?php
	
	$lang->addModSpecificLocalization('report');
	
	$tbl_user = "`".MYSQL_TABLE_PREFIX."users`";
	$tbl_reg = "`".MYSQL_TABLE_PREFIX."register`";
	
	$eventid = (int)$_GET['eventid'];
	
	switch ($_GET['mode']) {
		case 'registered':
			$sql = "SELECT `prename`, `lastname`, `nickname`
					FROM $tbl_user, $tbl_reg
					WHERE $tbl_user.`userid` = $tbl_reg.`userid`
					AND $tbl_reg.`eventid`=$eventid
					ORDER BY `lastname`";
			$mode = 'registered_users';
			break;
		case 'payed':
			$sql = "SELECT `prename`, `lastname`, `nickname`
					FROM $tbl_user, $tbl_reg
					WHERE $tbl_user.`userid` = $tbl_reg.`userid`
					AND $tbl_reg.`payed` > 0
					AND $tbl_reg.`eventid`=$eventid
					ORDER BY `lastname`";
			$mode = 'payed_users';
			break;
		case 'not_payed':
			$sql = "SELECT `prename`, `lastname`, `nickname`
					FROM $tbl_user, $tbl_reg
					WHERE $tbl_user.`userid` = $tbl_reg.`userid`
					AND $tbl_reg.`payed` = 0
					AND $tbl_reg.`eventid`=$eventid
					ORDER BY `lastname`";
			$mode = 'not_payed_users';
			break;
		case 'payed_pre':
			$sql = "SELECT `prename`, `lastname`, `nickname`
					FROM $tbl_user, $tbl_reg
					WHERE $tbl_user.`userid` = $tbl_reg.`userid`
					AND $tbl_reg.`payed` != 0
					AND $tbl_reg.`payed` != 2
					AND $tbl_reg.`eventid`=$eventid
					ORDER BY `lastname`";
			$mode = 'payed_pre';
			break;
		case 'payed_box_office':
			$sql = "SELECT `prename`, `lastname`, `nickname`
					FROM $tbl_user, $tbl_reg
					WHERE $tbl_user.`userid` = $tbl_reg.`userid`
					AND $tbl_reg.`payed` = 2
					AND $tbl_reg.`eventid`=$eventid
					ORDER BY `lastname`";
			$mode = 'payed_box_office';
			break;
		case 'all':
			$sql = "SELECT `prename`, `lastname`, `nickname`
					FROM $tbl_user
					ORDER BY `lastname`";
			$mode = 'all_users';
			break;
	}
	
	$result = $db->query($sql);
	$ulist = array();
	while ($u = mysql_fetch_assoc($result)) {
		$ulist[] = $u;
	}
	$smarty->assign('users', $ulist);
	
	$smarty->assign('mode', $lang->get($mode));
	$smarty->display('../mod/default/report/user_report.tpl');
	
?>