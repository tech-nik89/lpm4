<?php
	$lang->addModSpecificLocalization('calendar');
	$smarty->assign('lang', $lang->getAll());
	if(!isset($_GET['categoryId'])) {
		$smarty->assign('categoryId', '-1');
	} else {

		$categoryId = (int) $_GET['categoryId'];
		$category = $db->selectOneRow('calendar_categories', "*", "`categoryId` = '".$categoryId."'");
		$smarty->assign('category', $category);
		$smarty->assign('categoryId', $categoryId);
	}
	$smarty->display("../mod/default/calendar/categories_edit.tpl");
?>