<?php
	
	if ($eMail->getRegisterAdress() == '') {
		$notify->add($lang->get('error'), $lang->get('sender_mail_not_set'));
	}
	else {
		$smarty->assign('adress_set', true);
	}
	
	$smarty->assign('groups', $rights->getAllGroups());
	$breadcrumbs->addElement($lang->get('circular_mail'), makeURL($mod, array('mode' => 'circular')));
	$smarty->assign('path', $template_dir."/circular.tpl");
	
?>