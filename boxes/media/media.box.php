<?php
	
	$lang->addModSpecificLocalization('media');
	global $current_language;
	require_once('mod/default/media/media.function.php');
	
	$list = getNewestDownloads();
	
	$smarty->assign('media_box_entries', $list);
	$visible = true;
	$tpl_file = $template_dir."/default.tpl";
	
?>