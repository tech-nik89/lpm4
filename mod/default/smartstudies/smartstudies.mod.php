<?php
	
	/**
	* Project: Higher For Hire
	* File: smartstudies.mod.php
	*/

	$lang->addModSpecificLocalization($mod);
	if(!$rights->isAllowed('smartstudies', 'manage')) {
		return;
	}
	
	if (isset($_POST['do'])){
		include($mod_dir . "/smartstudies.function.php");
		DeleteAll();
		CreateAll($_POST['smartstudies_coursename'], $_POST['smartstudies_dummypassword'], "dhbw4ever!");
	} else {
		$url = makeHTMLUrl($lang->get('reset'), makeURL('smartstudies', array('do' => 'it')));
		$url = makeURL('smartstudies', array('do' => 'it'));
		$smarty->assign('url', $url);
		$smarty->assign('path', $template_dir."/smartstudies.tpl");
	}
	
?>