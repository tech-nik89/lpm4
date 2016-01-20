<?php
	
	$smarty->assign('path', $template_dir."/view_day.tpl");
	
	$breadcrumbs->addElement(makeDate($day), makeURL($mod, array('day' => $day, 'view' => $view)));
	
	$smarty->assign('prev', makeHTMLURL($lang->get('prev_day'), makeURL($mod, array('day' => strtotime("-1 Day", $day), 'view' => $view))));
	$smarty->assign('next', makeHTMLURL($lang->get('next_day'), makeURL($mod, array('day' => strtotime("+1 Day", $day), 'view' => $view))));
	
	$list = listEntries($day);
	$day_view = makeEntryArrayQuarters($list, $maxCols);

	$tbl_info = array();
	$width =  (int) floor(85/($maxCols+1));
	for($i = 1; $i <= $maxCols+1; $i++) {
		$tbl_info[$i] = $width;
	}

	$smarty->assign('tbl_info', $tbl_info);
	$smarty->assign('birthdays', ($config->get('calendar', 'show-birthdays')==1)?getTodaysBirthdays($day):"");
	$smarty->assign('day_view', $day_view);
	
?>