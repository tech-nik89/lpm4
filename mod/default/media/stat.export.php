<?php
	if ($rights->isAllowed('media', 'manage')) {
		require_once('./mod/default/media/media.function.php');
		$lang->addModSpecificLocalization('media');
		$smarty->assign('lang', $lang->getAll());
		
		@$month = (int)$_GET['month'];
		@$year = (int)$_GET['year'];
		if ($month == 0) $month = date("n");
		if ($year == 0) $year = date("Y");
		$selected_month_ts = mktime(0, 0, 0, $month, 15, $year);
		
		$statistics['month'] = date("F", $selected_month_ts);
		$statistics['year'] = $year;
		$max = 0;
		for ($day = 1; $day <= date("t", $selected_month_ts); $day++) {
			@$counter = getDownloadCounter(
				mktime(0, 0, 0, $month, $day, $year),
				mktime(23, 59, 59, $month, $day, $year),
				(int)$_GET['downloadid']
			);
			if ($counter > $max) $max = $counter;
			$statistics['days'][] = array(
				'day' => $day,
				'counter' => $counter
			);
		}
		
		$dlid_in_filename = '';
		if (@(int)$_GET['downloadid'] > 0)
			$dlid_in_filename = '-'.(int)$_GET['downloadid'];
		
		$statistics['max'] = $max;
		$smarty->assign('stat', $statistics);
		header('Content-type: text/comma-separated-values');
		header('Content-Disposition: attachment; filename="download-stats-'.strtolower(date("F", $selected_month_ts)).'-'.$year.$dlid_in_filename.'.csv"');
		$smarty->display('../mod/default/media/csv.export.tpl');
	}
?>