<?php
	
	$lang->addModSpecificLocalization($mod);
	
	if ($login->currentUser() !== false) {
		if (isset($_POST['delete'])) {
			$favorites->delete($_POST['favoriteid']);
		}
		$favs = $favorites->get();
		$smarty->assign('favs', $favs);
		$smarty->assign('path', $template_dir.'/default.tpl');
	}
	
?>