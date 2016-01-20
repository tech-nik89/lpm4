<?php

	$entry = $db->selectOneRow($tbl, "*", "`calendarid`=".$calendarid);
	
	$breadcrumbs->addElement(makeDate($day), makeURL($mod, array('day' => $day, 'view' => $view)));
	$breadcrumbs->addElement($entry['title'], makeURL($mod, array('mode' => 'view', 'day' => $day, 'view' => $view, 'calendarid' => $calendarid)));
	
	$entry['start'] = makeDate($entry['start']) . " " . makeTime($entry['start']);
	$entry['end'] = makeDate($entry['end']) . " " . makeTime($entry['end']);
	$entry['visible'] = visibilityToString($entry['visible']);
	$entry['description'] = $bbcode->parse($entry['description']);
	
	$smarty->assign('entry', $entry);
	$smarty->assign('path', $template_dir . "/view_entry.tpl");
	
	if ($entry['userid'] == $login->currentUserID() or $isallowed)
		$menu->addSubElement($mod, $lang->get('edit_calendar_entry'), 'edit', array('calendarid' => $calendarid));
		
?>