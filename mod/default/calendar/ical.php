<?php
	
	$breadcrumbs->addElement($lang->get('ical_info'), makeURL($mod, array('mode' => 'ical')));
	$smarty->assign('path', $template_dir."/ical_info.tpl");
	$p = $_SERVER["PHP_SELF"];
	$pos = strlen($p) - strlen('index.php');
	if (substr($p, $pos) == 'index.php')
		$p = substr($p, 0, $pos);
	$ical_url = 'http://'.$_SERVER["HTTP_HOST"].$p.'media/ical/calendar.ics';
	$smarty->assign('ical_url', $ical_url);
?>