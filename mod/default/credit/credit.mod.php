<?php
	$smarty->assign('path', $template_dir."/credit.tpl");
	$smarty->assign('mv', $credit->getMovements());
	$smarty->assign('balance', $credit->getBalance());
	$smarty->assign('last_activity', timeElapsed($credit->getLastActivity()));
?>