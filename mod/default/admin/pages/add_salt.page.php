<?php
	
	$breadcrumbs->addElement($lang->get('add_salt'), makeURL($mod, array('mode' => 'add_salt')));
	$smarty->assign('path', $template_dir."/add_salt.tpl");
	if (isset($_POST['add_salt_do'])) {
		$salt = $config->get('core', 'password-salt');
		if ($salt != '') {
			$db->query("UPDATE `".MYSQL_TABLE_PREFIX."users` SET `password` = MD5(SHA1(CONCAT('".$salt."', `password`, '".$salt."')));");
			$notify->add($lang->get('add_salt'), 'Salting done!');
		}
		else {
			$notify->add($lang->get('add_salt'), 'No salt defined. Please go to admin -> mod -> core -> configuration -> password-salt and fix this.');
		}
	}
	
?>