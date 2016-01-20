<?php
	
	$breadcrumbs->addElement('Add Config Key', makeURL($mod, array('mode' => 'add_key')));
	$smarty->assign('path', $template_dir."/add_key.tpl");
	if (isset($_POST['submit'])) {
		$config->register($_POST['key_mod'], $_POST['key_key'], 
			$_POST['key_value'], $_POST['key_type'], $_POST['key_descr']);
		$notify->add('Config', 'Config key has been successfully added.');
	}

?>