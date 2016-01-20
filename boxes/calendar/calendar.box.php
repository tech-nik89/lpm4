<?php
	
	global $current_language;
	
	$lang->addModSpecificLocalization('calendar');
	
	$tpl_file = $template_dir."/default.tpl";
	$tbl = MYSQL_TABLE_PREFIX.'calendar';
	
	$cnt = (int)$config->get('calendar', 'box-number-of-entries');
	if ($cnt == 0) $cnt = 5;
	
	if ($login->currentUser() !== false) {
		$list = $db->selectList($tbl, "*", "(`start`>".time()." AND ( (`visible` = 2 ) OR (`visible` = 0 AND `userid` = ".$login->currentUserID().") OR ( `visible` = 1) ))
			AND (`language` = '' OR `language` = '".$current_language."')", "`start` ASC", $cnt);
	}
	else {
		$list = $db->selectList($tbl, "*", "(`start`>".time()." AND `visible` = 2)
			AND (`language` = '' OR `language` = '".$current_language."')", "`start` ASC", $cnt);
	}
	
	if ($config->get('calendar', 'current-event') == '1') {
		$refresh_time = (int)$config->get('calendar', 'current-event-refresh-time');
		if ($refresh_time <= 3)
			$refresh_time = 15;
		$smarty->assign('calendar_box_refresh_time', $refresh_time);
		$smarty->assign('calendar_box_show_counter', true);
		if ($login->currentUser() !== false) {
			$current_event = $db->selectOneRow('calendar', "*", "`start` < ".time()." AND `end` > ".time()." AND ( (`visible` = 2 ) OR (`visible` = 0 AND `userid` = ".$login->currentUserID().") OR ( `visible` = 1) )");
		}
		else {
			$current_event = $db->selectOneRow('calendar', "*", "`start` < ".time()." AND `end` > ".time()." AND `visible` = 2");
		}
		$current_event['url'] = makeURL('calendar', array('day' => $current_event['start'], 'calendarid' => $current_event['calendarid'], 'mode' => 'view'));
		$smarty->assign('calendar_box_current', $current_event);
	}
	
	if (count($list) > 0)
		foreach ($list as $i => $l)
		{
			//$list[$i]['day'] = makeDate($l['start']);
			//$list[$i]['time'] = makeTime($l['start']);
			$list[$i]['timeleft'] = timeLeft($l['start']);
			$list[$i]['url'] = makeURL('calendar', array('day' => $l['start'], 'calendarid' => $l['calendarid'], 'mode' => 'view'));
		}
	
	$smarty->assign('calendar_box_entries', $list);
	$visible = true;
	
?>