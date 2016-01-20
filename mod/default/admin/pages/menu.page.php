<?php
	
	
	// add a breadcrumb
	$breadcrumbs->addElement($lang->get('menu_editor'), makeURL($mod, array('mode' => 'menu')));
	
	// add submenu entries
	$menu->addSubElement($mod, $lang->get('add'), 'menu', array('action' => 'add'));
	
	switch ($action) {
	
		case 'add':	
			$smarty->assign('path', $template_dir."/menu.entry.tpl");
			$breadcrumbs->addElement($lang->get('add'), makeURL($mod, array('mode' => 'menu', 'action' => 'add')));
			if (isset($_POST['save']) && trim($_POST['title']) != '') {
				$menu->addElement(
					$_POST['title'],
					$_POST['mod'],
					@(int)$_POST['requires_login'],
					@(int)$_POST['assigned_groupid'],
					@(int)$_POST['parentid'],
					@$_POST['language'],
					@$_POST['startpage'],
					@$_POST['template'],
					@$_POST['domainid']
				);
				@$entry = array(
					'title' => $_POST['title'],
					'mod' => $_POST['mod'],
					'requires_login' => (int)$_POST['requires_login'],
					'assigned_groupid' => (int)$_POST['assigned_groupid'],
					'parentid' => (int)$_POST['parentid'],
					'language' => $_POST['language'],
					'startpage' => $_POST['startpage'],
					'template' => $_POST['template'],
					'domainid' => $_POST['domainid']
				);
				$notify->add($lang->get('menu'), $lang->get('menu_entry_saved'));
				$smarty->assign('locked', true);
				$smarty->assign('entry', $entry);
				redirect(makeURL($mod, array('mode' => 'menu', 'action' => 'add')));
			}
			break;
		
		case 'edit':
			@$menuid = (int)$_GET['menuid'];
			$smarty->assign('path', $template_dir."/menu.entry.tpl");
			$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('mode' => 'menu', 'action' => 'edit', 'menuid' => $menuid)));
			$entry = $menu->getMenuEntry($menuid);
			if (isset($_POST['save']) && trim($_POST['title']) != '') {
				$menu->editElement(
					$menuid,
					$_POST['title'],
					$_POST['mod'],
					@(int)$_POST['requires_login'],
					@(int)$_POST['assigned_groupid'],
					@(int)$_POST['parentid'],
					@$_POST['language'],
					@$_POST['startpage'],
					@$_POST['template'],
					@$_POST['domainid']
				);
				@$entry = array(
					'title' => $_POST['title'],
					'mod' => $_POST['mod'],
					'requires_login' => (int)$_POST['requires_login'],
					'assigned_groupid' => (int)$_POST['assigned_groupid'],
					'parentid' => (int)$_POST['parentid'],
					'language' => $_POST['language'],
					'home' => $_POST['startpage'],
					'template' => $_POST['template'],
					'domainid' => $_POST['domainid']
				);
				$notify->add($lang->get('menu'), $lang->get('menu_entry_saved'));
				$smarty->assign('locked', true);
			}
			$smarty->assign('entry', $entry);
			break;
			
		default:
			// include the template
			$smarty->assign('path', $template_dir . '/menu.tpl');
			
			if (isset($_POST['save'])) {
				$menuList = $menu->getMenuRaw();
				foreach ($menuList as $i => $item) {
					updateElement($item);
				}
			}
	}
	
	// get a list of all static pages
	$pagelist = $content->getPageList();
	$smarty->assign('pagelist', $pagelist);
	
	// get a list of available modules
	$modlist = $this->listInstalled();
	$smarty->assign('modlist', $modlist);
	
	// get a list of available languages
	$langlist = $lang->listLanguages();
	$smarty->assign('languages', $langlist);
	
	// create form list
	if ($this->isInstalled('formmaker')) {
		$formlist = $db->selectList('formmaker');
		if (null != $formlist && count($formlist) > 0) {
			foreach ($formlist as $form) {
				$lst[] = array('mod' => $form['key']);
			}
			$smarty->assign('formlist', $lst);
		}
	}
	
	// get groups
	$groups = $rights->getAllGroups();
	$all = array(array('groupid' => 0, 'name' => $lang->get('all')));
	$nobody = array(array('groupid' => -1, 'name' => $lang->get('nobody')));
	$smarty->assign('groups', array_merge($all, $nobody, $groups));
	
	// get menu
	if (isset($_POST['do_filter'])) {
		$_SESSION['menu_filter_language'] = $_POST['filter_language'];
		$_SESSION['menu_filter_domain'] = $_POST['filter_domain'];
	}
	@$m = $menu->getMenuRaw(0, $_SESSION['menu_filter_language'], $_SESSION['menu_filter_domain']);
	$smarty->assign('m', $m);
	@$smarty->assign('filter_language', $_SESSION['menu_filter_language']);
	@$smarty->assign('filter_domain', $_SESSION['menu_filter_domain']);
	
	// list available templates
	$smarty->assign('tlist', listTemplates());
	$smarty->assign('dlist', getDomainList());

?>