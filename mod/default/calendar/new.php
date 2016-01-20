<?php
	
	if (isset($_POST['save'])) {
		$start_date = @explode("/", $_POST['start_date']);
		$end_date = @explode("/", $_POST['end_date']);
		
		$start = mktime((int)$_POST['start_Hour'], (int)$_POST['start_Minute'], 0, (int)$start_date[0], (int)$start_date[1], (int)$start_date[2]);
		$end = mktime((int)$_POST['end_Hour'], (int)$_POST['end_Minute'], 0, (int)$end_date[0], (int)$end_date[1], (int)$end_date[2]);
		
		$fifteen_min = 15 * 60;
		
		if (($end - $start) < $fifteen_min)
			$end = $end + $fifteen_min;
		
		if (count($start_date) == 3 && count($end_date) == 3 && trim($_POST['title']) != '')
		{	
			if ($start >= $end)
			{
				$tmp = $start;
				$start = $end;
				$end = $tmp;
			}
			
			$db->insert($tbl, array('title', 'start', 'end', 'visible', 'description', 'userid', 'language', 'categoryId'),
					array("'".$_POST['title']."'", $start, $end, (int)$_POST['visibility'], "'".$_POST['description']."'", $login->currentUserID(), "'".$_POST['language']."'", (int) $_POST['category'],));
			
			writeExport();
			
			redirect(makeURL($mod, array('day' => (time() - 60), 'view' => $view)));
			
		} else {
			$notify->add($lang->get('calendar'), $lang->get('new_calendar_entry_fill'));
		}
		
		$entry['title'] = $_POST['title'];
		$entry['start'] = $start;	$entry['start_date'] = $_POST['start_date'];
		$entry['end'] = $end;		$entry['end_date'] = $_POST['end_date'];
		$entry['description'] = $_POST['description'];
		$entry['visible'] = $_POST['visibility'];
		$smarty->assign('entry', $entry);
	}
	
	$breadcrumbs->addElement(makeDate($day), makeURL($mod, array('day' => $day, 'view' => $view)));
	$breadcrumbs->addElement($lang->get('new_calendar_entry'), makeURL($mod, array('mode' => 'new', 'day' => $day, 'view' => $view)));
	$entry['visible'] = 0;
	if ($config->get($mod, 'default-visibility') == 'logged-in') $entry['visible'] = 1;
	if ($config->get($mod, 'default-visibility') == 'public') $entry['visible'] = 2;
	$entry['start_date'] = date('m/d/Y', $day);
	$entry['end_date'] = date('m/d/Y', $day);
	$smarty->assign('entry', $entry);
	$smarty->assign('path', $template_dir."/new.tpl");
	$smarty->assign('head', $lang->get('new_calendar_entry'));
	
	require_once('calendar.function.php');
	$smarty->assign('categories', getCategories());
	
?>