<?php
	
	$smarty->assign('path', $template_dir."/view_year.tpl");
	
	$smarty->assign('prev', makeHTMLURL($lang->get('prev_year'), makeURL($mod, array('day' => strtotime("-1 year", $day), 'view' => $view))));
	$smarty->assign('next', makeHTMLURL($lang->get('next_year'), makeURL($mod, array('day' => strtotime("+1 year", $day), 'view' => $view))));
	
	// first and last day
	$first_day = mktime(0, 0, 0, 1, 1, date("Y", $day));
	$last_day = mktime(23, 59, 59, 12, 31, date("Y", $day));
	
	$breadcrumbs->addElement(makeDateRange($first_day, $last_day), makeURL($mod, array('day' => $day, 'view' => $view)));
	
	// all entries for this month
	$list = listEntries($first_day, $last_day);
	
	for ($month = 0; $month < 12; $month++)
	{
		$month_ts = mktime(0, 0, 0, $month + 1, 1, date("Y", $day));
		$days_of_month = date("t", $month_ts);
		
		for ($days = 1; $days <= $days_of_month; $days++)
		{
			$current_day_ts_start = mktime(0, 0, 0, $month + 1, $days, date("Y", $day));
			$current_day_ts_end = mktime(23, 59, 59, $month + 1, $days, date("Y", $day));
			
			$found = false;
			if (count($list) > 0)
			{
				foreach ($list as $entry)
				{
					if ($entry['start'] >= $current_day_ts_start && $entry['start'] <= $current_day_ts_end)
					{
						$found = true;
						break;
					}
				}
				
			}
			
			if ($found)
				$fields[$month][$days-1]['content'] = makeHTMLURL(makeHighlighted($days), makeURL($mod, array('day' => $current_day_ts_start, 'view' => 'day')));
			else
				$fields[$month][$days-1]['content'] = makeHTMLURL($days, makeURL($mod, array('day' => $current_day_ts_start, 'view' => 'day')));
				
			if ($current_day_ts_start < time() && $current_day_ts_end > time())
				$fields[$month][$days-1]['content'] = makeToday($fields[$month][$days-1]['content']);
				
			$fields[$month][$days-1]['flag'] = date("D", $current_day_ts_start);
		}
	}
	
	$smarty->assign('month', $fields);
	
?>