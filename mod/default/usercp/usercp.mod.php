<?php

	/**
	 * Project: Higher For Hire
	 * File: usercp.mod.php
	 *
	**/
	
	$disable_birthday = $config->get('login', 'disable-birthday') == '1';
	$smarty->assign('disable_birthday', $disable_birthday);
	
	// breadcrumbs
	$breadcrumbs->addElement($lang->get('usercp'), makeURL('usercp'));	
	
	// Check if user is logged in
	
	if ($login->currentUser() === false)
	{
		$notify->add('Login', $lang->get('not_logged_in'));
	}
	else
	{
	
		// Add submenus
		if ($config->get($mod, 'hide-overview') != '1')
			$menu->addSubElement($mod, $lang->get('overview'), 'overview');
		
		if ($config->get($mod, 'hide-personal') != '1')		
			$menu->addSubElement($mod, $lang->get('personal'), 'personal');
		
		if ($config->get($mod, 'hide-avatar') != '1')
			$menu->addSubElement($mod, $lang->get('avatar'), 'avatar');
		
		if ($config->get($mod, 'hide-comments') != '1')
			$menu->addSubElement($mod, $lang->get('my_comments'), 'comments');
		
		if ($config->get($mod, 'hide-changepw') != '1')
			$menu->addSubElement($mod, $lang->get('changepw'), 'changepw');
		
		if ($config->get($mod, 'hide-company') != '1')
			$menu->addSubElement($mod, $lang->get('company'), 'company');
		
		// store current user to $u
		$u = $login->currentUser();
		
		// set mode to overview if it's empty
		if ($_GET['mode'] == '') $_GET['mode'] = 'overview';
		$smarty->assign('mode', $_GET['mode']);
		
		// add current submenu to breadcrumbs
		$breadcrumbs->addElement($lang->get($_GET['mode']), makeURL($mod, array("mode" => $_GET['mode'])));
		
		switch ($_GET['mode']) {
			case "overview":
				
				$disable_editing = $config->get($mod, 'disable-editing');
				$smarty->assign('disable_editing', $disable_editing);
				
				if (isset($_POST['save']) && $disable_editing != '1') {
					if (!$disable_birthday) {
						@$birthday = mktime(0, 0, 0, $_POST['Date_Month'], $_POST['Date_Day'], $_POST['Date_Year']);
					}
					else {
						$birthday = time() - 60 * 60 * 24;
					}

					// $userid, $email, $nickname, $lastname, $prename, $comment = '', $birthday = 0
					$user->updateUser($login->currentUserID(), $u['email'], $u['nickname'],
									$_POST['lastname'], $_POST['prename'], $u['comment'],
									$birthday, $u['ban'], $u['activated']
									);
									
					$u['lastname'] = $_POST['lastname'];
					$u['prename'] = $_POST['prename'];
					$u['birthday'] = $birthday;
				}
				
				$smarty->assign('user', $u);
				$smarty->assign('avatar', $avatar->get($login->currentUserID()));
				$smarty->assign('path', $template_dir."/overview.tpl");
				
				break;
				
			case "personal":
				$smarty->assign('path', $template_dir."/personal.tpl");
				$tbl_fields = MYSQL_TABLE_PREFIX . 'personal_fields';
				$tbl_data = MYSQL_TABLE_PREFIX . 'personal_data';
				
				$list = $db->selectList($tbl_fields);
				
				if (isset($_POST['save']))
					$db->delete($tbl_data, '`userid`=' . $u['userid']);
				
				if (count($list) > 0) {
					$l=array();
					foreach ($list as $i => $v) {
						$where = "`userid`=" . $u['userid'] . " AND `fieldid`=" . $v['fieldid'];
						
						if (isset($_POST['save']) && $_POST['value_' . $v['fieldid']] != $v['value'])
							$db->insert($tbl_data, array('fieldid', 'userid', 'value'), array($v['fieldid'], $u['userid'], "'" . $_POST['value_' . $v['fieldid']] . "'"));

						$l[$i]['name'] = $v['value'];
						$l[$i]['fieldid'] = $v['fieldid'];
						$l[$i]['value'] = $db->selectOne($tbl_data, 'value', "`userid`=" . $u['userid'] . 
											" AND `fieldid`=" . $v['fieldid']);
					}
				
					$smarty->assign('list', $l);
				}
				break;
			
			case "avatar":
				$smarty->assign('path', $template_dir."/avatar.tpl");
				$smarty->assign('upload', $avatar->upload($login->currentUserID()));
				$smarty->assign('avatar', $avatar->get($login->currentUserID()));
				$smarty->assign('img_width', $config->get('core', 'img-width'));
				$smarty->assign('img_height', $config->get('core', 'img-height'));
				
				break;
			
			case "comments":
				if (isset($_POST['sbtFind']) && @trim($_POST['txtFind']) != '') {
					$list = $db->selectList('comments', '*', "`userid`=".$login->currentUserId()." AND INSTR(`text`, '".secureMySQL($_POST['txtFind'])."') > 0", "`timestamp` DESC", 20);
					$smarty->assign('find', $_POST['txtFind']);
				}
				else {
					$commentsPerPage = 20;
					$count = $db->num_rows('comments', "`userid`=".$login->currentUserId());
					@$pages->setValues((int)$_GET['page'], $commentsPerPage, $count);
					$list = $db->selectList('comments', '*', "`userid`=".$login->currentUserId(), "`timestamp` DESC", ($pages->currentValue()) . ", " . $commentsPerPage);
					if (null != $list && count($list) > 0) {
						foreach ($list as $i => $item) {
							$list[$i]['timestamp_str'] = timeElapsed($item['timestamp']);
						}
					}
					$smarty->assign('pages', $pages->get($mod, array('mode' => 'comments')));
				}
				$smarty->assign('comment_list', $list);
				$smarty->assign('path', $template_dir."/comments.tpl");
				break;
			case "changepw":
				$smarty->assign('path', $template_dir."/changepw.tpl");
				if (isset($_POST['save'])) {
					$u = $login->currentUser();
					
					if ($u['password'] == generatePasswordHash($_POST['password_old'])) {
						if ($_POST['password_new'] == $_POST['password_new_repeat']) {
							$user->changePassword($u['userid'], $_POST['password_new']);
							$_SESSION['password'] = generatePasswordHash($_POST['password_new']);
							
							$notify->add($lang->get('password'), $lang->get('password_changed_successfully'));
						}
						else {
							$notify->add($lang->get('password'), $lang->get('password_repeat_invalid'));
						}
					}
					else {
						$notify->add($lang->get('password'), $lang->get('password_wrong'));
					}
				}
				break;
				
			case "company":
				if ($config->get($mod, 'hide-company') != '1') {
					$smarty->assign('path', $template_dir."/company.tpl");
					$u = $login->currentUser();
					if (isset($_POST['save'])) {
						$db->update('users',
							"`company`='".secureMySQL($_POST['company'])."', `address`='".secureMySQL($_POST['address'])."'",
							"`userid`=".$u['userid']
						);
						$u = $db->selectOneRow('users', '`company`, `address`', '`userid`='.$u['userid']);
						$notify->add($lang->get('save'), $lang->get('changes_saved'));
					}
					$smarty->assign('usr', $u);
				}
				break;
		}
	}
	
?>