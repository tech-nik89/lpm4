<?php
	
	$lang->addModSpecificLocalization('catering');
	$smarty->assign('lang', $lang->getAll());
	
	$product = $db->selectOneRow('catering_products', '*', '`productid`='.(int)$_GET['productid']);
	$smarty->assign('product', $product);
	
	$product_ingredients = $db->selectList('catering_products_ingredients', '*', '`productid`='.(int)$_GET['productid']);
	$ingr = array();
	foreach ($product_ingredients as $ingredient) {
		$ingr[$ingredient['ingredientid']] = 1;
	}
	$smarty->assign('product_ingredients', $ingr);
	
	$smarty->assign('categories', $db->selectList('catering_categories'));
	$smarty->assign('ingredients', $db->selectList('catering_ingredients'));
	
	$smarty->display('../mod/default/catering/edit.products.admin.tpl');
?>