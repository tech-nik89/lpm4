<?php
	
	function makeDate($ts)
	{
		return date("d.m.Y", $ts);
	}
	
	function makeDateRange($ts_start, $ts_end)
	{
		global $lang;
		return makeDate($ts_start) . ' ' . $lang->get('to') . ' ' . makeDate($ts_end);
	}
	
	function makeTime($ts)
	{
		global $lang;
		return date("H", $ts) . '<sup>' . date("i", $ts) . '</sup> ' . $lang->get('o_clock');
	}
	
	function hasEntry($day)
	{
		global $db, $tbl;
		
		$day_start = mktime(0, 0, 0, date("m", $day), date("d", $day), date("Y", $day));
		$day_end = mktime(23, 59, 59, date("m", $day), date("d", $day), date("Y", $day));
		
		if ($db->num_rows($tbl, "`start` >= ".$day_start." AND `start`<=".$day_end) > 0)
			return true;
		else
			return false;
	}
	
	function listEntries($day, $day_end = 0)
	{
		global $db, $tbl;
		global $login;
		global $isallowed;
		global $current_language;
		
		$day_start = mktime(0, 0, 0, date("m", $day), date("d", $day), date("Y", $day));
		
		if ($day_end == 0)
			$day_end = mktime(23, 59, 59, date("m", $day), date("d", $day), date("Y", $day));
		else
			$day_end = mktime(23, 59, 59, date("m", $day_end), date("d", $day_end), date("Y", $day_end));
		if ($isallowed)
		{
			$list = $db->selectList($tbl, "*", "(`start`>=".$day_start." AND `start`<=".$day_end.") AND (`language` = '' OR `language` = '".$current_language."')", "`start` ASC");
		}
		else
		{
			if ($login->currentUser() === false)
			{
				$list = $db->selectList($tbl, "*", "(`start`>=".$day_start." AND `start`<=".$day_end.") 
					AND ( (`visible`=2)  OR  (`visible`=0 AND `userid`=".$login->currentUserID().") )
					AND (`language` = '' OR `language` = '".$current_language."')", "`start` ASC");
			}
			else
			{
				$list = $db->selectList($tbl, "*", "(`start`>=".$day_start." AND `start`<=".$day_end.") 
					AND ( (`visible`=2)  OR  (`visible`=0 AND `userid`=".$login->currentUserID().") OR (`visible`=1) )
					AND (`language` = '' OR `language` = '".$current_language."')", "`start` ASC");
			}
		}
		return $list;
	}
	
	function visibilityToString($visibility)
	{
		global $lang;
		switch ($visibility)
		{
			case 0: return $lang->get('private');
			case 1: return $lang->get('registered');
			case 2: return $lang->get('public');
		}
	}
	
	function makeEntryArrayQuarters($entries, &$maxCols) {
		$maxCols = 0;

		if (count($entries) > 0) {
			$day_view = array();
		
			foreach ($entries as $entry) {
				$hour = (int) date("H", $entry['start']);
				$quarter = (int) floor(date("i", $entry['start'])/15);
		
				$quarters = $hour*4 + $quarter;
				
				$rowspan = ceil(($entry['end']-$entry['start'])/60/15);

				// Check which column to put in
				for($col=0; $col<=$maxCols+1; $col++) {
					$foundCol=true;
					for($row=0; $row<$rowspan; $row++) {
						if(isset($day_view[$quarters+$row][$col])) {
							$foundCol = false;
						}
					}
					if($foundCol) {
						$column = $col;
						break 1;
					}
				}
				$maxCols = ($maxCols<$column)?$column:$maxCols;

				$day_view[$quarters][$column]['start']['hour'] = date("H",  $entry['start']);
				$day_view[$quarters][$column]['start']['min'] = date("i",  $entry['start']);
				$day_view[$quarters][$column]['end']['hour'] = date("H",  $entry['end']);
				$day_view[$quarters][$column]['end']['min'] = date("i",  $entry['end']);
				$day_view[$quarters][$column]['rowspan'] = $rowspan;
				$day_view[$quarters][$column]['title'] = makeHTMLURL($entry['title'],	makeURL('calendar', array('mode' => 'view', 'calendarid' => $entry['calendarid'])));
				$day_view[$quarters][$column]['description'] = nl2br($entry['description']);
				$day_view[$quarters][$column]['calandarid'] = $entry['calendarid'];
				
				global $db;
				$color = $db->selectOneRow('calendar_categories', '*', "`categoryId`='".$entry['categoryId']."'");

				$day_view[$quarters][$column]['backgroundcolor'] = (strlen($color['backgroundcolor']) > 0)?$color['backgroundcolor']:"#57FF7E";
				$day_view[$quarters][$column]['fontcolor'] = (strlen($color['fontcolor']) > 0)?$color['fontcolor']:"#000000";

				for($i=1; $i<$rowspan; $i++) {
					$day_view[$quarters+$i][$column]['rowspan'] = 0;
				}
				
			}
		}
		for($i=0; $i<24*4; $i++) {
			$day_view[$i]['hour'] = floor($i/4);
			$day_view[$i]['quarter'] = sprintf("%02d", $i%4 * 15);
			for($j=0; $j<=$maxCols; $j++) {
				if(!isset($day_view[$i][$j]['rowspan'])) {
					$day_view[$i][$j]['rowspan'] = 1;
				} 
			}
		}
		ksort($day_view, SORT_NUMERIC);
		
		return $day_view;	
	}
	
	function makeEntryArrayHours($entries, &$maxCols) {
		$maxCols = 0;
		$day_view = array();
		
		if (count($entries) > 0) {
			foreach ($entries as $entry) {
				$hour = (int) date("H", $entry['start']);				
				
				$seconds = $entry['end']-$entry['start'];
				
				if(date("i", $seconds) > 30 || $seconds <= 3600) {
					$rowspan = ceil($seconds/60/60);
				} else {
					$rowspan = floor($seconds/60/60);
				}

				// Check which column to put in
				for($col=0; $col<=$maxCols+1; $col++) {
					$foundCol=true;
					for($row=0; $row<$rowspan; $row++) {
						if(isset($day_view[$hour+$row][$col])) {
							$foundCol = false;
						}
					}
					if($foundCol) {
						$column = $col;
						break 1;
					}
				}
				$maxCols = ($maxCols<$column)?$column:$maxCols;

				$day_view[$hour][$column]['start']['hour'] = date("H",  $entry['start']);
				$day_view[$hour][$column]['start']['min'] = date("i",  $entry['start']);
				$day_view[$hour][$column]['end']['hour'] = date("H",  $entry['end']);
				$day_view[$hour][$column]['end']['min'] = date("i",  $entry['end']);
				$day_view[$hour][$column]['rowspan'] = $rowspan;
				$day_view[$hour][$column]['title'] = makeHTMLURL($entry['title'],	makeURL('calendar', array('mode' => 'view', 'calendarid' => $entry['calendarid'])));
				$day_view[$hour][$column]['description'] = nl2br($entry['description']);
				$day_view[$hour][$column]['calendarid'] = $entry['calendarid'];
				
				global $db;
				$color = $db->selectOneRow('calendar_categories', '*', "`categoryId`='".$entry['categoryId']."'");

				$day_view[$hour][$column]['backgroundcolor'] = (strlen($color['backgroundcolor']) > 0)?$color['backgroundcolor']:"#57FF7E";
				$day_view[$hour][$column]['fontcolor'] = (strlen($color['fontcolor']) > 0)?$color['fontcolor']:"#000000";
				
				for($i=1; $i<$rowspan; $i++) {
					$day_view[$hour+$i][$column]['rowspan'] = 0;
				}
				
			}
		}
		for($i=0; $i<24; $i++) {
			$day_view[$i]['hour'] = $i;
			for($j=0; $j<=$maxCols; $j++) {
				if(!isset($day_view[$i][$j]['rowspan'])) {
					$day_view[$i][$j]['rowspan'] = 1;
				} 
			}
		}
		ksort($day_view, SORT_NUMERIC);
		
		return $day_view;
	}
	
	function makeGrey($number)
	{
		return '<font color="#C0C0C0">'.$number.'</font>';
	}
	
	function makeHighlighted($number)
	{
		return '<font color="#DD0000"><strong>'.$number.'</strong></font>';
	}
	
	function makeToday($number)
	{
		return '<em><u>'.$number.'</u></em>';
	}
	
	function getTodaysBirthdays($today) {
		global $db;
		
		$birthdays = $db->selectList("users", "*", "FROM_UNIXTIME(`birthday`, '%d.%m') = " . date("d.m", $today));

		return $birthdays;
	}
	
	function getCategories() {
		global $db;
		
		return $db->selectList('calendar_categories', '*');
	}
?>