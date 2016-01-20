<?php
	// assign additional language file and default template
	$lang->addModSpecificLocalization($mod);
		
	// check for login
	if ($login->currentUser() !== false)
	{
	
		// make table strings
		$tbl_b = MYSQL_TABLE_PREFIX . 'buddylist';
		$tbl_u = MYSQL_TABLE_PREFIX . 'users';
		
		// Add submenu for buddy requests
		$menu->addSubElement($mod, $lang->get('buddy_requests'), 'requests');
		
		switch ($_GET['mode'])
		{
			case 'requests':
				
				if (isset($_POST['accept']))
				{
					$db->update($tbl_b, "`accepted`=1", 
						"`userid`=".(int)$_POST['userid']." AND `buddyid`=".$login->currentUserID());
					$notify->add($lang->get('buddylist'), $lang->get('buddy_added'));
				}
				
				if (isset($_POST['discard']))
				{
					$db->delete($tbl_b, "(`userid`=".(int)$_POST['userid']." AND `buddyid`=".$login->currentUserID() . ")");
					$notify->add($lang->get('buddylist'), $lang->get('buddy_denied'));
				}
				
				$requests = $db->selectList($tbl_b . "`, `" . $tbl_u, "*", 
							"`".$tbl_b."`.`buddyid`=".$login->currentUserID() . " 
							AND `".$tbl_b."`.`userid` = `".$tbl_u."`.`userid` AND `".$tbl_b."`.`accepted`=0");
							
				if (count($requests) > 0)
					foreach ($requests as $index => $buddy)
						$requests[$index]['url'] = makeURL('profile', array('userid' => $buddy['userid']));
				
				
				$smarty->assign('requests', $requests);
				$smarty->assign('path', $template_dir."/requests.tpl");
				break;
				
			default:
				// delete buddy
				if (isset($_POST['delete']))
				{
					$db->delete($tbl_b, "(`userid`=".$login->currentUserID()." AND `buddyid`=".(int)$_POST['userid'].")
										OR (`userid`=".(int)$_POST['userid']." AND `buddyid`=".$login->currentUserID() . ")");
					$notify->add($lang->get('buddylist'), $lang->get('buddy_deleted'));
				}
				
				// list existing buddies
				$buddies = $db->selectList($tbl_b . "`, `" . $tbl_u, "*", 
							"((
								`".$tbl_b."`.`userid`=".$login->currentUserID() . " 
								AND `".$tbl_b."`.`buddyid` = `".$tbl_u."`.`userid`
							)
							OR (
								`".$tbl_b."`.`buddyid`=".$login->currentUserID() . " 
								AND `".$tbl_b."`.`userid` = `".$tbl_u."`.`userid`
							)
							) AND `".$tbl_b."`.`accepted`=1");
				
				$dur = (int)$config->get('core', 'stat-duration');
				
				if (count($buddies) > 0)
					foreach ($buddies as $index => $buddy) {
						if ($buddy['lastaction'] + $dur > time())
							$buddies[$index]['online'] = true;
						else
							$buddies[$index]['online'] = false;
						$buddies[$index]['url'] = makeURL('profile', array('userid' => $buddy['userid']));
					}
				$smarty->assign('buddies', $buddies);
				$smarty->assign('path', $template_dir."/default.tpl");
		}
		
	} else {
		$notify->add($lang->get('buddylist'), $lang->get('please_login'));
	}
?>