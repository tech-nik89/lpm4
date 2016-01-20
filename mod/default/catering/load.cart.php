<?php
	$lang->addModSpecificLocalization('catering');
	$smarty->assign('lang', $lang->getAll());
	
	$productid = (int) $_GET['productid'];
	$ingredients = explode(",", $_GET['ingredients']);

	require_once('mod/default/catering/catering.function.php');

	addOrderToSession($productid, $ingredients);

	$smarty->assign('cart', getOrderFromSession());

	$smarty->display('../mod/default/catering/cart.tpl');
?>
