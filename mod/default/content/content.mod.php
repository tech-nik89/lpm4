<?php

	@$page = $content->getPage($_GET['key']);
	$lang->addModSpecificLocalization('admin');
	
	if ($this->isInstalled('formmaker')) {
		@$form = $db->selectOneRow('formmaker', '*', "`key`='".secureMySQL($_GET['key'])."'");
	}
	
	if (@$form['key'] == $_GET['key']) {
		require_once('./mod/default/formmaker/form.display.php');
	}
	else {
	
		$pageVisible = false;
		$pageGroups = array_row($db->selectList('content_permissions', '*', "`key`='".$page['key']."'"), 'groupid');
		if (count($pageGroups) > 0) {
			$myGroups = array_row($rights->getGroups($login->currentUserID()), 'groupid');
			foreach ($pageGroups as $pageGroup) {
				if (in_array($pageGroup, $myGroups)) {
					$pageVisible = true;
					break;
				}
			}
		}
		else {
			$pageVisible = true;
		}
		
		if ($pageVisible) {
			$smarty->assign('page', $page);
			$breadcrumbs->addElement($page['title'], makeURL($page['key']));
			if ($rights->isAllowed('admin', 'content')) {
				$smarty->assign('edit_url', makeURL('admin', array('mode' => 'content', 'action' => 'edit', 'key' => $page['key'])));
			}
		}
		else {
			$notify->add($lang->get('error'), $lang->get('not_allowed'));
		}
		
		$smarty->assign('path', $template_dir . "/default.tpl");
	}
?>