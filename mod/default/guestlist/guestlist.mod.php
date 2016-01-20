<?php
	
	/**
	 * Project: Higher For Hire
	 * File: admin.mod.php
	 *
	 */
	
	$paystates = array(	'' => '-',
					0 => $lang->get('not_payed'),
					1 => $lang->get('payed_pre'),
					2 => $lang->get('payed_box_office'),
					3 => $lang->get('not_payed'),
					4 => $lang->get('not_payed'),
					5 => $lang->get('payed_pre'),
					6 => $lang->get('payed_pre'),
					7 => $lang->get('payed_pre'),
					8 => $lang->get('payed_pre'),
					9 => $lang->get('payed_pre')
				);
	
	// add breadcrumbs
	$breadcrumbs->addElement($lang->get('guestlist'), makeURL('guestlist'));
	
	// include the template
	$smarty->assign('path', $template_dir . '/guestlist.tpl');
	
	// add submenus
	$menu->addSubElement($mod, $lang->get('all'), '');
	
	// get events
	$tbl_events = MYSQL_TABLE_PREFIX . "events";
	$list = $db->selectList($tbl_events, "*", "1", "`start` DESC");
	
	if (count($list) > 0)
	foreach ($list as $l)
		$menu->addSubElement($mod, $l['name'], "", array('eventid' => $l['eventid']));
	
	
	// how many users do we show per page
	$upp = $config->get($mod, 'users-per-page');
	
	// search
	if (isset($_POST['go']))
	{
		$find = secureMySQL($_POST['find']);
		$_SESSION['find'] = $find;
	}
	
	@$find = $_SESSION['find'];
	$smarty->assign('find', $find);
	
	$debug->add('find', $find);
	
	// order by
	@$order = secureMySQL($_GET['order']);
	if ($order == '')
		$order = 'nickname';
		
	@$dir = secureMySQL($_GET['dir']);
	if ($dir == '')
		$dir = 'ASC';
		
	if ($dir == 'ASC')
		$other_dir = 'DESC';
	else
		$other_dir = 'ASC';
	
	@$page = (int)$_GET['page'];
	if ($page == 0) $page = 1;
	
	if (@(int)$_GET['eventid'] == 0)
	{
		// add another breadcrumb
		$breadcrumbs->addElement($lang->get('all'), makeURL($mod));
		
		// usercount
		$tbl_users = MYSQL_TABLE_PREFIX . "users";
		$tbl_register = MYSQL_TABLE_PREFIX . "register";
		
		$sql = "SELECT * FROM
				`" . $tbl_users . "`
				WHERE
				(
					INSTR(`" . $tbl_users . "`.`nickname`, '" . $find . "') > 0
					OR
					INSTR(`" . $tbl_users . "`.`prename`, '" . $find . "') > 0
					OR
					`" . $tbl_users . "`.`userid`=" . (int)$find . "
				)
		";
		
		$result = $db->query($sql);
		$uc = mysql_num_rows($result);
		
		@$pages->setValues((int)$_GET['page'], $upp, $uc);
		
		$sql = "SELECT * FROM
				`" . $tbl_users . "`
				WHERE
				(
					INSTR(`" . $tbl_users . "`.`nickname`, '" . $find . "') > 0
					OR
					INSTR(`" . $tbl_users . "`.`prename`, '" . $find . "') > 0
					OR
					`" . $tbl_users . "`.`userid`=" . (int)$find . "
				)
				ORDER BY `" . $order . "` " . $dir . "
				LIMIT "  . ($pages->currentValue()) . ", " . (int)$upp . "
		";
		
		$result = $db->query($sql);
		
		while ($row = mysql_fetch_assoc($result))
			$users[] = $row;
	
	}
	else
	{
		@$event = $db->selectOneRow($tbl_events, "*", "`eventid`=".(int)$_GET['eventid']);
		$breadcrumbs->addElement($event['name'], makeURL($mod, array('eventid' => $event['eventid'])));
		
		// usercount
		$tbl_users = MYSQL_TABLE_PREFIX . "users";
		$tbl_register = MYSQL_TABLE_PREFIX . "register";
		
		$sql = "SELECT * FROM
				`" . $tbl_users . "`, `" . $tbl_register . "`
				WHERE
				`" . $tbl_register . "`.`eventid`=" . (int)$_GET['eventid'] . "
				AND
				`" . $tbl_users . "`.`userid`=`" . $tbl_register . "`.`userid`
				AND
				(
					INSTR(`" . $tbl_users . "`.`nickname`, '" . $find . "') > 0
					OR
					INSTR(`" . $tbl_users . "`.`prename`, '" . $find . "') > 0
					OR
					`" . $tbl_users . "`.`userid`=" . (int)$find . "
				)
		";
		
		$result = $db->query($sql);
		$uc = mysql_num_rows($result);
		
		$pages->setValues(@(int)$_GET['page'], $upp, $uc);
		
		$sql = "SELECT * FROM
				`" . $tbl_users . "`, `" . $tbl_register . "`
				WHERE
				`" . $tbl_register . "`.`eventid`=" . (int)$_GET['eventid'] . "
				AND
				`" . $tbl_users . "`.`userid`=`" . $tbl_register . "`.`userid`
				AND
				(
					INSTR(`" . $tbl_users . "`.`nickname`, '" . $find . "') > 0
					OR
					INSTR(`" . $tbl_users . "`.`prename`, '" . $find . "') > 0
					OR
					`" . $tbl_users . "`.`userid`=" . (int)$find . "
				)
				ORDER BY `" . $order . "` " . $dir . "
				LIMIT "  . ($pages->currentValue()) . ", " . (int)$upp . "
		";
		
		$result = $db->query($sql);
		
		while ($row = mysql_fetch_assoc($result))
			$users[] = $row;
		
		$sort['payed'] = makeURL($mod, array('eventid' => (int)$_GET['eventid'], 'page' => $page, 'order' => 'payed', 'dir' => $other_dir));
		$sort['appeared'] = makeURL($mod, array('eventid' => (int)$_GET['eventid'], 'page' => $page, 'order' => 'appeared', 'dir' => $other_dir));
		
		$e['registered'] = $db->num_rows($tbl_register, "`eventid`=" . (int)$_GET['eventid']);
		$e['payed'] = $db->num_rows($tbl_register, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed` > 0");
		$e['payed_pre'] = $db->num_rows($tbl_register, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed`!=0 AND `payed`!=2");
		$e['payed_box_office'] = $db->num_rows($tbl_register, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed`=2");
		$smarty->assign('event', $e);
	}
	
	// page management
	@$smarty->assign('pages', $pages->get($mod, array("eventid"=>(int)$_GET['eventid'])));
	
	// order by - urls
	@$sort['nickname'] = makeURL($mod, array('eventid' => (int)$_GET['eventid'], 'page' => $page, 'order' => 'nickname', 'dir' => $other_dir));
	@$sort['prename'] = makeURL($mod, array('eventid' => (int)$_GET['eventid'], 'page' => $page, 'order' => 'prename', 'dir' => $other_dir));
	@$sort['paystate'] = makeURL($mod, array('eventid' => (int)$_GET['eventid'], 'page' => $page, 'order' => 'payed', 'dir' => $other_dir));
	@$sort['appeared'] = makeURL($mod, array('eventid' => (int)$_GET['eventid'], 'page' => $page, 'order' => 'appeared', 'dir' => $other_dir));
	
	$smarty->assign('sort', $sort);
	
	if (@count($users) > 0)
		foreach ($users as $i => $u)
		{
			$users[$i]['url'] = makeURL('profile', array('userid' => $u['userid']));
			@$users[$i]['payed_str'] = $paystates[$u['payed']];
			if (@(int)$_GET['eventid'] > 0)
				$users[$i]['appeared_str'] = intToYesNo($u['appeared']);
			else
				$users[$i]['appeared_str'] = '-';
		}
	@$smarty->assign('users', $users);
	@$smarty->assign('eventid', (int)$_GET['eventid']);
	
?>