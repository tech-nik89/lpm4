<?php
	
	/**
	 * Project: Higher For Hire
	 * File: admin.mod.php
	 *
	 * Rights used
	 *   users				Manage users
	 *   groups				Manage groups
	 *   menu				Modify menu
	 *   mod				Manage modules
	 *	 personal_fields	Manage personal fields
	 *   contact			Manage contact requests
	**/
	
	require_once($mod_dir."/admin.function.php");
	
	// include language file
	$lang->addModSpecificLocalization($mod);

	// add breadcrumbs
	$breadcrumbs->addElement($lang->get('admin'), makeURL('admin'));

	if ($rights->isAllowed($mod, 'content')) {
		$url['content'] = makeURL($mod, array('mode' => 'content'));
		$url['meta'] = makeURL($mod, array('mode' => 'meta'));
		$right['content'] = true;
	}
	
	if ($rights->isAllowed($mod, 'config')) {
		$url['config'] = makeURL($mod, array('mode' => 'config'));
		$url['domains'] = makeURL($mod, array('mode' => 'domains'));
		$right['config'] = true;
	}
	
	if ($rights->isAllowed($mod, 'users')) {
		$url['users'] = makeURL($mod, array('mode' => 'users'));
		$url['newuser'] = makeURL($mod, array('mode' => 'newuser'));
		$url['circular'] = makeURL($mod, array('mode' => 'circular'));
		$right['users'] = true;
	}
	
	if ($rights->isAllowed($mod, 'groups')) {
		$url['groups'] = makeURL($mod, array('mode' => 'groups'));
		$right['groups'] = true;
	}
	
	if ($rights->isAllowed($mod, 'menu')) {
		$url['menu'] = makeURL($mod, array('mode' => 'menu'));
		$right['menu'] = true;
	}
	
	if ($rights->isAllowed($mod, 'mod')) {
		$url['mod'] = makeURL($mod, array('mode' => 'mod'));
		$url['add_key'] = makeURL($mod, array('mode' => 'add_key'));
		$url['add_salt'] = makeURL($mod, array('mode' => 'add_salt'));
		$right['mod'] = true;
	}
	
	if ($rights->isAllowed($mod, 'personal_fields')) {
		$url['personal_fields'] = makeURL($mod, array('mode' => 'personal_fields'));
		$right['personal_fields'] = true;
	}
	
	if ($rights->isAllowed($mod, 'comments')) {
		$url['comments'] = makeURL($mod, array('mode' => 'comments'));
		$right['comments'] = true;
	}
	
	if ($rights->isAllowed($mod, 'contact')) {
		$url['contact'] = makeURL($mod, array('mode' => 'contact'));
		$right['contact'] = true;
	}
	
	if ($rights->isAllowed($mod, 'log')) {
		$url['log'] = makeURL($mod, array('mode' => 'log'));
		$right['log'] = true;
	}
	
	if ($rights->isAllowed($mod, 'groupware')) {
		$url['groupware'] = makeURL($mod, array('mode' => 'groupware'));
		$right['groupware'] = true;
	}
	
	if ($rights->isAllowed($mod, 'sessions')) {
		$url['sessions'] = makeURL($mod, array('mode' => 'sessions'));
		$right['sessions'] = true;
	}
	
	if ($rights->isAllowed($mod, 'boxes')) {
		$url['boxes'] = makeURL($mod, array('mode' => 'boxes'));
		$right['boxes'] = true;
	}
	
	if ($rights->isAllowed($mod, 'backup')) {
		$url['backup'] = makeURL($mod, array('mode' => 'backup'));
		$right['backup'] = true;
	}
	
	@$smarty->assign('right', $right);
	@$smarty->assign('url', $url);
	
	$mode = $_GET['mode'];
	$action = isset($_GET['action']) ? $_GET['action'] : '';

	// how many users do we show per page
	$upp = $config->get($mod, 'users-per-page');
	
	switch ($mode)
	{
		
		case 'domains':
			if (!$right['config'])
				break;
			
			require_once($mod_dir.'/pages/domains.page.php');
			break;
		
		case 'sessions':
			if (!$right['sessions'])
				break;
			
			require_once($mod_dir.'/pages/sessions.page.php');
			break;
			
		case 'circular':
			if (!$right['users'])
				break;
			
			require_once($mod_dir.'/pages/circular.page.php');
			break;
		
		case 'content':
			if (!$right['content'])
				break;
			
			include_once($mod_dir.'/pages/content.page.php');
			break;
		
		case 'meta':
			if (!$right['content'])
				break;
			
			include_once($mod_dir.'/pages/meta.page.php');
			break;
		
		case 'contact':
			
			if (!$right['contact'])
				break;
			
			include_once($mod_dir.'/pages/contact.page.php');
			break;
		
		case 'comments':
		
			if (!$right['comments'])
				$break;
			
			include_once($mod_dir.'/pages/comments.page.php');
			break;
		
		case 'paystate':
		
			if (!$right['paystate'])
				break;
			
			include_once($mod_dir.'/pages/paystate.page.php');
			break;
		
		case 'config':
			
			if (!$right['config'])
				break;
			
			include_once($mod_dir.'/pages/config.page.php');
			break;
		
		case 'groups';
			
			if (!$right['groups'])
				break;
			include_once($mod_dir.'/pages/groups.page.php');
			break;
		
		case 'newuser':
		
			if (!$right['users'])
				break;
			include_once($mod_dir.'/pages/newuser.page.php');
			break;
			
		case 'users':
			if (!$right['users'])
				break;
			
			include_once($mod_dir.'/pages/users.page.php');
			break;
			
		case 'menu':
			if (!$right['menu'])
				break;
			
			include_once($mod_dir.'/pages/menu.page.php');
			break;
			
			
		case 'mod':
			
			if (!$right['mod'])	
				break;
			
			include_once($mod_dir.'/pages/mod.page.php');
			break;
			
		case 'add_key':
			if (!$right['mod'])
				break;
			
			include_once($mod_dir.'/pages/add_key.page.php');
			break;
		
		case 'add_salt':
			if (!$right['mod'])
				break;
				
			include_once($mod_dir.'/pages/add_salt.page.php');
			break;
		
		case 'personal_fields':
		
			if (!$right['personal_fields'])
				break;
			
			include_once($mod_dir.'/pages/personal_fields.page.php');
			break;
			
		case 'log':
			
			if (@!$right['log'])
				break;
			
			include_once($mod_dir.'/pages/log.page.php');
			break;
			
		case 'groupware':
			
			if (!$right['groupware'])
				break;
			
			include_once($mod_dir.'/pages/groupware.page.php');
			break;
			
		case 'boxes':
			
			if (!$right['boxes'])
				break;
			
			include_once($mod_dir.'/pages/boxes.page.php');
			break;
		
		case 'backup':
			
			if (!$right['backup'])
				break;
			
			include_once($mod_dir.'/pages/backup.page.php');
			break;
		
		default:
			if (isset($right))
			{
				// include the template
				$smarty->assign('path', $template_dir . '/admin.tpl');
			} else {
				$notify->add($lang->get('admin'), $lang->get('no_access'));
			}
	}
	
	$smarty->assign('action', $action);
?>