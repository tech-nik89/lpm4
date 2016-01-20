<?php
	
	if (trim($_GET['search_string']) != '') {
		$lang->addModSpecificLocalization('admission');
		
		$table = MYSQL_TABLE_PREFIX . 'events';
		$tbl_reg = MYSQL_TABLE_PREFIX . 'register';
		$tbl_users = MYSQL_TABLE_PREFIX . 'users';
		
		$paystates = array(	0 => $lang->get('not_payed'),
						1 => $lang->get('payed_pre'),
						2 => $lang->get('payed_box_office'),
						3 => $lang->get('pay_reserv'),
						4 => $lang->get('pay_wait'),
						5 => $lang->get('pay_assistant'),
						6 => $lang->get('pay_vip'),
						7 => $lang->get('pay_press'),
						8 => $lang->get('pay_bonus')
					);
		
		$result = $db->query("SELECT * FROM `" . $tbl_reg . "`, `" . $tbl_users . "`
						WHERE `" . $tbl_reg . "`.`userid` = `" . $tbl_users . "`.`userid`
						AND `" . $tbl_reg . "`.`eventid` = " . (int)$_GET['eventid'] . "
						AND 
						(INSTR(`" .$tbl_users . "`.`nickname`, '" . secureMySQL($_GET['search_string']) . "') > 0
						OR INSTR(`" .$tbl_users . "`.`prename`, '" . secureMySQL($_GET['search_string']) . "') > 0
						OR INSTR(`" .$tbl_users . "`.`lastname`, '" . secureMySQL($_GET['search_string']) . "') > 0)
						LIMIT 10;");
		
		while ($row = mysql_fetch_assoc($result))
			$l[] = $row;
		
		$smarty->assign('search_string', $_GET['search_string']);
		$smarty->assign('paystates', $paystates);
		$smarty->assign('list', @$l);
		$smarty->display('../mod/default/admission/userlist.tpl');
	}
	
?>