<?php
	$categoryid = (int) $_GET['categoryid'];
	$products = $db->selectList('catering_products', '*', '`categoryid`='.$categoryid.' AND `amount` != 0', '`name` ASC');
	
	$smarty->assign('products', $products);
	$smarty->display('../mod/default/catering/show.products.tpl');
?>
