<?php
	
	if (!$rights->isAllowed('catering', 'seller'))
		die('You are not an seller ;)');
	
	$result = $user->find($_GET['find'], 10);
	$smarty->assign('result', $result);
	$smarty->display('../mod/default/catering/user.find.ajax.tpl');
?>