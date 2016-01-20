<?php
	
	if ($rights->isAllowed('tagcloud', 'manage')) {
		
		$action = isset($_GET['add']) ? 'add' : 'edit';
		$smarty->assign('action', $action);
		@$tagid = (int)$_GET['tagid'];
		$smarty->assign('tagid', $tagid);
		
		if ($tagid > 0 && $action == 'edit') {
			$tag = $db->selectOneRow('tagcloud', '*', '`tagid`='.$tagid);
			$smarty->assign('tag', $tag);
		}
		
		$lang->addModSpecificLocalization('tagcloud');
		$smarty->assign('domains', getDomainList());
		$smarty->assign('languages', array_merge(array('' => ''), $lang->listLanguages()));
		$smarty->assign('lang', $lang->getAll());
		
		$smarty->display('../mod/default/tagcloud/manage.edit.tpl');
	}
	else {
		die('Not in this life, bro ...');
	}
	
?>