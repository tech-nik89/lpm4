<?php
	
	$lang->addModSpecificLocalization('stat');
	$smarty->assign('lang', $lang->getAll());
	
	$year = date("Y");
	@$month = (int)$_GET['month'];
	if ($month == 0) $month = date("n");
	$selected_month_ts = mktime(0, 0, 0, $month, 15, date("Y"));
	
	$statistics['month'] = date("F", $selected_month_ts);
	$max = 0;
	$values = $stat->visitorsTablePerMonth($month, $year);
	$statistics['days'] = $values['elements'];
	
	$dlid_in_filename = '';
	if (@(int)$_GET['downloadid'] > 0)
		$dlid_in_filename = '-'.(int)$_GET['downloadid'];
	
	$statistics['max'] = $max;
	$smarty->assign('stat', $statistics);
	
	header('Content-type: text/comma-separated-values');
	header('Content-Disposition: attachment; filename="visitor-stats-'.strtolower(date("F", $selected_month_ts)).'-'.date("Y").$dlid_in_filename.'.csv"');
	
	$smarty->display('../mod/default/stat/csv.export.tpl');
	
?>