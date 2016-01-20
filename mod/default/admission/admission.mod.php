<?php
	
	$paystates = array(	0 => $lang->get('not_payed'),
					1 => $lang->get('payed_pre'),
					2 => $lang->get('payed_box_office'),
					3 => $lang->get('pay_reserv'),
					4 => $lang->get('pay_wait'),
					5 => $lang->get('pay_assistant'),
					6 => $lang->get('pay_vip'),
					7 => $lang->get('pay_press'),
					8 => $lang->get('pay_bonus')
				);
	
	$lang->addModSpecificLocalization($mod);
	$breadcrumbs->addElement($lang->get('admission'), makeURL($mod));
	
	$table = MYSQL_TABLE_PREFIX . 'events';
	$tbl_reg = MYSQL_TABLE_PREFIX . 'register';
	$tbl_users = MYSQL_TABLE_PREFIX . 'users';
	
	if ($rights->isAllowed('admin', 'users'))
	{
		$menu->addSubElement($mod, $lang->get('user_add'), 'newuser');
		$right['users'] = true;
	}
	
	if ($rights->isAllowed('events', 'manage'))
	{
		$ev = $db->selectList($table, "*");
		if (count($ev)>0)
			foreach($ev as $v)
				$menu->addSubElement($mod, $v['name'] . ' ' . $lang->get('event_paystate'), 'paystate', array('eventid' => $v['eventid']));
		
		$right['paystate'] = true;
	}
	
	$mode = $_GET['mode'];
	
	switch ($mode)
	{
		default:
			$smarty->assign('path', $template_dir."/default.tpl");
			break;
			
		case 'paystate':
			if (!$right['paystate'])
					break;
				
			$e = $db->selectOneRow($table, "*", "`eventid`=" . (int)$_GET['eventid']);
			$breadcrumbs->addElement($lang->get('event_paystate'), makeURL($mod, array('mode' => 'paystate', 'eventid' => $e['eventid'])));
			$breadcrumbs->addElement($e['name'], makeURL($mod, array('mode' => 'paystate', 'eventid' => $e['eventid'])));	
			
			if (isset($_POST['search']) and trim($_POST['search_string']) != '')
			{
				if (isset($_POST['save']))
					@$db->update($tbl_reg, "`payed`=" . (int)$_POST['paystate'] . ", `appeared`=" . (int)$_POST['appeared'], "`eventid`=" . (int)$_GET['eventid'] . " AND `userid`=" . (int)$_POST['userid'] );
				
				$smarty->assign('search_string', $_POST['search_string']);
				$result = $db->query("SELECT * FROM `" . $tbl_reg . "`, `" . $tbl_users . "`
								WHERE `" . $tbl_reg . "`.`userid` = `" . $tbl_users . "`.`userid`
								AND `" . $tbl_reg . "`.`eventid` = " . (int)$_GET['eventid'] . "
								AND 
								(INSTR(`" .$tbl_users . "`.`nickname`, '" . secureMySQL($_POST['search_string']) . "') > 0
								OR INSTR(`" .$tbl_users . "`.`prename`, '" . secureMySQL($_POST['search_string']) . "') > 0
								OR INSTR(`" .$tbl_users . "`.`lastname`, '" . secureMySQL($_POST['search_string']) . "') > 0)");
				
				$l = array();
				while ($row = mysql_fetch_assoc($result))
					$l[] = $row;
				
				$smarty->assign('list', $l);
				
			}
			
			$e['registered'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid']);
			$e['payed'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed` > 0");
			$e['payed_pre'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed`!=0 AND `payed`!=2");
			$e['payed_box_office'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed`=2");
			
			$smarty->assign('event', $e);
			$smarty->assign('paystates', $paystates);
			$smarty->assign('path', $template_dir . "/paystate.tpl");
			
			break;
		
		case 'newuser':
		
			if ($right['users'])
			{
				if (isset($_POST['create']))
				{
					$birthday = mktime(0, 0, 0 , $_POST['Date_Month'], $_POST['Date_Day'], $_POST['Date_Year']);
					
					if (
					$user->createUser($_POST['email'],$_POST['password'], $_POST['nickname'], 
												$_POST['lastname'], $_POST['prename'], $birthday) == 0)
					{
						if ((int)$_POST['eventid'] > 0)
							$db->insert($tbl_reg, array("userid", "eventid"), array(mysql_insert_id(),(int)$_POST['eventid']));
						$notify->add($lang->get('users'), $lang->get('user_add_done'));
					}
					else
					{
						$notify->add($lang->get('users'), $lang->get('user_add_failed'));
						break;
					}
				} else {
				
					// add a breadcrumb
					$breadcrumbs->addElement($lang->get('user_add'), makeURL($mod, array('mode' => 'newuser')));
					
					// assign template file
					$smarty->assign('path', $template_dir . "/newuser.tpl");
					
					// generate random password
					$smarty->assign('password', randomPassword());
					
					// list events
					$smarty->assign('events', $ev);
					
					break;
				}
			}
		
	}

?>