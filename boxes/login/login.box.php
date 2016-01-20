<?php
	
	$loggedin = $login->currentUser() !== false;
	$smarty->assign('loggedin', $loggedin);
	
	if (!$loggedin) {
		if ($config->get('login', 'register-disable') != 1)
			$smarty->assign('login_box_register', true);
			
		$link = array(
			'form' => makeURL('login'), 
			'register' => makeURL('login', array('mode' => 'register')),
			'lostpw' => makeURL('login', array('mode' => 'lostpw'))
		);
		$smarty->assign('link', $link);
	}
	else {
		$link['logout'] = makeURL('login', array('mode' => 'logout'));
		if ($config->get('usercp', 'hide-overview') != '1')
			$link['overview'] = makeURL('usercp');
		if ($config->get('usercp', 'hide-personal') != '1')		
			$link['personal'] = makeURL('usercp', array('mode' => 'personal'));
		if ($config->get('usercp', 'hide-avatar') != '1')
			$link['avatar'] = makeURL('usercp', array('mode' => 'avatar'));
		if ($config->get('usercp', 'hide-comments') != '1')
			$link['comments'] = makeURL('usercp', array('mode' => 'comments'));
		if ($config->get('usercp', 'hide-changepw') != '1')
			$link['changepw'] = makeURL('usercp', array('mode' => 'changepw'));
		$smarty->assign('link', $link);
	}
	
	$tpl_file = $template_dir."/login.tpl";
	$visible = true;
	
?>