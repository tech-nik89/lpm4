<?php
	
	$lang->addModSpecificLocalization($mod);
	$smarty->assign('path', $template_dir."/sitemap.tpl");
	$breadcrumbs->addElement($lang->get('sitemap'), makeURL('sitemap'));
	
?>