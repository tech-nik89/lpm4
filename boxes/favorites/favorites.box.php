<?php
	
	$table = MYSQL_TABLE_PREFIX . 'favorites';
	$smarty->assign('box_favs_enabled', $config->get('favorites', 'enable') == '1' ? true : false);
	
	if ($login->currentUser() !== false) {
		$favs = $favorites->get();
		
		$smarty->assign('box_favs_inmodule', $_GET['mod'] == 'favorites');
		
		$smarty->assign('box_favs', $favs);
		$tpl_file = $template_dir."/default.tpl";
		$smarty->assign('box_favs_url', makeURL('favorites'));
	}
	else {
		$tpl_file = $template_dir."/login.tpl";
	}
	
	$visible = true;
	
?>