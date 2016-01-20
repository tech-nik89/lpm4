<?php
	
	$lang->addModSpecificLocalization('catering');
	$smarty->assign('lang', $lang->getAll());
	
	$smarty->assign('categories', $db->selectList('catering_categories'));
	$smarty->assign('ingredients', $db->selectList('catering_ingredients'));
	$smarty->display('../mod/default/catering/add.products.admin.tpl');
?>