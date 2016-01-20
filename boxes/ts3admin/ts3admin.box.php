<?php
	$tsimagepath = "mod/default/ts3admin/images/";
	$smarty->assign('imgsrc',$tsimagepath);
	
	// reassign rights
	$rights = new Rights($user, $db, $login);
	$manage_box_allowed = $rights->isallowed("ts3admin", 'manage_box');
	$smarty->assign('manage_box_allowed',$manage_box_allowed);
	
	$tpl_file = $template_dir."/serverlist.tpl";
	$visible = true;
?>