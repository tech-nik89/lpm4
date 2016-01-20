<?php
	
	$breadcrumbs->addElement($lang->get('contact'), makeURL($mod, array('mode' => 'contact')));
	
	$tbl_contact = MYSQL_TABLE_PREFIX . "contact";
	$contactid = isset($_GET['contactid']) ? (int)$_GET['contactid'] : 0;
	
	if (isset($_POST['delete']))
	{
		$db->delete($tbl_contact, "`contactid`=".$contactid);
		$contactid = 0;
		$notify->add($lang->get('contact'), $lang->get('contact_deleted'));
		$log->add($mod, 'removed contact entry ' . $contactid);
	}
	
	$smarty->assign('contactid', $contactid);
	
	if ($contactid == 0)
	{
		$list = $db->selectList($tbl_contact);
		
		if (count($list) > 0)
		foreach ($list as $i => $l)
		{
			$u = $user->getUserByID($l['userid']);
			$list[$i]['sender'] = makeHTMLURL($u['nickname'], makeURL('profile', array('userid' => $l['userid'])));
			$list[$i]['timestamp'] = timeElapsed($l['timestamp']);
			$list[$i]['url'] = makeURL($mod, array('mode' => 'contact', 'contactid' => $l['contactid']));
			$list[$i]['read'] = intToYesNo($l['read']);
			$list[$i]['done'] = intToYesNo($l['done']);
			$list[$i]['comments'] = $comments->count($mod, $l['contactid']);
		}
		
		$smarty->assign('requests', $list);
	} else {
		
		$tbl_groupware = MYSQL_TABLE_PREFIX . "groupware";
		
		$request = $db->selectOneRow($tbl_contact, "*", "`contactid`=".$contactid);
		$breadcrumbs->addElement($request['subject'], makeURL($mod, array('mode' => 'contact', 'contactid' => $contactid)));
		
		if ($db->num_rows($tbl_groupware, "`contactid`=" . $contactid) > 0)
			$request['isInGroupware'] = true;
		
		if (isset($_POST['addcomment']))
			$comments->add($mod, $login->currentUserID(), $_POST['text'], $contactid);
		
		$smarty->assign('comments', $comments->get($mod, $contactid));
		
		if ($request['read'] == 0)
		{
			$request['read'] = 1;
			$db->update($tbl_contact, "`read`=1", "`contactid`=".$contactid);
		}
			
		if (isset($_POST['done']))
		{
			$db->update($tbl_contact, "`done`=".$login->currentUserID(), "`contactid`=".$contactid);
			$request['done'] = $login->currentUserID();
			$log->add($mod, 'contact entry ' . $contactid . ' marked as done');
		}
		
		if (isset($_POST['move_to_groupware']))
		{	
			$end = strtotime("+1 Hour");
			$db->insert($tbl_groupware, array('title', 'description', 'state', 'end', 'priority', 'contactid', 'userid'),
							array("'".$request['subject']."'", "'".$request['text']."'", 0, $end, 1, $contactid, 0));
					
			$notify->add($lang->get('groupware'), $lang->get('copy_to_groupware_done'));
			$request['isInGroupware'] = true;
		}
		
		$u = $user->getUserByID($request['userid']);
		
		$request['sender'] = makeHTMLURL($u['nickname'], makeURL('profile', array('userid' => $request['userid'])));
		$request['timestamp'] = timeLeft($request['timestamp']);
		$request['text'] = $bbcode->parse($request['text']);
		$request['read'] = intToYesNo($request['read']);
		
		if ($request['done'] > 0)
		{
			$u = $user->getUserByID($request['done']);
			$request['done_by'] = makeHTMLURL($u['nickname'], makeURL('profile', array('userid' => $u['userid'])));
		}
		
		$smarty->assign('delete_url', makeURL($mod, array('mode' => 'contact')));
		$smarty->assign('request', $request);
		
	}
	
	$smarty->assign('path', $template_dir ."/contact.tpl");
	
?>