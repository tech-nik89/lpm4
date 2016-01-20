<?php
	$lang->addModSpecificLocalization('admin');
	if ($rights->isAllowed('admin', 'content')) {
		$smarty->assign('lang', $lang->getAll());
		$template_dir = "../mod/default/admin";
		
		if (isset($_GET['version'])) {
			$version = (int)$_GET['version'];
			$db->delete('content', "`key`='".secureMySQL($_GET['key'])."' AND `version`=".$version);
		}
		
		$page = $content->getPage($_GET['key']);
		$smarty->assign('content', $page);
		$smarty->display($template_dir."/version.manage.ajax.tpl");
	}
?>