<?php
	
	$breadcrumbs->addElement($lang->get('log'), makeURL($mod, array('mode' => 'log')));
	$smarty->assign('path', $template_dir  . "/log.tpl");
	
	if ($log->mysqlIsEnabled())
	{
		$list = $db->selectList($log->getTable(), "*", "1", "`timestamp` DESC");
		if (count($list) > 0)
			foreach ($list as $i => $l)
				$list[$i]['time'] = '[ ' . date("d.m.Y", $l['timestamp']) . " | " . date("H:i.s", $l['timestamp']) . " ]";
		
		$smarty->assign('list', $list);
	}
	
	$smarty->assign('filelist', $log->listFileLogs());
	
?>