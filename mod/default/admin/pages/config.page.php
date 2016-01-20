<?php
	
	// add a breadcrumb
	$breadcrumbs->addElement($lang->get('config'), makeURL($mod, array('mode' => 'config')));
	
	// include the template
	$smarty->assign('path', $template_dir . '/config.tpl');
	
	// save ... or not ^^
	if (isset($_POST['save']))
	{
		if (@$_POST['template'] != $config->get('core', 'template')) {
			redirect();
		}
		
		@$config->set('core', 'maintenance', (int)$_POST['maintenance']);
		@$config->set('core', 'maintenance_description', $_POST['maintenance_description']);
		@$config->set('core', 'template', $_POST['template']);
		@$config->set('core', 'mod_rewrite', (int)$_POST['mod_rewrite']);
		@$config->set('core', 'bbcode', (int)$_POST['bbcode']);
		@$config->set('core', 'language', $_POST['language']);
		
		@$config->set('login', 'register-activation-required', (int)$_POST['register-activation-required']);
		@$config->set('login', 'register-mail-sender', $_POST['register-mail-sender']);
		@$config->set('login', 'register-mail-subject', $_POST['register-mail-subject']);
		@$config->set('login', 'register-mail-text', $_POST['register-mail-text']);
		
		@$config->set('login', 'lostpw-mail-subject', $_POST['lostpw-mail-subject']);
		@$config->set('login', 'lostpw-mail-text', $_POST['lostpw-mail-text']);
	}
	
	// ##### default mod #### //
	
	// get a list of available modules
	// get a list of all static pages
	$pagelist = $content->getPageList();
	
	// get a list of available modules
	$modlist = $this->listInstalled();
	
	// create dropdown list
	if ($pagelist == null) {
		$dropdownlist = $modlist;
	}
	else {
		$dropdownlist = array_merge($modlist, array(array('mod' => '---')), $pagelist);
	}
	
	$smarty->assign('mlist', $dropdownlist);
	
	$smarty->assign('default_mod', $config->get('core', 'default_mod'));
	
	// ##### default template //
	
	$tpllist = listTemplates(); 
	$smarty->assign('tlist', $tpllist);
	
	$smarty->assign('template', $config->get('core', 'template'));
	
	// #### maintenance mode ####
	$smarty->assign('maintenance', $config->get('core', 'maintenance'));
	$smarty->assign('maintenance_description', $config->get('core', 'maintenance_description'));
	
	// #### encrypt url #### //
	$smarty->assign('encrypt_url', $config->get('core', 'encrypt-url'));
	
	// #### mod_rewrite #### //
	$smarty->assign('mod_rewrite', $config->get('core', 'mod_rewrite'));
	
	// #### bbcode #### //
	$smarty->assign('bbcode', $config->get('core', 'bbcode'));
	
	// ##### language ###### //
	
	$langlist = $lang->listLanguages(); 
	$smarty->assign('llist', $langlist);
	
	$smarty->assign('language', $config->get('core', 'language'));
	
	$smarty->assign('activation_required', $config->get('login', 'register-activation-required'));
	$smarty->assign('register_mail_sender', $config->get('login', 'register-mail-sender'));
	$smarty->assign('register_mail_subject', $config->get('login', 'register-mail-subject'));
	$smarty->assign('register_mail_text', $config->get('login', 'register-mail-text'));
	
	$smarty->assign('lostpw_mail_subject', $config->get('login', 'lostpw-mail-subject'));
	$smarty->assign('lostpw_mail_text', $config->get('login', 'lostpw-mail-text'));
	
?>