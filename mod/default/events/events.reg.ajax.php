<?php
	
	if (!$rights->isAllowed('events', 'manage'))
		die();
	
	if (isset($_GET['reg'])) {
		if ($_GET['reg'] == '1') {
			$db->insert('register', array('eventid', 'userid'),
									array((int)$_GET['eventid'], (int)$_GET['userid']));
		}
		else {
			$db->delete('register', "`userid`=".(int)$_GET['userid']." AND 
				`eventid`=".(int)$_GET['eventid']);
		}
	}
	else {
	
		$find = secureMySQL($_GET['find']);
		$tbl_users = MYSQL_TABLE_PREFIX . "users";
		$tbl_register = MYSQL_TABLE_PREFIX . "register";
		
		if (strlen($find) > 1) {
			
			$sql = "SELECT * FROM
					`" . $tbl_users . "`
					WHERE
					(
						INSTR(`" . $tbl_users . "`.`nickname`, '" . $find . "') > 0
						OR
						INSTR(`" . $tbl_users . "`.`prename`, '" . $find . "') > 0
						OR
						`" . $tbl_users . "`.`userid`=" . (int)$find . "
					)
					LIMIT 10;
			";
			
			$result = $db->query($sql);
			while ($row = mysql_fetch_assoc($result)) {
				$row['reg'] = (int)$db->num_rows('register', 
					"`userid`=".$row['userid']." AND `eventid`=".(int)$_GET['eventid']) > 0;
				
				$userList[] = $row;
			}
		
		}
		
		$smarty->assign('userList', @$userList);
		$smarty->display('../mod/default/events/results.reg.ajax.tpl');
	}	
?>