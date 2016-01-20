<?php
	
	/**
	* Higher For Hire: Shoutbox
	*/
	
	$lang->addModSpecificLocalization('shoutbox');
	$smarty->assign('shoutbox_url', makeURL('shoutbox'));
	$smarty->assign('reverse', $config->get('shoutbox', 'reverse'));
	
	$tpl_file = $template_dir."/default.tpl";
	$smarty->assign('shoutbox', 'bla');
	
	if ($login->currentUser() !== false)
		$smarty->assign('loggedin', true);
	
	$visible = true;
?>