<?php
	
	function timeLeft($fromTime, $toTime = 0)
	{
		global $lang;
		
		if ($toTime == 0) $toTime = time();
		
		if ((int)$fromTime == 0)
			return $lang->get('never');
		
		$distanceInSeconds = round(abs($toTime - $fromTime));
		$distanceInMinutes = round($distanceInSeconds / 60);
		
		if ( $distanceInMinutes <= 1 ) {
			return $lang->get('less_than_one_minute_left');
		}
		if ( $distanceInMinutes < 45 ) {
			return str_replace("%v", $distanceInMinutes, $lang->get('minutes_left'));
		}
		if ( $distanceInMinutes < 90 ) {
			return $lang->get('one_hour_left');
		}
		if ( $distanceInMinutes < 1440 ) {
			return str_replace("%v", round(floatval($distanceInMinutes) / 60.0), $lang->get('hours_left'));
		}
		if ( $distanceInMinutes < 2880 ) {
			return $lang->get('one_day_left');
		}
		if ( $distanceInMinutes < 20160 ) {
			return str_replace("%v", round(floatval($distanceInMinutes) / 1440.0), $lang->get('days_left'));
		}
		
		/*
		$s = array("%d", "%t");
		$r = array(date("d.m.Y", (int)$fromTime), date("H:i", (int)$fromTime));
		*/
		
		return date("d.m.Y", (int)$fromTime);
	}

	function timeElapsed($fromTime, $toTime = 0)
	{
		global $lang;
		
		if ($toTime == 0) $toTime = time();
		
		if ((int)$fromTime == 0)
			return $lang->get('never');
		
		$distanceInSeconds = round(abs($toTime - $fromTime));
		$distanceInMinutes = round($distanceInSeconds / 60);
		
		if ( $distanceInMinutes <= 1 ) {
			return $lang->get('less_than_one_minute_ago');
		}
		if ( $distanceInMinutes < 45 ) {
			return str_replace("%v", $distanceInMinutes, $lang->get('minutes_ago'));
		}
		if ( $distanceInMinutes < 90 ) {
			return $lang->get('one_hour_ago');
		}
		if ( $distanceInMinutes < 1440 ) {
			return str_replace("%v", round(floatval($distanceInMinutes) / 60.0), $lang->get('hours_ago'));
		}
		if ( $distanceInMinutes < 2880 ) {
			return $lang->get('one_day_ago');
		}
		if ( $distanceInMinutes < 20160 ) {
			return str_replace("%v", round(floatval($distanceInMinutes) / 1440.0), $lang->get('days_ago'));
		}
	
		/*
		$s = array("%d", "%t");
		$r = array(date("d.m.Y", (int)$fromTime), date("H:i", (int)$fromTime));
		*/
		
		return date("d.m.Y", (int)$fromTime);
		
	}
	
	function hasAge($birthday)
	{
		// seconds left since birthday
		if ($birthday > 0) {
			$sec = time() - $birthday;
		}
		else {
			// Timestamp is negative if date is before 01.01.1970,
			// so we subtract both values and take the absolute result.
			$sec = abs($birthday - time());
		}
		
		// minutes left
		$min = $sec / 60;
		
		// hours left
		$hours = $min / 60;
		
		// days left
		$days = $hours / 24;
		
		// years left
		$years = $days / 365;
		
		// age
		$age = floor($years);
		
		return $age;
	}
	
	function durationToString($sec)
	{
		$min = floor($sec / 60);
		$sec = round($sec - $min * 60, 3);
		
		if ($min > 0)
			return $min . " min " . $sec . " sec";
		else
			return $sec . " sec";
		
	}
	
	function formatTime($timestamp) {
		global $lang;
		if ($lang != null)
			$oclock = ' '.$lang->get('o_clock');
		else
			$oclock = '';
		return date("d.m.Y", (int)$timestamp) . ", " . date("H:i", (int)$timestamp).$oclock;
	}
	
?>