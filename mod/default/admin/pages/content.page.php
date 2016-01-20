<?php
	
	if ($this->isInstalled('fileadmin')) {
		$smarty->assign('fileadmin_installed', true);
	}
	
	$menu->addSubElement($mod, $lang->get('content_add'), 'content', array('action' => 'add'));
	$action = isset($_GET['action']) ? $_GET['action'] : '';
	$smarty->assign('action', $action);
	
	$breadcrumbs->addElement($lang->get('content'), makeURL($mod, array('mode' => 'content')));
	
	// get groups
	$grouplist = $rights->getAllGroups();
	$smarty->assign('groups', $grouplist);
	
	switch ($action) {
		case 'add':
			$breadcrumbs->addElement($lang->get('add'), makeURL($mod, array('mode' => 'content', 'action' => 'add')));
			
			if (isset($_POST['submit']))
			{
				if (trim($_POST['title']) != '' && trim($_POST['text']) != '' && trim($_POST['key']) != '') {
					if (!$this->modExists(stringToURL($_POST['key'])) && !$content->pageExists($_POST['key'])) {
						$assigned_groups = array();
						foreach ($grouplist as $group) {
							if (@$_POST['group_'.$group['groupid']] == '1')
								$assigned_groups[] = $group['groupid'];
						}
						$content->createPage($_POST['title'], $_POST['text'], $assigned_groups, $_POST['key'], $_POST['box_content']);
						$notify->add($lang->get('content'), $lang->get('content_added'));
						$log->add($mod, 'content ' . $_POST['title'] .  ' added');
						$smarty->assign('locked', true);
						redirect(makeURL($mod, array('mode' => 'content', 'action' => 'edit', 'key' => $_POST['key'])));
					}
					else {
						$notify->add($lang->get('error'), $lang->get('content_error'));
					}
				}
				else {
					$notify->add($lang->get('error'), $lang->get('fill_all_fields'));
				}
			}
			
			$smarty->assign('permissions', array());
			$smarty->assign('path', $template_dir . "/content.edit.tpl");
			break;
			
		case 'edit':
			if (isset($_POST['submit']) && trim($_POST['title']) != '' && trim($_POST['text']) != '')
			{
				if (!$this->modExists(stringToURL($_GET['key']))) {
					$assigned_groups = array();
					foreach ($grouplist as $group) {
						if (@$_POST['group_'.$group['groupid']] == '1')
							$assigned_groups[] = $group['groupid'];
					}
					$content->editPage($_GET['key'], $_POST['title'], $_POST['text'], $assigned_groups, $_GET['key'], $_POST['box_content']);
					$notify->add($lang->get('content'), $lang->get('content_edited'));
					$log->add($mod, 'content ' . $_GET['key'] .  ' updated');
					$smarty->assign('locked', true);
					unset($_GET['version']);
					redirect(makeURL($mod, array('mode' => 'content', 'action' => 'edit', 'key' => $_GET['key'])));
				}
				else {
					$notify->add($lang->get('error'), $lang->get('content_error'));
				}
			}
			
			$ver = isset($_GET['version']) ? (int)$_GET['version'] : -1;
			$page = $content->getPage($_GET['key'], $ver);
			if ($ver == -1) $ver = $page['version'];
			@$smarty->assign('selected_version', $ver);
			
			$smarty->assign('content', $page);
			$assigned_groups = array_row($db->selectList('content_permissions', '*', "`key`='".secureMySQL($_GET['key'])."'"), 'groupid');
			$smarty->assign('permissions', $assigned_groups);
			
			$breadcrumbs->addElement($page['title'], makeURL($_GET['key']));
			$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('mode' => 'content', 'action' => 'edit', 'key' => $_GET['key'])));
			
			$smarty->assign('path', $template_dir . "/content.edit.tpl");
			break;
		
		case 'remove':
			if (isset($_POST['yes']))
			{
				
				$content->removePage($_GET['key']);
				$notify->add($lang->get('content'), $lang->get('content_removed'));
				$log->add($mod, 'content ' . $_GET['key'] .  ' removed');
				
			} else {
			
				$breadcrumbs->addElement($lang->get('remove'), makeURL($mod, array('mode' => 'content', 'action' => 'remove', 'key' => $_GET['key'])));
				
				
				$smarty->assign('url_no', makeURL($mod, array('mode' => 'content')));
				$smarty->assign('path', $template_dir . "/content.remove.tpl");
				break;
			
			}
		default:
			$cont = $content->getPages();
			
			$smarty->assign('content', $cont);
			$smarty->assign('add_url', makeURL($mod, array('mode' => 'content', 'action' => 'add')));
			
			$smarty->assign('path', $template_dir . "/content.tpl");
	}
	
?>