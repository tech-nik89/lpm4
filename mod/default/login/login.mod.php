<?php
	
	$lang->addModSpecificLocalization($mod);
	
	$breadcrumbs->addElement($lang->get('login'), makeURL($mod));
	
	if (isset($_POST['login']))
		$_GET['mode'] = '';
		
	$hide_submenu = false;
	
	$disable_register = $config->get('login', 'register-disable') == 1;
	$smarty->assign('disable_register', $disable_register);
	
	// Show the register page
	if ($_GET['mode'] == 'register'){
		$breadcrumbs->addElement($lang->get('register'), makeURL($mod, array('mode' => 'register')));
		
		$disable_nickname = $config->get('login', 'disable-nickname') == '1';
		$smarty->assign('disable_nickname', $disable_nickname);
		
		$disable_birthday = $config->get('login', 'disable-birthday') == '1';
		$smarty->assign('disable_birthday', $disable_birthday);
		
		if ($disable_register) {
			$notify->add($lang->get('login'), $lang->get('register_disabled'));
		}
		else {
			$disable_second_email = $config->get('login', 'disable-second-email') == '1';
			$smarty->assign('disable_second_email', $disable_second_email);
			
			// Register button has been pressed
			if (isset($_POST['create'])) {
				// check for bot
				if ($_POST['mail'] == '' & (time() > ($_POST['ts'] + 5))) {
					// check if everything is valid ------------------------ //
					$everything_valid = true;
					
					if (!checkMail($_POST['email'])) {
						$smarty->assign('email_notify', $lang->get('email_invalid'));
						$everything_valid = false;
					}
					
					// email repeat
					if (!$disable_second_email) {
						if ($_POST['email'] != $_POST['email_repeat']) {
							$smarty->assign('email_repeat_notify', $lang->get('email_repeat_invalid'));
							$everything_valid = false;
						}
					}
					
					// nickname
					if (!$disable_nickname) {
						if (trim($_POST['nickname']) == '') {
							$smarty->assign('nickname_notify', $lang->get('nickname_invalid'));
							$everything_valid = false;
						}
					}
					else {
						$_POST['nickname'] = $_POST['prename'] . ' ' . $_POST['lastname'];
					}
					
					// password
					if (trim($_POST['password']) == '') {
						$smarty->assign('password_notify', $lang->get('password_invalid'));
						$everything_valid = false;
					}
					
					// password repeat
					if ($_POST['password'] != $_POST['password_repeat']) {
						$smarty->assign('password_repeat_notify', $lang->get('password_repeat_invalid'));
						$everything_valid = false;
					}
					
					// prename
					if (trim($_POST['prename']) == '') {
						$smarty->assign('prename_notify', $lang->get('prename_invalid'));
						$everything_valid = false;
					}
					
					// lastname
					if (trim($_POST['lastname']) == '') {
						$smarty->assign('lastname_notify', $lang->get('lastname_invalid'));
						$everything_valid = false;
					}
					
					// ------------------------------------------------------------------ //
					
					@$birthday = mktime(0, 0, 0, $_POST['Date_Month'], $_POST['Date_Day'], $_POST['Date_Year']);
					
					// everything valid, create the user
					if ($everything_valid) {
						$result = $user->createUser($_POST['email'], $_POST['password'], $_POST['nickname'], 
												$_POST['lastname'], $_POST['prename'], $birthday);
						if ($result == 0){
							$notify->add($lang->get('register'), $lang->get('register_success'));
							$addr = trim($config->get('login', 'register-notification-mail-address'));
							if ($addr != '') {
								$text = '<p><strong>'.$lang->get('register_mail_notification_descr').'</strong></p>';
								$text .= '<p>'.$lang->get('email').': '.strip_tags($_POST['email']).'<br />';
								$text .= $lang->get('nickname').': '.strip_tags($_POST['nickname']).'<br />';
								$text .= $lang->get('prename').': '.strip_tags($_POST['prename']).'<br />';
								$text .= $lang->get('lastname').': '.strip_tags($_POST['lastname']).'<br />';
								$text .= '<a href="'.getSelfURL().'/'.makeURL('profile', array('userid' => mysql_insert_id())).'">'.$lang->get('view_profile').'</a></p>';
								$eMail->send($lang->get('register_mail_notification_subject'), $text, $addr);
							}
						}
						else {
							$notify->add($lang->get('register'), $lang->get('register_failed') . ' ('.$result.')');
						}
						$_GET['mode'] = '';
						$hide_submenu = true;
					} else {
						$smarty->assign('email', $_POST['email']);
						@$smarty->assign('email_repeat', $_POST['email_repeat']);
						$smarty->assign('nickname', $_POST['nickname']);
						$smarty->assign('password', $_POST['password']);
						$smarty->assign('password_repeat', $_POST['password_repeat']);
						$smarty->assign('prename', $_POST['prename']);
						$smarty->assign('lastname', $_POST['lastname']);
						$smarty->assign('birthday', $birthday);
					}
				}
				else {
					// $notify->add('Bot', 'Erwischt');
				}
			}
			
			if (!$hide_submenu && $config->get('login', 'register-disable') != 1)
				$menu->addSubElement($mod, $lang->get('register'), 'register');
			$smarty->assign('path', $template_dir . '/register.tpl');
			$smarty->assign('timestamp', time());
		}
	}
	
	if ($_GET['mode'] != 'register') {
		$save_login_disabled = (int)$config->get('core', 'save-login-disabled');
		$smarty->assign('save_login_disabled', $save_login_disabled);
		
		// Here logout
		if ($_GET['mode'] == 'logout')
			$login->logout();
		
		// Here log in
		if (isset($_POST['login'])) {
			if ($login->doLogin($_POST['email'], generatePasswordHash($_POST['password']), @(int)$_POST['save_login'])) {
				$smarty->assign('login_successfull', true);
				$menu->addSubElement('login', $lang->get('logout'), 'logout');
			}
			else {
				$notify->add($lang->get('login'), $lang->get('login_failed'));
			}
		}
		
		// Which one do we show?
		$current = $login->currentUser();
		if ($current != false) {
			$smarty->assign('logged_in', true);
			$smarty->assign('logout_url', makeURL('login', array('mode' => 'logout')));
			$smarty->assign('user', $current);
		}
		else {
			$smarty->assign('logged_in', false);
			if ($config->get('login', 'register-disable') != 1) {
				$menu->addSubElement($mod, $lang->get('register'), 'register');
			}
			$menu->addSubElement($mod, $lang->get('password_lost'), 'lostpw');
		}
		$smarty->assign('path', $template_dir . '/login.tpl');
	}
	
	if ($_GET['mode'] == 'lostpw') {
		$breadcrumbs->addElement($lang->get('password_lost'), makeURL($mod, array('mode' => 'lostpw')));
		if (isset($_POST['submit'])) {
			$u = $user->getUserByEMail($_POST['email']);
			 
			if ($u !== false) {
				$newpw = randomPassword();
				
				$subject = $config->get('login', 'lostpw-mail-subject');
				$text = $config->get('login', 'lostpw-mail-text');
				$sender = $config->get('login', 'register-mail-sender');
				
				$text = str_replace(
						array("%newpassword%", "%nickname%", "%prename%", "%lastname%", "\r\n"), 
						array($newpw, $u['nickname'], $u['prename'], $u['lastname'], "<br />"),
						$text);
				
				$mail_sent = $eMail->send($subject, $text, $u['email'], $sender);
				
				if ($mail_sent) {
					$user->changePassword($u['userid'], $newpw);
					$notify->add($lang->get('password_lost'), $lang->get('password_lost_email_sent'));
					$log->add('login', 'successfully sent new password to user ' . $u['userid'] . ' (' . $u['email'] . ')');
				}
				else {
					$notify->add($lang->get('password_lost'), $lang->get('password_lost_email_failed'));
					$log->add('login', 'failed sending new password to user ' . $u['userid'] . ' (' . $u['email'] . ')');
				}
			}
			else {
				$notify->add($lang->get('password_lost'), $lang->get('user_not_found'));
			}
		}
		$smarty->assign('path', $template_dir . "/lostpw.tpl");
	}
	
	if ($_GET['mode'] == 'unlock') {
		$user->activate($_GET['key']);
	}
	
?>