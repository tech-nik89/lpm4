<?php

	$tbl = MYSQL_TABLE_PREFIX.'guestbook';
	
	$lang->addModSpecificLocalization($mod);
	
	$isallowed = $rights->isAllowed($mod, 'manage');
	$ip_blocker_enabled = $config->get($mod, 'ip-blocker-enable');
	$ip_blocker_limit = $config->get($mod, 'ip-blocker-timelimit');
	
	$smarty->assign('isallowed', $isallowed);
	$smarty->assign('path', $template_dir."/default.tpl");
	
	$usr = $login->currentUser();
	if ($usr !== false)
		$smarty->assign('nickname', $usr['nickname']);
	
	if (isset($_POST['delete']) && $isallowed)
	{
		$db->delete($tbl, "`guestbookid`=".(int)$_POST['guestbookid']);
		$notify->add($lang->get('guestbook'), $lang->get('deleted'));
	}
	
	if (isset($_POST['add']) && $_POST['email'] == '')
	{
		if (trim($_POST['author']) == '' || trim($_POST['message']) == '')
		{
			$notify->add($lang->get('guestbook'), $lang->get('fill_all'));
		} else {
			
			if (($ip_blocker_enabled == 1 && 
				$db->num_rows($tbl, "`ipadress`='".getRemoteAdr()."' AND `timestamp`>".(time()-$ip_blocker_limit)) == 0) ||
				$ip_blocker_enabled == 0)
			{
				$db->insert($tbl, array('timestamp', 'author', 'message', 'ipadress'),
					array(time(), "'".$_POST['author']."'", "'".strip_tags($_POST['message'])."'", "'".getRemoteAdr()."'"));
			} else {
				$notify->add($lang->get('guestbook'), $lang->get('ip-blocker'));
			}
		}
	}
	
	$messageCount = $db->num_rows($tbl, "1");
	$messagesPerPage = $config->get($mod, 'entries-per-page');
	@$pages->setValues((int)$_GET['page'], $messagesPerPage, $messageCount);
	
	$list = $db->selectList($tbl, "*", "1", "`timestamp` DESC", ($pages->currentValue()) . ", " . (int)$messagesPerPage);
	if (count($list) > 0)
		foreach ($list as $i => $l)
		{
			$list[$i]['time'] = timeElapsed($l['timestamp']);
			$list[$i]['message'] = $bbcode->parse($l['message']);
		}
	$smarty->assign('list', $list);
	$smarty->assign('pages', $pages->get($mod, array()));
	
?>