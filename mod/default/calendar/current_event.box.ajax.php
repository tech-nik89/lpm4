<?php

	$lang->addModSpecificLocalization('calendar');
	$smarty->assign('lang', $lang->getAll());
	
	if ($login->currentUser() !== false) {
		$current_event = $db->selectOneRow('calendar', "*", "`start` < ".time()." AND `end` > ".time()." AND ( (`visible` = 2 ) OR (`visible` = 0 AND `userid` = ".$login->currentUserID().") OR ( `visible` = 1) )");
	}
	else {
		$current_event = $db->selectOneRow('calendar', "*", "`start` < ".time()." AND `end` > ".time()." AND `visible` = 2");
	}
	if ($current_event) {
		$secs = $current_event['end'] - time();
		$current_event['time_left_seconds'] = $secs;
		
		$smarty->assign('calendar_box_current', $current_event);
		
		$smarty->display('../mod/default/calendar/current_event.box.tpl');
	}
?>