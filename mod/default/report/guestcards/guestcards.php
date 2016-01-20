<?php
	
	$tbl_user = "`".MYSQL_TABLE_PREFIX."users`";
	$tbl_reg = "`".MYSQL_TABLE_PREFIX."register`";
	
	$eventid = (int)$_POST['eventid'];
	
	switch ((int)$_POST['viewid']) {
		case 0:
			$sql = "SELECT $tbl_user.`userid`, `prename`, `lastname`, `nickname`
					FROM $tbl_user, $tbl_reg
					WHERE $tbl_user.`userid` = $tbl_reg.`userid`
					AND $tbl_reg.`eventid`=$eventid";
			break;
		case 1:
			$sql = "SELECT $tbl_user.`userid`, `prename`, `lastname`, `nickname`
					FROM $tbl_user, $tbl_reg
					WHERE $tbl_user.`userid` = $tbl_reg.`userid`
					AND $tbl_reg.`payed` > 0
					AND $tbl_reg.`eventid`=$eventid";
			break;
		case 2:
			$sql = "SELECT $tbl_user.`userid`, `prename`, `lastname`, `nickname`
					FROM $tbl_user, $tbl_reg
					WHERE $tbl_user.`userid` = $tbl_reg.`userid`
					AND $tbl_reg.`payed` = 0
					AND $tbl_reg.`eventid`=$eventid";
			break;
		case 3:
			$sql = "SELECT $tbl_user.`userid`, `prename`, `lastname`, `nickname`
					FROM $tbl_user, $tbl_reg
					WHERE $tbl_user.`userid` = $tbl_reg.`userid`
					AND $tbl_reg.`payed` != 0
					AND $tbl_reg.`payed` != 2
					AND $tbl_reg.`eventid`=$eventid";
			break;
		case 4:
			$sql = "SELECT $tbl_user.`userid`, `prename`, `lastname`, `nickname`
					FROM $tbl_user, $tbl_reg
					WHERE $tbl_user.`userid` = $tbl_reg.`userid`
					AND $tbl_reg.`payed` = 2
					AND $tbl_reg.`eventid`=$eventid";
			break;
		case 5:
			$sql = "SELECT $tbl_user.`userid`, `prename`, `lastname`, `nickname`
					FROM $tbl_user";
			break;
		case 6:
			$sql = "";
			break;
		case 7:
			$sql = "SELECT `userid`, `prename`, `lastname`, `nickname`
					FROM $tbl_user
					WHERE `userid` IN (".secureMySQL($_POST['single_users']).")";
			break;
	}
	
	if ($sql != "") {
		$result = $db->query($sql);
		$ulist = array();
		while ($u = mysql_fetch_assoc($result)) {
			$ulist[] = $u;
		}
	}
	else {
		for ($i = 0; $i < 10; $i++) {
			$ulist[] = array('prename' => '', 'lastname' => '', 'nickname', 'userid' => '');
		}
	}
	$smarty->assign('users', $ulist);
	
	$smarty->display('../mod/default/report/guestcards/guestcards.tpl');
	
?>