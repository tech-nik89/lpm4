<?php
	
	$lang->addModSpecificLocalization('catering');
	$smarty->assign('lang', $lang->getAll());
	$category = $db->selectOneRow('catering_categories', '*', '`categoryid`='.(int)$_GET['categoryid']);
	$smarty->assign('category', $category);
	$smarty->display('../mod/default/catering/edit.categories.admin.tpl');
?>