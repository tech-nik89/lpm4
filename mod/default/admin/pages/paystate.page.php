<?php
	
	$e = $db->selectOneRow($table, "*", "`eventid`=" . (int)$_GET['eventid']);
	$breadcrumbs->addElement($lang->get('event_paystate'), makeURL($mod, array('mode' => 'paystate', 'eventid' => $e['eventid'])));
	$breadcrumbs->addElement($e['name'], makeURL($mod, array('mode' => 'paystate', 'eventid' => $e['eventid'])));	
	
	if (isset($_POST['search']) and trim($_POST['search_string']) != '')
	{
		if (isset($_POST['save']))
			$db->update($tbl_reg, "`payed`=" . (int)$_POST['paystate'] . ", `appeared`=" . (int)$_POST['appeared'], "`eventid`=" . (int)$_GET['eventid'] . " AND `userid`=" . (int)$_POST['userid'] );
		
		$smarty->assign('search_string', $_POST['search_string']);
		$result = $db->query("SELECT * FROM `" . $tbl_reg . "`, `" . $tbl_users . "`
						WHERE `" . $tbl_reg . "`.`userid` = `" . $tbl_users . "`.`userid`
						AND `" . $tbl_reg . "`.`eventid` = " . (int)$_GET['eventid'] . "
						AND 
						(INSTR(`" .$tbl_users . "`.`nickname`, '" . secureMySQL($_POST['search_string']) . "') > 0
						OR INSTR(`" .$tbl_users . "`.`prename`, '" . secureMySQL($_POST['search_string']) . "') > 0
						OR INSTR(`" .$tbl_users . "`.`lastname`, '" . secureMySQL($_POST['search_string']) . "') > 0)");
		
		while ($row = mysql_fetch_assoc($result))
			$l[] = $row;
		
		$smarty->assign('list', $l);
		
	}
	
	$e['registered'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid']);
	$e['payed'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed` > 0");
	$e['payed_pre'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed`=1");
	$e['payed_box_office'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed`=2");
	
	$smarty->assign('event', $e);
	$smarty->assign('paystates', $paystates);
	$smarty->assign('path', $template_dir . "/paystate.tpl");
	
?>