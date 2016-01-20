<?php
	
	if (strlen($_GET['search']) >= 2)
	{
		$list = $db->selectList(MYSQL_TABLE_PREFIX . 'users', "*", "INSTR(`nickname`, '" . secureMySQL($_GET['search']) . "') > 0", "`nickname` ASC", "10");
	}
	else
	{
		$list = array();
	}
	
	$smarty->assign('list', $list);
	$smarty->display('../mod/default/pmbox/userlist.tpl');
?>