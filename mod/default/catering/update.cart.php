<?php
	
	$lang->addModSpecificLocalization('catering');
	$smarty->assign('lang', $lang->getAll());
	
	$index = (int) $_GET['index'];
	$quantity = (int) $_GET['quantity'];

	require_once('mod/default/catering/catering.function.php');

	updateOrderQuantityInSession($index, $quantity);

	$smarty->assign('cart', getOrderFromSession());
	$smarty->assign('submit_order', $lang->get('submit_order')); 
	
	$smarty->display('../mod/default/catering/cart.tpl');
?>
