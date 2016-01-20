<?php
	
	if (isset($_POST['changeStateSubmitButton'])) {
		$db->update('catering_items', "`state`=".(int)$_POST['newItemState'], "`itemid`=".(int)$_POST['changeItemStateId']);
	}
	
	$sql = "
		select i.orderid, i.itemid, p.name, i.state,  o.date, o.ordererid, u.nickname
		from ".MYSQL_TABLE_PREFIX."catering_items as i
		left join ".MYSQL_TABLE_PREFIX."catering_orders as o
		on i.orderid = o.orderid
		left join ".MYSQL_TABLE_PREFIX."catering_products as p
		on i.productid = p.productid
		left join ".MYSQL_TABLE_PREFIX."users as u
		on u.userid = o.ordererid
		where p.sellerid = 1
		order by o.date desc";

	$result = $db->queryToList($sql);
	$smarty->assign('result', $result);
	
	$smarty->assign('path', $template_dir.'/seller.tpl');
?>