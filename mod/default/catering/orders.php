<?php
	
	$breadcrumbs->addElement($lang->get('my_orders'), makeURL($mod, array('mode' => 'orders')));

	$sql = "SELECT `orderid` AS `id`, `date`, 
			(SELECT count(itemid) FROM ".MYSQL_TABLE_PREFIX."catering_items WHERE `orderid` = `id`) AS `items`,
			(SELECT MAX(`state`) FROM `".MYSQL_TABLE_PREFIX."catering_items` WHERE `orderid` = `id`) AS `max_state`
			FROM ".MYSQL_TABLE_PREFIX."catering_orders WHERE ordererid = ".$login->currentUserId()." ORDER BY `date` DESC";
	
	$orders = $db->queryToList($sql);
	$smarty->assign('orders', $orders);
	$smarty->assign('path', $template_dir.'/orders.tpl');
	
?>