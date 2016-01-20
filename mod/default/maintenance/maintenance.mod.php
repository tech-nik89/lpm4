<?php
	
	$smarty->assign('path', $template_dir."/maintenance.tpl");
	$smarty->assign('reason', $config->get('core', 'maintenance_description'));
	
?>