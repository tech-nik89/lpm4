<?php
	
	// add a breadcrumb
	$breadcrumbs->addElement($lang->get('user_add'), makeURL($mod, array('mode' => 'newuser')));
	
	// assign template file
	$smarty->assign('path', $template_dir . "/newuser.tpl");
	
	// generate random password
	$smarty->assign('password', randomPassword());
	
	if (isset($_POST['create'])) {
		$birthday = mktime(0, 0, 0 , $_POST['Date_Month'], $_POST['Date_Day'], $_POST['Date_Year']);
		
		if ( $user->createUser($_POST['email'], $_POST['password'], $_POST['nickname'],  $_POST['lastname'], $_POST['prename'], $birthday) == 0 ) {
			$notify->add($lang->get('users'), $lang->get('user_add_done'));
			redirect(makeURL($mod, array('mode' => 'users')));
		}
		else {
			$notify->add($lang->get('users'), $lang->get('user_add_failed'));
		}
	}
	
?>