<?php
	
	$lang->addModSpecificLocalization('catering');
	$smarty->assign('lang', $lang->getAll());
	
	$smarty->display('../mod/default/catering/add.categories.admin.tpl');
?>