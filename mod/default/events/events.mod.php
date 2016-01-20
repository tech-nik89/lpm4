<?php
	
	$table = MYSQL_TABLE_PREFIX . 'events';
	$tbl_reg = MYSQL_TABLE_PREFIX . 'register';
	$tbl_users = MYSQL_TABLE_PREFIX . 'users';
	
	$paystates = array(	0 => $lang->get('not_payed'),
					1 => $lang->get('payed_pre'),
					2 => $lang->get('payed_box_office')
				);
	
	$breadcrumbs->addElement($lang->get('events'), makeURL($mod, array()));
	
	$min_age_list = array("16" => "16", "18" => "18");
	
	$bar_width = (int)$config->get($mod, 'bar-width');
	$smarty->assign('bar_width', $bar_width);
	
	// check if user is allowed to manage this modul
	if ($rights->isAllowed($mod, 'manage'))
	{
		$isallowed = true;
		$menu->addSubElement($mod, $lang->get('add'), 'add');
	} else
		$isallowed = false;
		
	$smarty->assign('isallowed', $isallowed);
	
	switch ($_GET['mode'])
	{
		
		case 'reg':
			
			if (!$isallowed)
				break;
				
			$event = $db->selectOneRow('events', "*", "`eventid`=".(int)$_GET['eventid']);
			$smarty->assign('path', $template_dir."/reg.tpl");
			$smarty->assign('event', $event);
			
			$breadcrumbs->addElement($event['name'], makeURL($mod, array('eventid' => $event['eventid'])));
			$breadcrumbs->addElement($lang->get('event_regs'), makeURL($mod, array('eventid' => $event['eventid'], 'mode' => 'reg')));
			
			break;
		
		case 'agb':
			
			$smarty->assign('path', $template_dir . "/agb.tpl");
			$e = $db->selectOneRow($table, "*", "`eventid`=" . (int)$_GET['eventid']);
			
			$breadcrumbs->addElement($e['name'], makeURL($mod, array('eventid' => $e['eventid'])));	
			$breadcrumbs->addElement($lang->get('agb'), makeURL($mod, array('mode' => 'agb', 'eventid' => $e['eventid'])));
			
			$agb = $e['agb'];
			$agb = stripslashes($agb);
			$agb = str_replace("\n", "<br />\n", $agb);
			
			$smarty->assign('agb', $agb);
			
			break;
		
		case 'remove':
			
			if ($_GET['mode'] == 'remove')
			{
				if ($isallowed !== true)
					break;
					
				if (isset($_POST['yes']))
				{
					// delete
					
					$log->add($mod, 'event ' . $_GET['eventid'] . ' removed');
					
					$db->delete($table, "`eventid`=" . (int)$_GET['eventid']);
					$db->delete($tbl_reg, "`eventid`=" . (int)$_GET['eventid']);
					$_GET['eventid'] = 0;
				}
				else
				{
					
					if (!isset($_POST['no']))
					{
						$e = $db->selectOneRow($table, "*", "`eventid`=" . (int)$_GET['eventid']);
						
						$breadcrumbs->addElement($e['name'], makeURL($mod, array('eventid' => $e['eventid'])));	
						$breadcrumbs->addElement($lang->get('remove'), makeURL($mod, array('mode' => 'remove', 'eventid' => $e['eventid'])));
						
						$smarty->assign('path', $template_dir."/remove.tpl");
						break;
					}
				}
			}
		
		case 'edit':
			
			if ($_GET['mode'] == 'edit')
			{
			
				if ($isallowed !== true)
					break;
					
				if (isset($_POST['save']) && trim($_POST['name'] != ''))
				{
					// save
					
					$start = mktime($_POST['Start_Hour'], $_POST['Start_Minute'], 0, $_POST['Start_Month'], $_POST['Start_Day'], $_POST['Start_Year']);
					$end = mktime($_POST['End_Hour'], $_POST['End_Minute'], 0, $_POST['End_Month'], $_POST['End_Day'], $_POST['End_Year']);
					
					$start_reg = mktime($_POST['Start_Reg_Hour'], $_POST['Start_Reg_Minute'], 0, $_POST['Start_Reg_Month'], $_POST['Start_Reg_Day'], $_POST['Start_Reg_Year']);
					$end_reg = mktime($_POST['End_Reg_Hour'], $_POST['End_Reg_Minute'], 0, $_POST['End_Reg_Month'], $_POST['End_Reg_Day'], $_POST['End_Reg_Year']);
					
					if (isset($_POST['free']) && (int)$_POST['free'] == 1) $free = 0; else $free = 1;
					
					$db->update($table,
								"`name`='" . secureMySQL($_POST['name']) . "',
								`description`='" . secureMySQL($_POST['description']) . "', 
								`start`=" . $start . ",
								`end`=" . $end . ",
								`reg_start`=" . $start_reg . ", 
								`reg_end`=" . $end_reg . ", 
								`min_age`=" . (int)$_POST['min_age'] . ",
								`agb`='" . secureMySQL(strip_tags($_POST['agb'])) . "',
								`seats`=" . (int)$_POST['seats'] . ",
								`free`=" . $free . ",
								`login_active`=" . (int)$_POST['login_active'] . ",
								`credits`=" . (int)$_POST['credits'],
								
								"`eventid`=" . (int)$_GET['eventid']);
					
					$log->add($mod, 'event ' . $_GET['eventid'] . ' updated');
					
				
				}
				else
				{
					// read event from db
					$e = $db->selectOneRow($table, "*", "`eventid`=" . (int)$_GET['eventid']);
				
					$breadcrumbs->addElement($e['name'], makeURL($mod, array('eventid' => $e['eventid'])));	
					$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('mode' => 'edit', 'eventid' => $e['eventid'])));
					
					$smarty->assign('event', $e);
					$smarty->assign('path', $template_dir."/edit.tpl");
					$smarty->assign('min_age', $min_age_list);
					
					break;
				}
			}
			
		case 'add':
			
			if ($_GET['mode'] == 'add')
			{
			
				if ($isallowed !== true)
					break;
				
				if (isset($_POST['add']) && trim($_POST['name'] != ''))
				{
					if ((int)$_POST['free'] == 0) $free = 1; else $free = 0;
					
					@$db->insert($table, 
								array('eventid', 'name', 'description', 'start', 'end', 'reg_start', 'reg_end', 
									'min_age', 'agb', 'login_active', 'seats', 'free', 'credits'),
								
								array('NULL', "'".$_POST['name']."'", "'".$_POST['description']."'", 
										mktime($_POST['Start_Hour'], $_POST['Start_Minute'], 0, $_POST['Start_Month'], $_POST['Start_Day'], $_POST['Start_Year']),
										mktime($_POST['End_Hour'], $_POST['End_Minute'], 0, $_POST['End_Month'], $_POST['End_Day'], $_POST['End_Year']),
										mktime($_POST['Start_Reg_Hour'], $_POST['Start_Reg_Minute'], 0, $_POST['Start_Reg_Month'], $_POST['Start_Reg_Day'], $_POST['Start_Reg_Year']),
										mktime($_POST['End_Reg_Hour'], $_POST['End_Reg_Minute'], 0, $_POST['End_Reg_Month'], $_POST['End_Reg_Day'], $_POST['End_Reg_Year']),
										(int)$_POST['min_age'], "'".secureMySQL(strip_tags($_POST['agb']))."'",
										(int)$_POST['login_active'], (int)$_POST['seats'], $free, (int)$_POST['credits']
										));
					
					$log->add($mod, 'event ' . mysql_insert_id() . ' created');
					
				}
				else
				{
					
					$breadcrumbs->addElement($lang->get('add'), makeURL($mod, array('mode' => 'add')));	
					
					$smarty->assign('path', $template_dir."/add.tpl");
					$smarty->assign('min_age', $min_age_list);
					
					break;
				}
				
			}
			
			
		default:
	
	
			if (@(int)$_GET['eventid'] == 0)
			{
				$smarty->assign('path', $template_dir . "/overview.tpl");
				
				$list = $db->selectList($table, "*");
				
				if (count($list)>0)
					foreach ($list as $i => $v)
					{
						$list[$i]['start'] = date('d.m.Y / H:i', $v['start']);
						$list[$i]['end'] = date('d.m.Y / H:i', $v['end']);
						
						if ($list[$i]['last_check'] == 0)
							$list[$i]['last_check'] = $lang->get('never');
						else
							$list[$i]['last_check'] = timeElapsed($list[$i]['last_check']); 
						
						$list[$i]['url'] = makeURL($mod, array('eventid' => $v['eventid']));
						
						
						// Paystate bar
						$list[$i]['registered'] = $db->num_rows($tbl_reg, "`eventid`=" . $v['eventid']);
		
						$list[$i]['payed'] = $db->num_rows($tbl_reg, "`eventid`=" . $v['eventid'] . " AND `payed` > 0");
						$list[$i]['payed_pre'] = $db->num_rows($tbl_reg, "`eventid`=" . $v['eventid'] . " AND `payed`!=0 AND `payed`!=2");
						$list[$i]['payed_box_office'] = $db->num_rows($tbl_reg, "`eventid`=" . $v['eventid'] . " AND `payed`=2");
						
						$list[$i]['seats_free'] = $list[$i]['seats'] - $list[$i]['registered'];
						
						if ($list[$i]['free'] == 0)
							$list[$i]['not_payed'] = $list[$i]['registered'] - $list[$i]['payed'];
						
						if ($v['free'] == 0)
						{
							$list[$i]['payed_width'] = ((100 * $list[$i]['payed']) / $list[$i]['seats']) * ($bar_width / 100);
							$list[$i]['not_payed_width'] = ((100 * ($list[$i]['not_payed'])) / $list[$i]['seats']) * ($bar_width / 100);
							$list[$i]['free_width'] = ((100 * ($list[$i]['seats_free'])) / $list[$i]['seats']) * ($bar_width / 100);
						} else {
							$list[$i]['payed_width'] = ((100 * $list[$i]['registered']) / $list[$i]['seats']) * ($bar_width / 100);
							$list[$i]['not_payed_width'] = 0;
							$list[$i]['free_width'] = ((100 * ($list[$i]['seats_free'])) / $list[$i]['seats']) * ($bar_width / 100);
						}
						
					}
				
				$smarty->assign('bar_tpl', $template_dir . "/bar.tpl");
				$smarty->assign('list', $list);
				
			}
			else
			{
				$e = $db->selectOneRow($table, "*", "`eventid`=" . (int)$_GET['eventid']);
				
				if ($isallowed) {
					$menu->addSubElement($mod, $lang->get('event_regs'), 'reg', array('eventid' => (int)$_GET['eventid']));
				}
				
				if (isset($_POST['unregister_all']) && $isallowed)
				{
					// Remove all guests from guestlist
					$db->delete($tbl_reg, "`eventid`=" . (int)$_GET['eventid']);
					$notify->add($lang->get('events'), $lang->get('event_unregister_all_done'));
					$log->add($mod, 'unregistered all users from event ' . $_GET['eventid']);
				}
				
				if (isset($_POST['last_check']) && $isallowed)
				{
					$db->update($table, "`last_check`=".time(),"`eventid`=".(int)$_GET['eventid']);
					$notify->add($lang->get('events'), $lang->get('event_last_check_now_done'));
					$log->add($mod, 'updated last check of event ' . $_GET['eventid']);
				}
				
				// is user registered?
				$reg = $db->num_rows($tbl_reg, "`userid`=".$login->currentUserID()." 
												AND `eventid`=".(int)$_GET['eventid']);
				
				// do or undo reg
				if (isset($_POST['register']))
				{
					
					// Credits
					require_once("./mod/default/tournament/credit.class.php");
					global $tCredit;
					$tCredit = new TournamentCredit();
					
					if ($reg == 1)
					{

						$db->delete($tbl_reg, "`userid`=".$login->currentUserID()." AND `eventid`=".(int)$_GET['eventid']);
						$reg = 0;
						$notify->add($lang->get('events'), $lang->get('event_undo_success'));
						$log->add($mod, 'unregistered to event ' . $_GET['eventid']);
						$tCredit->decrement($login->currentUserId(), $e['eventid'], $e['credits']);
						
					} else {
						
						if (@(int)$_POST['agb'] == 1)
						{
							
							$u = $login->currentUser();
							$age = hasAge($u['birthday']);
							
							if (@$age >= $e['min_age'])
							{
							
								$db->insert($tbl_reg, array("userid", "eventid"), array($login->currentUserID(),(int)$_GET['eventid']));
								$reg = 1;
								@$notify->add($lang->get('events'), str_replace("%v", $e['name'], $lang->get('event_do_success')));
								$log->add($mod, 'registered to event ' . $_GET['eventid']);
								$tCredit->increment($login->currentUserId(), $e['eventid'], $e['credits']);
								
							} else
								$notify->add($lang->get('events'), $lang->get('event_do_min_age'));
								
						} else {
							$notify->add($lang->get('events'), $lang->get('agb_not_accepted'));
						}
					}
				}
				
				$e = $db->selectOneRow($table, "*", "`eventid`=" . (int)$_GET['eventid']);
				$breadcrumbs->addElement($e['name'], makeURL($mod, array('eventid' => $e['eventid'])));
				
				if ($login->currentUser() === false)
					$e['login_active'] = 0;
				
				if ($e['login_active'] == 1)
					if (time() < $e['reg_start'] || time() > $e['reg_end'])
						$e['login_active'] = 0;
				
				$e['start'] = date('d.m.Y / H:i', $e['start']);
				$e['end'] = date('d.m.Y / H:i', $e['end']);
				
				$e['reg_start'] = date('d.m.Y / H:i', $e['reg_start']);
				$e['reg_end'] = date('d.m.Y / H:i', $e['reg_end']);
				
				$e['registered'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid']);

				$e['payed'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed` > 0");
				$e['payed_pre'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed`=1");
				$e['payed_box_office'] = $db->num_rows($tbl_reg, "`eventid`=" . (int)$_GET['eventid'] . " AND `payed`=2");
				
				$e['seats_free'] = $e['seats'] - $e['registered'];
				
				if ($e['free'] == 0)
					$e['not_payed'] = $e['registered'] - $e['payed'];
				
				if ($e['last_check'] == 0)
					$e['last_check'] = $lang->get('never');
				else
					$e['last_check'] = timeElapsed($e['last_check']); 
				
				$smarty->assign('path', $template_dir . "/view.tpl");
				$smarty->assign('event', $e);
				
				// edit and remove urls
				$smarty->assign('edit_url', makeURL($mod, array('mode' => 'edit', 'eventid' => $e['eventid'])));
				$smarty->assign('remove_url', makeURL($mod, array('mode' => 'remove', 'eventid' => $e['eventid'])));
				$smarty->assign('paystate_url', makeURL($mod, array('mode' => 'paystate', 'eventid' => $e['eventid'])));
				$smarty->assign('agb_url', makeURL($mod, array('mode' => 'agb', 'eventid' => $e['eventid'])));
				
				// Paystate bar
				if ($e['free'] == 0)
				{
					$smarty->assign('payed_width', ((100 * $e['payed']) / $e['seats']) * ($bar_width / 100));
					$smarty->assign('not_payed_width', ((100 * ($e['not_payed'])) / $e['seats']) * ($bar_width / 100));
					$smarty->assign('free_width', ((100 * ($e['seats_free'])) / $e['seats']) * ($bar_width / 100));
				} else {
					$smarty->assign('payed_width', ((100 * $e['registered']) / $e['seats']) * ($bar_width / 100));
					$smarty->assign('not_payed_width', 0);
					$smarty->assign('free_width', ((100 * ($e['seats_free'])) / $e['seats']) * ($bar_width / 100));
				}
				
				$smarty->assign('bar_tpl', $template_dir . "/bar.tpl");
				
				$smarty->assign('reg', $reg);
				if ($reg == 1)
				{
					$smarty->assign('state', $lang->get('event_registered'));
					$smarty->assign('register', $lang->get('event_undo_reg'));
				} else {
					$smarty->assign('state', $lang->get('event_not_registered'));
					$smarty->assign('register', $lang->get('event_do_reg'));
				}
			}
		
	}
?>