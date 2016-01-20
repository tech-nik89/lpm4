<?php
	
	if ($login->currentUser() === false)
		die('Please log in ;)');
	
	$lang->addModSpecificLocalization('catering');
	$smarty->assign('lang', $lang->getAll());
	require_once('mod/default/catering/catering.function.php');
	$orderid = (int)$_GET['orderid'];
	
	$sql = "SELECT `p`.`name`, (`p`.`price` + (SELECT SUM(`ing`.`price`) FROM `".MYSQL_TABLE_PREFIX."catering_items_ingredients` AS `ing` WHERE `ing`.`itemid` = `i`.`itemid`)) AS `price_sum`, `i`.`state` 
			FROM `".MYSQL_TABLE_PREFIX."catering_items` AS `i`
			LEFT JOIN `".MYSQL_TABLE_PREFIX."catering_products` AS `p`
			ON `p`.`productid` = `i`.`productid`
			WHERE `orderid`=".$orderid;
	
	$items = $db->queryToList($sql);
	$smarty->assign('items', $items);
	
	$smarty->display('../mod/default/catering/order.details.tpl');
	
?>