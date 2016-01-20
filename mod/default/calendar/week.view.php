<?php
	
	// current weekday
	$weekday = date("w", $day);
	
	$smarty->assign('prev', makeHTMLURL($lang->get('prev_week'), makeURL($mod, array('day' => strtotime("-1 Week", $day), 'view' => $view))));
	$smarty->assign('next', makeHTMLURL($lang->get('next_week'), makeURL($mod, array('day' => strtotime("+1 Week", $day), 'view' => $view))));
	
	// start end end of week
	$week_start = strtotime("-$weekday day", $day);
	$week_start = mktime(0, 0, 0, date("n", $week_start), date("d", $week_start), date("Y", $week_start));
	$week_end = strtotime("+6 days", $week_start);
	$week_end = mktime(23, 59, 59, date("n", $week_end), date("d", $week_end), date("Y", $week_end));
	
	$breadcrumbs->addElement(makeDateRange($week_start, $week_end), makeURL($mod, array('day' => $day, 'view' => $view)));

	$birthdays = array();
	$allCols = array();
	for ($i = 0; $i < 7; $i++)
	{
		$key = $i - $weekday;
		$current_day_ts = strtotime("+$key day", $day);
		$current_day_ts_start = mktime(0, 0, 0, date("n", $current_day_ts), date("d", $current_day_ts), date("Y", $current_day_ts));
		$current_day_ts_end = mktime(23, 59, 59, date("n", $current_day_ts), date("d", $current_day_ts), date("Y", $current_day_ts));

		$list = listEntries($current_day_ts_start, $current_day_ts_end);
		
		$fields[$i]['hours'] = makeEntryArrayHours($list, $cols);
		$allCols[$i] = $cols+1;
	
		$fields[$i]['date'] = makeHTMLURL(date("d.m", $current_day_ts), makeURL($mod, array('view' => 'day', 'day' => $current_day_ts)));
	
		$birthdays[$i] = getTodaysBirthdays($current_day_ts_start);
	}
	
	$tbl_info = array();
	$maxCols = array_sum($allCols);
	$dayWidth = 100 / 7;
	for($i = 0; $i < 7; $i++) {
		for($j = 0; $j < $allCols[$i] ; $j++) {
			$tbl_info[] = $dayWidth/($allCols[$i]);
		}
	}
	$smarty->assign('colspans', $allCols);
	$smarty->assign('tbl_info', $tbl_info);
	$smarty->assign('fields', $fields);
	
	$birthdaysThisWeek = false;
	if($config->get('calendar', 'show-birthdays')==1) {
		for($i=0; $i<7; $i++){
			if(count($birthdays[$i])>0) {
				$birthdaysThisWeek=true;
				break;
			}
		}
	}
	
	$smarty->assign('birthdays', $birthdaysThisWeek?$birthdays:"");
	$smarty->assign('path', $template_dir."/view_week.tpl");
	
?>