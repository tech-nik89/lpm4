<?php
	
	// add a breadcrumb
	$breadcrumbs->addElement($lang->get('users'), makeURL($mod, array('mode' => 'users')));
	
	// include the template
	$smarty->assign('path', $template_dir . '/users.tpl');
	
	
	switch ($action)
	{
		case 'deposit':
			
			$u = $user->getUserByID($_GET['userid']);
			$breadcrumbs->addElement($u['nickname'], makeURL($mod, array('mode' => 'users', 'action' => 'edit', 'userid' => $u['userid'])));
			$breadcrumbs->addElement($lang->get('options_deposit'), makeURL($mod, array('mode' => 'users', 'action' => 'deposit', 'userid' => $u['userid'])));
			
			if (isset($_POST['add']))
			{
				$amount = (int)$_POST['amount'];
				
				if ($amount > 0)
				{
					$credit->deposit($u['userid'], $amount, $lang->get('deposit_notify'));
					$notify->add($lang->get('credit'), $lang->get('amount_submitted'));
				} else {
					$notify->add($lang->get('credit'), $lang->get('amount_too_small'));
				}
			}
			
			break;
		
		case 'memberships':
			
			$u = $user->getUserByID($_GET['userid']);
			
			// add a breadcrumb
			$breadcrumbs->addElement($u['nickname'] . ' ' . $lang->get('options_memberships'), makeURL($mod, array('mode' => 'users', 'action' => 'memberships', 'userid' => $u['userid'])));
			
			// delete a group
			if (isset($_POST['delete']))
				$rights->removeUserFromGroup($_POST['groupid'], $_GET['userid']);
			
			// assign a group
			if (isset($_POST['add']))
				$rights->assignUserToGroup($_POST['group_new'], $_GET['userid']);
			
			// get groups of user
			$g = $rights->getGroups($_GET['userid']);
			$smarty->assign('groups', $g);
			
			// get all groups
			$g = $rights->getAllGroups();
			$smarty->assign('group_new', $g);
			
			break;
			
		case 'edit':
			
			// save edit
			if (isset($_POST['edit_submit']))
			{
				$u = $user->getUserByID($_GET['userid']);
				
				@$user->updateUser($_GET['userid'], 
					$_POST['email'],
					$_POST['nickname'],
					$_POST['lastname'],
					$_POST['prename'],
					$_POST['comment'],
					$u['birthday'],
					$_POST['ban'],
					$_POST['activated'],
					$_POST['company'],
					$_POST['address']
				);
				$db->update('users', "`template`='".secureMySQL($_POST['template'])."'", "`userid`=".$u['userid']);
			}
			// change password
			if (isset($_POST['password_submit'])) {
				$user->changePassword($_GET['userid'],$_POST['password']);
				$notify->add($lang->get('admin'), $lang->get('password_changed'));
			}
			
			// change personal details
			$tbl_fields = MYSQL_TABLE_PREFIX . 'personal_fields';
			$tbl_data = MYSQL_TABLE_PREFIX . 'personal_data';
			$uid = (int)$_GET['userid'];
			$list = $db->selectList($tbl_fields);
			
			if (isset($_POST['save']))
				$db->delete($tbl_data, '`userid`=' . $uid);
			
			if (count($list) > 0)
			{
				$l=array();
				foreach ($list as $i => $v)
				{
					
					$where = "`userid`=" . $uid . " AND `fieldid`=" . $v['fieldid'];
					
					if (isset($_POST['save']) && $_POST['value_' . $v['fieldid']] != $v['value'])
						$db->insert($tbl_data, array('fieldid', 'userid', 'value'), array($v['fieldid'], $uid, "'" . $_POST['value_' . $v['fieldid']] . "'"));

					
					$l[$i]['name'] = $v['value'];
					$l[$i]['fieldid'] = $v['fieldid'];
					$l[$i]['value'] = $db->selectOne($tbl_data, 'value', "`userid`=" . $uid . 
										" AND `fieldid`=" . $v['fieldid']);
				}
			
				$smarty->assign('list', $l);
			}
			
			// get user
			$u = $user->getUserByID($_GET['userid']);
			$smarty->assign('user', $u);
			
			
			// add a breadcrumb
			$breadcrumbs->addElement($u['nickname'], makeURL($mod, array('mode' => 'users', 'action' => 'edit', 'userid' => $u['userid'])));
			
			// list available templates
			$smarty->assign('tlist', listTemplates());
			
			break;
		
		case 'delete':
			
			if (isset($_POST['yes']))
			{
				
				$user->removeUser($_GET['userid']);
				$action = '';
				
			} else {
				
				// get user
				$u = $user->getUserByID($_GET['userid']);
				
				// delete user
				$smarty->assign('path', $template_dir . "/user_delete.tpl");
				
				$breadcrumbs->addElement($u['nickname'], makeURL($mod, array('mode' => 'users', 'action' => 'edit', 'userid' => $u['userid'])));
				$breadcrumbs->addElement($lang->get('options_delete'), makeURL($mod, array('mode' => 'users', 'action' => 'delete', 'userid' => $u['userid'])));

			}
			
		default:
			
			$find = '';
			if (isset($_POST['find']))
				$find = secureMySQL($_POST['find']);
			
			$dir = secureMySQL(isset($_GET['dir']) ? $_GET['dir'] : '');
			$order = secureMySQL(isset($_GET['order']) ? $_GET['order'] : '');
			
			if ($dir == 'ASC')
				$other_dir = 'DESC';
			else
				$other_dir = 'ASC';
				
			if ($order == '')
				$order = 'nickname';
			
			// usercount
			$uc = $user->count();
			$smarty->assign('usercount', $uc);
			
			// read users
			if ($find == '')
			{
				@$pages->setValues((int)$_GET['page'], $upp, $uc);
				$userlist = $user->listUsers($pages->currentValue(), $upp, $order . " " . $dir, $find);
			}
			else
			{
				$pages->setPages(1, 1);
				$userlist = $user->find($find);
			}
			
			// page management
			$smarty->assign('pages', $pages->get($mod, array('mode' => 'users', 'order' => $order, 'dir' => $dir)));
			
			// create options paths
			if ($userlist !== false)
			foreach ($userlist as $key => $val)
			{
				$userlist[$key]['url_show_profile'] = makeURL('profile', array('userid' => $val['userid']));
				$userlist[$key]['url_edit'] = makeURL($mod, array('mode' => 'users', 'action' => 'edit', 'userid' => $val['userid']));
				$userlist[$key]['url_delete'] = makeURL($mod, array('mode' => 'users', 'action' => 'delete', 'userid' => $val['userid']));
				$userlist[$key]['url_memberships'] = makeURL($mod, array('mode' => 'users', 'action' => 'memberships', 'userid' => $val['userid']));
				$userlist[$key]['url_deposit'] = makeURL($mod, array('mode' => 'users', 'action' => 'deposit', 'userid' => $val['userid']));
			}
			
			$smarty->assign('users', $userlist);
			
			// order urls
			$smarty->assign('order_nickname', makeURL($mod, array('mode' => 'users', 'order' => 'nickname', 'dir' => $other_dir)));
			$smarty->assign('order_prename', makeURL($mod, array('mode' => 'users', 'order' => 'prename', 'dir' => $other_dir)));
			$smarty->assign('order_lastname', makeURL($mod, array('mode' => 'users', 'order' => 'lastname', 'dir' => $other_dir)));
			$smarty->assign('order_email', makeURL($mod, array('mode' => 'users', 'order' => 'email', 'dir' => $other_dir)));
			
			$smarty->assign('find', $find);
			
			break;
	}
	
?>