<?php
	
	if (isset($_POST['go'])) {
		$date = @explode("/", $_POST['date']);
		$day = mktime(0, 0, 0, (int)$date[0], (int)$date[1], (int)$date[2]);
		redirect(makeURL($mod, array('view' => 'day', 'day' => $day)));
	}
	
	$smarty->assign('current_date', date("d.m.Y H:i", $day));
	$smarty->assign('current_day', date("m/d/Y", $day));
	
	$smarty->assign('path', $template_dir."/switch_date.tpl");
	
	$breadcrumbs->addElement($lang->get('switch_date'), makeURL($mod, array('mode' => 'switch_date', 'day' => $day)));
	
?>