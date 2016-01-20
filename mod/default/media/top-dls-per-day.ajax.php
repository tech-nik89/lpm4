<?php
	
	$bots = $config->get('media', 'show-bots') == '1' ? '' : " AND `dc`.`browseragent` <> 'Bot'";
	
	$start = mktime(0, 0, 0, (int)$_GET['month'], (int)$_GET['start_day'], (int)$_GET['year']);
	$end = mktime(23, 59, 59, (int)$_GET['month'], (int)$_GET['end_day'], (int)$_GET['year']);
	
	@$nolimit = isset($_GET['nolimit']);
	
	$sql = "SELECT count(*) as `downloads`, `dc`.`downloadid`, `dl`.`categoryid`, `dl`.`name` 
			FROM `".MYSQL_TABLE_PREFIX."media_downloads_counter` AS `dc`
			LEFT JOIN `".MYSQL_TABLE_PREFIX."media_downloads` AS `dl`
			ON `dc`.`downloadid` = `dl`.`downloadid` WHERE `dc`.`timestamp` > ".$start." 
			AND `dc`.`timestamp` < ".$end." 
			".$bots."
			GROUP BY `dc`.`downloadid` 
			ORDER BY downloads DESC";
	
	if (!$nolimit)
		$sql .= " LIMIT 10";
	
	$result = $db->queryToList($sql);
	
	$smarty->assign('month_str', date("F", $start));
	
	$smarty->assign('items', $result);
	$smarty->display('../mod/default/media/top-dls-per-day.tpl');
	
?>