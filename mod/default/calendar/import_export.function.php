<?php
	
	function ical_export() {
		global $db, $config;
		
		$utc = 'Z';
		
		$br = "\r\n";
		
		$ical = "BEGIN:VCALENDAR".$br;
		$ical .= "VERSION:2.0".$br;
		$ical .= "TZID:".$config->get('core', 'timezone').$br;
		$ical .= "PRODID:LAN Party Manager IV/4.0.0 beta".$br;

		$entries = $db->selectList('calendar', "*", "`visible`=2");
		
		$dtstamp = gmdate('Ymd').'T'. gmdate('His') . "Z";
		$counter = 0;
		
		foreach($entries as $entry) {

			$dtstart = gmdate('Ymd', (int)$entry['start']).'T'. gmdate('His', (int)$entry['start']) . $utc; 
			$dtend = gmdate('Ymd', (int)$entry['end']).'T'. gmdate('His', (int)$entry['end']) . $utc;

			$ical .= "BEGIN:VEVENT".$br;
			$ical .= "UID:" . md5(uniqid(mt_rand(), true)). $counter++ .$br;
			$ical .= "DTSTART:" . $dtstart .$br;
			$ical .= "DTEND:" . $dtend .$br;
			$ical .= "DTSTAMP:" . $dtstamp . $br;
			$ical .= "SUMMARY:" . $entry['title'] .$br;
			$ical .= "DESCRIPTION:" . str_replace(",", " ", preg_replace('/\n|\r/', "\\n", $entry['description'])) .$br;
			$ical .= "END:VEVENT".$br;
		}

		$birthdays = $db->selectList(MYSQL_TABLE_PREFIX."users", "`nickname`, `lastname`, `prename`, `birthday`", '`birthday` > 0');
	
		foreach($birthdays as $birthday) {
			$ical .= "BEGIN:VEVENT" . $br;
			$ical .= "DTSTART;VALUE=DATE:" . date("Ymd", $birthday['birthday']). $br;
			$ical .= "SUMMARY:" . $birthday['prename'] . " " . $birthday['lastname']  . $br;
			$ical .= "UID:" . md5(uniqid(mt_rand(), true)). $counter++ . $br;
			$ical .= "DTSTAMP:" . $dtstamp . $br;
			$ical .= "TRANSP:TRANSPARENT" . $br;
			$ical .= "CATEGORIES:ANNIVERSARY,BIRTHDAY" . $br;
			$ical .= "RRULE:FREQ=YEARLY" . $br;
			$ical .= "END:VEVENT" . $br;
		}

		$ical .= "END:VCALENDAR";
		return $ical;
	}
	
	// Calendar ical import //
	function ical_import($ical) {
		
		global $db;
		global $login;
		
		$in_event = false;
		$event = array();
		
		foreach ($ical as $val) {
			$value = trim($val);
			if ($value == "BEGIN:VEVENT") {
				$in_event = true;
				$event = array();
			}
			if ($value == "END:VEVENT") {
				$db->insert('calendar', array('userid', 'start', 'end', 'title', 'description', 'visible'),
					array($login->currentUserId(), $event['start'], $event['end'], "'".$event['title']."'", "'".$event['description']."'", 2));
				
				$in_event = false;
			}
			if ($in_event) {
				$pos = strpos($value, ":");
				$left = substr($value, 0, $pos);
				$right = substr($value, $pos + 1);
				switch ($left) {
					case "DTSTART":
						$event['start'] = icalTimeToUnixTime($right);
						break;
					case "DTEND":
						$event['end'] = icalTimeToUnixTime($right);
						break;
					case "SUMMARY":
						$event['title'] = $right;
						break;
					case "DESCRIPTION":
						$event['description'] = $right;
						break;
				}
			}
		}
	}
	
	function icalTimeToUnixTime($time) {
		$year = substr($time, 0, 4);
		$month = substr($time, 4, 2);
		$day = substr($time, 6, 2);
		$hour_min = addZeros(substr($time, 9, 4) + date("O"), 4);
		$hour = (int)substr($hour_min, 0, 2);
		$min = substr($hour_min, 2, 2);
		$sec = 0;
		return mktime($hour, $min, $sec, $month, $day, $year);
	}
	
	function addZeros($val, $length) {
		while (strlen($val) < $length) {
			$val = "0". $val;
		}
		return $val;
	}
	
?>