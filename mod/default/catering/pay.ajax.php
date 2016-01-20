<?php
	
	if (!$rights->isAllowed('catering', 'seller'))
		die("You're, kidding ... aren't you?");
	
	$lang->addModSpecificLocalization('catering');
	$smarty->assign('lang', $lang->getAll());
	
	switch ($_GET['mode']) {
		default:
		case 'bar':
			$smarty->display('../mod/default/catering/pay.bar.tpl');
			break;
		case 'credit':
			$smarty->display('../mod/default/catering/pay.credit.tpl');
	}
?>