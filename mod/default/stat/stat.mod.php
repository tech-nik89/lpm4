<?php
	global $stat, $config;

	$lang->addModSpecificLocalization($mod);
	$breadcrumbs->addElement($lang->get('stat'), makeURL($mod));
	
	if ($rights->isAllowed('stat', 'manage')) {
		$smarty->assign('isallowed', true);
		$count_myself = !isset($_COOKIE['hfh_disable_counter']);
		if (isset($_POST['not_count_myself'])) {
			setcookie('hfh_disable_counter', 'true', time() + 31536000);
			$count_myself = false;
		}
		if (isset($_POST['count_myself'])) {
			setcookie('hfh_disable_counter', '', -3600);
			$count_myself = true;
		}
		if (isset($_POST['clear_stat'])) {
			$sql = "TRUNCATE TABLE `".MYSQL_TABLE_PREFIX."stat`";
			$db->query($sql);
		}
		$smarty->assign('count_myself', $count_myself);
	}
	
	$statistic['config']['stats_enabled'] = $stat->isEnabled();
	$statistic['config']['visitors_month_enabled'] = (int)$config->get('stat', 'show-visitors-this-month');
	$statistic['config']['visitors_year_enabled'] = (int)$config->get('stat', 'show-visitors-this-year');
	$statistic['config']['browseragent'] = (int)$config->get('stat', 'show-browseragent');
	$statistic['config']['os'] = (int)$config->get('stat', 'show-os');
	$statistic['config']['referer'] = (int)$config->get('stat', 'show-referer');
		
	$statistic['running_since'] = $stat->runningSince();
	$statistic['user_online'] = $stat->userOnline();
	$statistic['visitors_today'] = $stat->visitorsToday();
	$statistic['visitors_yesterday'] = $stat->visitorsYesterday();
	$statistic['visitors_this_month'] = $stat->visitorsThisMonth();
	$statistic['visitors_overall'] = $stat->visitorsOverall();
	$visitorList = $stat->listOnline();
	if (count($visitorList) > 0)
		foreach ($visitorList as $i => $v) {
			$visitorList[$i]['url'] = makeURL('profile', array('userid' => $v['userid']));
			@$visitorList[$i]['duration_str'] = durationToString($v['duration']);
		}
		
	$statistic['visitor_list'] = $visitorList;
	
	$lastSeenList = $stat->lastSeen();

	if (count($lastSeenList) > 0)
		foreach ($lastSeenList as $i => $v) {
			$lastSeenList[$i]['url'] = makeURL('profile', array('userid' => $v['userid']));
			$lastSeenList[$i]['timeelapsed'] = timeElapsed($v['lastaction']);
		}
		
	$statistic['lastseen'] = $lastSeenList;
	
	$statistic['visitors_per_day'] = $stat->visitorsPerDay();
	
	$month = date("n");
	$year = date("Y");
	
	if (isset($_POST['btnChangeYearAndMonth'])) {
		$month = (int)$_POST['Date_Month'];
		$year = (int)$_POST['Date_Year'];
	}
	
	$statistic['table_this_month'] = $stat->visitorsTablePerMonth($month, $year);
	$statistic['this_month_name'] = date("F", mktime(0, 0, 0, $month, 1, $year));
	
	$statistic['table_this_year'] = $stat->visitorsTablePerYear($year);
	$statistic['this_year_name'] = date("Y", mktime(0, 0, 0, $month, 1, $year));
	
	$statistic['table_browseragent'] = $stat->makeBrowseragentList();
	$statistic['table_referer'] = $stat->makeRefererList();
	$statistic['table_os'] = $stat->makeOsList();
	
	$url['export'] = 'ajax_request.php?mod=stat&amp;file=stat.export&amp;month='.$month;
	
	$smarty->assign('url', $url);
	$smarty->assign('current_timestamp', mktime(0, 0, 0, $month, 1, $year));
	$smarty->assign('st', $statistic);
	$smarty->assign('path', $template_dir . "/stat.tpl");
	
?>