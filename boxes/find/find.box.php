<?php

	$lang->addModSpecificLocalization('find');

	$tpl_file = $template_dir."/find.tpl";
	$smarty->assign('box_find_url', makeURL('find'));
	$visible = true;
	
?>