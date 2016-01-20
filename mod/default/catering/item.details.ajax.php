<?php
	
	if (!$rights->isAllowed('catering', 'seller'))
		die('You are no seller...');
	
	$lang->addModSpecificLocalization('catering');
	$smarty->assign('lang', $lang->getAll());
	
	$item = $db->selectOneRow('catering_items', '*', '`itemid`='.(int)$_GET['itemid']);
	$smarty->assign('product', $item);
	
	$sql = "
		select i.name
		from ".MYSQL_TABLE_PREFIX."catering_items_ingredients as ii
		left join ".MYSQL_TABLE_PREFIX."catering_ingredients as i
		on ii.ingredientid = i.ingredientid
		where ii.itemid = ".(int)$_GET['itemid'];
		
	$ingredients = $db->queryToList($sql);
	$smarty->assign('ingredients', $ingredients);
	
	$smarty->display('../mod/default/catering/item.details.ajax.tpl');
?>