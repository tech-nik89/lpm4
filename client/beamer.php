<?php
	$tbl_n = MYSQL_TABLE_PREFIX.'news';
	
	$allnews = $db->selectList($tbl_n, "*", "1", "timestamp DESC");
	
	$smarty->assign('allnews', $allnews);
	
	// Load template and display
	$smarty->display('../client/beamer.tpl');
?>