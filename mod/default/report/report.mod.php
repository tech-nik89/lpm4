<?php
	
	$lang->addModSpecificLocalization($mod);
	
	if ($rights->isAllowed('admin', 'users')) {
	
		// Generate URLs
		$url['guestcards'] = 'ajax_request.php?mod=report&file=guestcards/guestcards';
		$url['all_users'] = 'ajax_request.php?mod=report&file=all_users.report';
		$url['event_users'] = 'ajax_request.php?mod=report&file=event_users.report&eventid=%eventid%&mode=%mode%';
		$smarty->assign('url', $url);
		
		$smarty->assign('path', $template_dir."/default.tpl");
		
		// Read events
		$events = $db->selectList('events');
		$smarty->assign('events', $events);
		
		// Set views
		$views[] = array('viewid' => 0, 'name' => $lang->get('registered_users'));
		$views[] = array('viewid' => 1, 'name' => $lang->get('payed_users'));
		$views[] = array('viewid' => 2, 'name' => $lang->get('not_payed_users'));
		$views[] = array('viewid' => 3, 'name' => $lang->get('payed_pre'));
		$views[] = array('viewid' => 4, 'name' => $lang->get('payed_box_office'));
		$views[] = array('viewid' => 5, 'name' => $lang->get('all_users'));
		$views[] = array('viewid' => 6, 'name' => $lang->get('empty_cards'));
		$views[] = array('viewid' => 7, 'name' => $lang->get('single_users'));
		
		$smarty->assign('views', $views);
		
	}
	else {
		$notify->add($lang->get('reports'), $lang->get('not_allowed'));
	}
?>