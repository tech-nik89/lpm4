<?php
	
	global $current_language;
	
	if ($this->isInstalled('calendar')) {
		$area['title'] = $lang->get('calendar');
		$area['content'] = '';
		if ($login->currentUser() === false) {
			$cal_list = $db->selectList('calendar', "*", 
				"(`start` > " . time() . " AND `visible`=2)
				AND (`language` = '' OR `language` = '".$current_language."')", "`start` ASC", "5");
		}
		else {
			$cal_list = $db->selectList('calendar', "*", 
				"(`start` > " . time() . " AND (`visible`=2 OR `visible`=1 OR (`visible`=0 AND `userid`=".
					$login->currentUserId().")))
					AND (`language` = '' OR `language` = '".$current_language."')", "`start` ASC", "5");
		}
		
		if (null != $cal_list && count($cal_list) > 0) {
			foreach ($cal_list as $cal) {
				$time = time() > $cal['start'] ? timeElapsed($cal['start']) : timeLeft($cal['start']);
				$area['content'] = $area['content'] . "<p>&raquo; ".makeHtmlURL($cal['title'], 
					makeURL('calendar', array('calendarid' => $cal['calendarid'], 'mode' => 'view', 'day' => $cal['start']))) . 
					' (' . $time . ')</p>';
			}
		}
		$areas[] = $area;
	}
?>