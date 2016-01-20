<?php
	
	global $current_language;
	
	$range = isset($_GET['range']) ? (int)$_GET['range'] : 14;
	
	if ($login->currentUser() !== false) {
		$list = $db->selectList($tbl, "*", "`end`>".$day." AND ( (`visible` = 2 ) OR (`visible` = 0 AND `userid` = ".$login->currentUserID().") OR ( `visible` = 1) )
			AND (`language` = '' OR `language` = '".$current_language."')", "`start` ASC", $range);
	}
	else {
		$list = $db->selectList($tbl, "*", "(`end`>".$day." AND `visible` = 2)
			 AND (`language` = '' OR `language` = '".$current_language."')", "`start` ASC", "$range");
	}
	if (count($list) > 0)
		foreach ($list as $i => $l)
		{
			$list[$i]['day'] = makeDate($l['start']);
			$list[$i]['time'] = makeTime($l['start']);
			$list[$i]['url'] = makeURL($mod, array('day' => $day, 'calendarid' => $l['calendarid'], 'mode' => 'view'));
			
			if (date('j', $l['start']) != date('j', $l['end'])) {
				$list[$i]['day_end'] = makeDate($l['end']);
			}
			$list[$i]['time_end'] = makeTime($l['end']);
		}
	
	$smarty->assign('entries', $list);
	$smarty->assign('path', $template_dir."/view_next.tpl");
	
?>