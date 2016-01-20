<?php
	
	// first day of this month
	$first_day = mktime(0, 0, 0, date("n", $day), 1, date("Y", $day));
	
	// weekday with which month starts
	$start_month = date("w", $first_day);
	
	// length of month
	$length_month = date("t", $day);
	
	// last day of this month
	$last_day = mktime(23, 59, 59, date("n", $day), $length_month, date("Y", $day));
	
	// days of month before
	$days_month_before = date("t", strtotime("-1 month", $day));
	
	// all entries for this month
	$list = listEntries($first_day, $last_day);
	
	$smarty->assign('month', $lang->get(strtolower(date("M", $day))));
	$smarty->assign('prev', makeHTMLURL($lang->get('prev_month'), makeURL($mod, array('day' => strtotime("-1 Month", $day), 'view' => $view))));
	$smarty->assign('next', makeHTMLURL($lang->get('next_month'), makeURL($mod, array('day' => strtotime("+1 Month", $day), 'view' => $view))));
	
	$breadcrumbs->addElement(makeDateRange($first_day, $last_day), makeURL($mod, array('view' => 'month', 'day' => $day)));
	
	$current_day = $days_month_before - $start_month;
	$in_month = false;
	
	for ($i = 0; $i <= 41; $i++)
	{
		if ($i == $start_month)
		{
			$in_month = true;
			$current_day = 0;
		}
		if ($i == $start_month + $length_month)
		{
			$in_month = false;
			$current_day = 0;
		}
		$current_day++;
		
		$current_day_ts = mktime(0, 0, 0, date("n", $day), $current_day, date("Y", $day));
		$current_day_ts_start = mktime(0, 0, 0, date("n", $current_day_ts), date("j", $current_day_ts), date("Y", $current_day_ts));
		$current_day_ts_stop = mktime(23, 59, 59, date("n", $current_day_ts), date("j", $current_day_ts), date("Y", $current_day_ts));
		
		if ($in_month)
		{
			$found = false;
			if (count($list) > 0)
			{
				foreach ($list as $entry)
				{
					if ($entry['start'] >= $current_day_ts_start && $entry['start'] <= $current_day_ts_stop)
					{
						$found = true;
						break;
					}
				}
				
			}
			
			if ($found)
				$field[$i] = makeHTMLURL(makeHighlighted($current_day), makeURL($mod, array('day' => $current_day_ts, 'view' => 'day')));
			else
				$field[$i] = makeHTMLURL($current_day, makeURL($mod, array('day' => $current_day_ts, 'view' => 'day')));
			
			
			if ($current_day_ts_start < time() && $current_day_ts_stop > time())
				$field[$i] = makeToday($field[$i]);
			
		} else {
			$field[$i] = makeGrey($current_day);
		}
	}
	
	$smarty->assign('field', $field);
	$smarty->assign('path', $template_dir."/view_month.tpl");
	
?>