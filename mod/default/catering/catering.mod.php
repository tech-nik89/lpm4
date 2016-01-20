<?php
	
	/* Catering Module */
	
	require_once($mod_dir.'/catering.function.php');
	
	$lang->addModSpecificLocalization($mod);
	@$mode = $_GET['mode'];
	$breadcrumbs->addElement($lang->get('catering'), makeURL($mod));
	
	$is['seller'] = false;
	$is['manage'] = false;
	
	if ($rights->isAllowed($mod, 'manage')) {
		$menu->addSubElement($mod, $lang->get('admin'), 'admin');
		$is['manage'] = true;
	}
	if ($rights->isAllowed($mod, 'seller')) {
		$menu->addSubElement($mod, $lang->get('seller'), 'seller');
		$is['seller'] = true;
	}
	$smarty->assign('is', $is);
	
	if ($login->currentUser() !== false) {
		$menu->addSubElement($mod, $lang->get('my_orders'), 'orders');
	}
	
	switch ($mode) {
		case 'admin':
			if(!$rights->isAllowed($mod, 'manage')) {
				break;
			}
			require_once($mod_dir.'/catering.admin.php');
			break;
		
		case 'orders':
			if ($login->currentUser() === false) {
				break;
			}
			require_once($mod_dir.'/orders.php');
			break;
			
		case 'seller':
			if(!$rights->isAllowed($mod, 'seller')) {
				break;
			}
			require_once($mod_dir.'/seller.php');
			break;
		
		default:
			if ($login->currentUser() !== false) {
				// Pay bar button was pressed
				if (isset($_POST['PayBarFormSubmitted'])) {
					submitOrderToDB(true);
				}
				
				// Pay with credit
				if (isset($_POST['CreditUserId'])) {
					$order = getOrderFromSession();
					$result = $credit->pay($order['price'], (int)$_POST['CreditUserId'], $login->currentUserId(), "'".$lang->get('catering')."'");
					if ($result) {
						submitOrderToDB(true);
					}
					else {
						$notify->add($lang->get('catering'), $lang->get('not_enough_credit'));
					}
				}
			}
			
			$smarty->assign('categories', getCategories());
			$smarty->assign('show_cart', $login->currentUser() !== false);
			$smarty->assign('path', $template_dir.'/overview.tpl');
			
			break;
	}
	
?>