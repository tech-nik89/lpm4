<?php
	
	$lang->addModSpecificLocalization('catering');
	$smarty->assign('lang', $lang->getAll());
	$ingredient = $db->selectOneRow('catering_ingredients', '*', '`ingredientid`='.(int)$_GET['ingredientid']);
	$smarty->assign('ingredient', $ingredient);
	$smarty->display('../mod/default/catering/edit.ingredients.admin.tpl');
?>