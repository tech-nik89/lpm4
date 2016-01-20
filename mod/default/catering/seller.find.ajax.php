<?php
	
	if (!$rights->isAllowed('catering', 'manage'))
		die('You are not an admin ;)');
		
	if (strlen(trim($_GET['find'])) > 2) {
		$result = $user->find(trim($_GET['find']), 5);
		$smarty->assign('result', $result);
	}
	
	$smarty->display('../mod/default/catering/seller.find.tpl');
	
?>