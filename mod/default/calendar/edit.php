<?php
	
	if (@$_GET['delete'] == 1)
	{
		$db->delete($tbl, "`calendarid`=".$calendarid);
		$notify->add($lang->get('calendar'), $lang->get('deleted'));
		
		writeExport();
		
	} else {
		
		$menu->addSubElement($mod, $lang->get('delete_term'), 'edit', array('calendarid' => $calendarid, 'delete' => 1));
		
		// get current entry
		$entry = $db->selectOneRow($tbl, "*", "`calendarid`=".$calendarid);
		
		// add breadcrumbs
		$breadcrumbs->addElement(makeDate($day), makeURL($mod, array('day' => $day, 'view' => $view)));
		$breadcrumbs->addElement($entry['title'], makeURL($mod, array('mode' => 'view', 'day' => $day, 'view' => $view, 'calendarid' => $calendarid)));
		$breadcrumbs->addElement($lang->get('edit_calendar_entry'), makeURL($mod, array('mode' => 'edit', 'day' => $day, 'view' => $view, 'calendarid' => $calendarid)));
		
		// prepare date and time
		$entry['start_date'] = date("m/d/Y", $entry['start']);
		$entry['end_date'] = date("m/d/Y", $entry['end']);
		
		// save button pressed?
		if (isset($_POST['save']))
		{
			
			// save new data
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
				
				$db->update($tbl, "`title`='".secureMySQL($_POST['title'])."',
									`start`=".$start.", `end`=".$end.", `visible`=".(int)$_POST['visibility'].",
									`description`='".secureMySQL($_POST['description'])."',
									`language`='".secureMySQL($_POST['language'])."',
									`categoryId`='".secureMySQL((int) $_POST['category'])."'",
									"`calendarid`=".$calendarid);
				
				writeExport();
				
				redirect(makeURL($mod, array('day' => $start, 'view' => $view)));
				
			} else {
				$notify->add($lang->get('calendar'), $lang->get('new_calendar_entry_fill'));
			}
			
			$entry['title'] = $_POST['title'];
			$entry['start'] = $start;	
			$entry['start_date'] = $_POST['start_date'];
			$entry['end'] = $end;		
			$entry['end_date'] = $_POST['end_date'];
			$entry['description'] = $_POST['description'];
			$entry['visible'] = $_POST['visibility'];
			$entry['category'] = $_POST['category'];
			
			redirect(makeURL($mod));
		}
		
		require_once('calendar.function.php');
		$smarty->assign('categories', getCategories());
		

		// add template
		$smarty->assign('entry', $entry);
		$smarty->assign('path', $template_dir."/new.tpl");
		$smarty->assign('head', $lang->get('edit_calendar_entry'));
	}
?>