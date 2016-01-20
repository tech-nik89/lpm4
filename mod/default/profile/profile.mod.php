<?php
	
	// check if user is selected
	$userid = (int)$_GET['userid'];
	
	
	// include language file
	$lang->addModSpecificLocalization($mod);
	
	// get user
	$u = $user->getUserByID($_GET['userid']);
	
	// check if user exists
	if ($u !== false)
	{
		// set breadcrumb
		$breadcrumbs->addElement($u['nickname'], makeURL($mod, array('userid' => $u['userid'])));
		
		// set my userid
		$smarty->assign('myid', $login->currentUserId());
	
		// include the template
		$smarty->assign('path', $template_dir . '/profile.tpl');
		
		if ($config->get('profile', 'hide-lastname') == '1')
			$u['lastname'] = substr($u['lastname'], 0, 1) . '.';
		
		$smarty->assign('user', $u);
		
		// avatar
		$smarty->assign('avatar', $avatar->get($_GET['userid']));
		
		// last seen
		$smarty->assign('last_seen', timeElapsed($u['lastaction']));
		
		// clan
		if ($this->isInstalled('clan')) {
			$clanid = (int)$db->selectOne('clan_member', 'clanid', "`userid`=".$userid);
			if ($clanid > 0) {
				$clan = $db->selectOneRow('clan', '*', '`clanid`='.$clanid);
				$clan['url'] = makeURL('clan', array('clanid' => $clanid));
				$smarty->assign('clan', $clan);
			}
		}
		
		// get personal fields
		$tbl_fields = MYSQL_TABLE_PREFIX . 'personal_fields';
		$tbl_data = MYSQL_TABLE_PREFIX . 'personal_data';
		
		$list = $db->selectList($tbl_fields);
		
		$l = array();
		if (count($list) > 0)
		foreach ($list as $i => $v)
		{
			
			$where = "`userid`=" . (int)$u['userid'] . " AND `fieldid`=" . $v['fieldid'];
			
			$l[$i]['name'] = $v['value'];
			$l[$i]['fieldid'] = $v['fieldid'];
			$l[$i]['value'] = $db->selectOne($tbl_data, 'value', "`userid`=" . $u['userid'] . 
								" AND `fieldid`=" . $v['fieldid']);
		}
		
		$smarty->assign('personal', $l);
		
		$smarty->assign('pm_url', makeURL('pmbox', array('mode' => 'write', 'userid' => (int)$_GET['userid'])));
		
		// events where user is registered
		$tbl_events = MYSQL_TABLE_PREFIX . 'events';
		$tbl_reg = MYSQL_TABLE_PREFIX . 'register';
		$events = array();
		
		if ($this->isInstalled('events')) {
			$events = $db->selectList($tbl_events . "`, `" . $tbl_reg, "*", "`".$tbl_reg."`.`userid`=".(int)$_GET['userid']." AND `".$tbl_reg."`.`eventid`=`".$tbl_events."`.`eventid`");
			$smarty->assign('event_count', count($events));
		}
		
		$tbl_room = "`".MYSQL_TABLE_PREFIX."room`";
		$tbl_room_item = "`".MYSQL_TABLE_PREFIX."room_item`";
		
		$show_seats = true;
		if (!$db->tableExists(MYSQL_TABLE_PREFIX."room") || !$db->tableExists(MYSQL_TABLE_PREFIX."room_item"))
			$show_seats = false;
		
		if (@count($events)>0)
		foreach ($events as $i => $v)
		{
			$events[$i]['url'] = makeURL('events', array('eventid' => $v['eventid']));
			$events[$i]['date'] = date("d.m.Y", $v['start']);
			
			if ($show_seats) {
				$sql = "
					SELECT x, y, $tbl_room.`roomid` FROM $tbl_room, $tbl_room_item
					WHERE $tbl_room.`eventid`=".$v['eventid']."
					AND $tbl_room_item.`roomid`=$tbl_room.`roomid`
					AND `value`=".$userid."
					AND `type`=13";
				$result = $db->query($sql);
				if (mysql_num_rows($result) == 0) {
					$events[$i]['seat'] = '-';
				}
				else {
					$row = mysql_fetch_assoc($result);
					$events[$i]['seat'] = strtoupper(chr($row['y']+97)) . ($row['x'] + 1);
					$events[$i]['seat_url'] = makeURL('room', array('roomid' => $row['roomid']));
				}
			}
			else {
				$events[$i]['seat'] = '-';
			}
		}
		$smarty->assign('events', $events);
			
		
		// buddylist
		if ($db->num_rows('menu', "`mod`='buddylist' AND `assigned_groupid`!=-1") > 0 || $db->num_rows('boxes', "`file`='buddylist' AND `visible`=1") > 0) {
			$smarty->assign('buddylist_enabled', true);
			
			if ($login->currentUser() !== false)
				$smarty->assign('logged_in', true);
			
			$tbl_b = MYSQL_TABLE_PREFIX . 'buddylist';
			
			if (isset($_POST['buddy_request']))
			{
				$db->insert($tbl_b, array('userid', 'buddyid'), array($login->currentUserID(), $u['userid']));
				$notify->add($lang->get('buddies'), $lang->get('request_sent'));
			}
			
			if ($db->num_rows($tbl_b, " (`userid`=".$login->currentUserID()." AND `buddyid`=".$u['userid'] . ")
										OR (`buddyid`=".$login->currentUserID()." AND `userid`=".$u['userid'] . ")") > 0)
				$smarty->assign('is_buddy', true);
			else
				$smarty->assign('is_buddy', false);
		}
		
		// User Groups
		$usergroups = $rights->getGroups($u['userid'], 1);
		$smarty->assign('usergroups', $usergroups);
	}
	else
	{
		// include the template
		$smarty->assign('path', $template_dir . '/notfound.tpl');
	}
	
	$smarty->assign('edit_personal_url', makeURL('usercp', array('mode' => 'personal')));
?>