<?php
	$lang->addModSpecificLocalization('catering');
	$smarty->assign('lang', $lang->getAll());
	
	require_once('mod/default/catering/catering.function.php');

	submitOrderToDB();

	$smarty->assign('cart', null);
	$smarty->assign('msg', $lang->get('order_submit_done'));
	
	$smarty->display('../mod/default/catering/cart.tpl');
?>
