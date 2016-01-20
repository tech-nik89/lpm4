<?php
	
	//$lang->addModSpecificLocalization('events');
	
	$table = MYSQL_TABLE_PREFIX . 'events';
	$tbl_reg = MYSQL_TABLE_PREFIX . 'register';
	$tbl_users = MYSQL_TABLE_PREFIX . 'users';
	
	
	$e = $db->selectOneRow($table, "*", "`end`>" . time(), "`start` ASC", "1");
	if (@$e['eventid'] == '') {
		$tpl_file = $template_dir."/none.tpl";
	}
	else {
		$e['url'] = makeURL('events', array('eventid' => $e['eventid']));
		
		//$e['start'] = date('d.m.Y / H:i', $e['start']);
		$e['start'] = time() > $e['start'] ? timeElapsed($e['start']) : timeLeft($e['start']);
		$e['end'] = date('d.m.Y / H:i', $e['end']);
		
		$e['registered'] = $db->num_rows($tbl_reg, "`eventid`=" . $e['eventid']);
		
		$e['payed'] = $db->num_rows($tbl_reg, "`eventid`=" . $e['eventid'] . " AND `payed` > 0");
		$e['payed_pre'] = $db->num_rows($tbl_reg, "`eventid`=" . $e['eventid'] . " AND `payed`!=0 AND `payed`!=2");
		$e['payed_box_office'] = $db->num_rows($tbl_reg, "`eventid`=" . $e['eventid'] . " AND `payed`=2");
		
		$e['seats_free'] = $e['seats'] - $e['registered'];
		
		$bar_width = 100;
		
		// is user registered?
		$reg = $db->num_rows($tbl_reg, "`userid`=".$login->currentUserID()." 
										AND `eventid`=".$e['eventid']);
		$smarty->assign('box_events_reg', $reg);
		
		if ($reg == 1)
			$smarty->assign('box_events_state', $lang->get('event_registered'));
		else
			$smarty->assign('box_events_state', $lang->get('event_not_registered'));
		
		if ($e['free'] == 0)
			$e['not_payed'] = $e['registered'] - $e['payed'];
		
		// Paystate bar
		if ($e['free'] == 0)
		{
			$smarty->assign('box_payed_width', ((100 * $e['payed']) / $e['seats']) * ($bar_width / 100));
			$smarty->assign('box_not_payed_width', ((100 * ($e['not_payed'])) / $e['seats']) * ($bar_width / 100));
			$smarty->assign('box_free_width', ((100 * ($e['seats_free'])) / $e['seats']) * ($bar_width / 100));
		} else {
			$smarty->assign('box_payed_width', ((100 * $e['registered']) / $e['seats']) * ($bar_width / 100));
			$smarty->assign('box_not_payed_width', 0);
			$smarty->assign('box_free_width', ((100 * ($e['seats_free'])) / $e['seats']) * ($bar_width / 100));
		}
		
		$smarty->assign('box_events_event', $e);
		$tpl_file = $template_dir."/event.tpl";
		
		if ($login->currentUser() !== false)
			$smarty->assign('logged_in', true);
		else
			$smarty->assign('logged_in', false);
	}
	
	$visible = true;
	
?>