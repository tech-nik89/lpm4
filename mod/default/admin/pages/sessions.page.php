<?php
	
	$breadcrumbs->addElement($lang->get('sessions'), makeURL($mod, array('mode' => 'sessions')));
	
	require_once($mod_dir . "/sessions.class.php");
	$sessions = new Sessions();
	
	$smarty->assign('path', $template_dir . "/sessions.tpl");
	$smarty->assign('list', $sessions->listActive());
	
?>