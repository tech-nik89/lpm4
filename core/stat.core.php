<?php

	/**
	 * Project: Higher For Hire
	 * File: stat.core.php
	 *
	**/
	
	class Stat
	{
		private $table;
		private $enabled;
		private $duration;
		private $show_bots_enabled;
		
		private $time;
		
		function __construct()
		{
			global $config;
			
			$this->table = MYSQL_TABLE_PREFIX . "stat";
			$this->enabled = (int)$config->get('core', 'stat-enabled');
			$this->duration = (int)$config->get('core', 'stat-duration');
			$this->show_bots_enabled = (int)$config->get('stat', 'show-bots');
			
			$this->time['today']['start'] = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
			$this->time['today']['end'] = strtotime("+24 hours", $this->time['today']['start']);

			$this->time['yesterday']['start'] = strtotime("-24 hours", $this->time['today']['start']);
			$this->time['yesterday']['end'] = strtotime("-24 hours", $this->time['today']['end']);
			
			$this->time['this_month']['start'] = mktime(0, 0, 0, date("m"), 1, date("Y"));
			$this->time['this_month']['end'] = mktime(0, 0, 0, date("m"), date("t"), date("Y"));;
			
			
		}
	
		function setup()
		{
			global $db;
			global $config;
			global $rights;
			
			$sql = "
				CREATE TABLE IF NOT EXISTS `".$this->table."` (
					`userid` INT NOT NULL ,
					`timestamp` INT NOT NULL ,
					`duration` INT NOT NULL ,
					`last_action` INT NOT NULL ,
					`referer` VARCHAR (1023),
					`browseragent` VARCHAR (255),
					`os` VARCHAR (255),
					`ipadress` VARCHAR (16) NOT NULL
					) ENGINE = MYISAM ;
			";
			$db->query($sql);
			
			$config->register('core', 'stat-enabled', 0, 'bool', 'Enables or disables the visitors statistic.');
			$config->register('core', 'stat-duration', 420, 'int', 'Specifies the value in seconds a visitor is counted as one.');
			$config->register('core', 'stat-last-seen-length', 10, 'int', 'Specifies the number of users shown in the last-seen list.');
			
			$config->register('stat', 'show-visitors-this-year', 1, 'bool', 'Enables or diasables the showing of visitors this year.');
			$config->register('stat', 'show-visitors-this-month', 1, 'bool', 'Enables or diasables the showing of visitors this month.');
			$config->register('stat', 'show-browseragent', 1, 'bool', 'Enables or diasables the showing of the browseragent.');
			$config->register('stat', 'show-os', 1, 'bool', 'Enables or diasables the showing of the operationg system.');
			$config->register('stat', 'show-referer', 1, 'bool', 'Enables or diasables the showing of the referer.');
			$config->register('stat', 'show-bots', 0, 'bool', 'Enables or diasables the showing of bots.');
			
			$rights->registerRight('stat', 'manage');
		}
		
		function isEnabled() {
			return $this->enabled;
		}
		
		function update()
		{
			if ($this->enabled == 1)
			{
				if (isset($_COOKIE['hfh_disable_counter']))
					return;
					
				global $db;
				global $login;
				
				$ip = getRemoteAdr();
				$dur = $this->duration;
				
				/* Update usage stat */
				$sql = "SELECT * FROM `".$this->table."` WHERE `userid`=" . $login->currentUserID() . " OR `ipadress`='".$ip."' ORDER BY `timestamp` DESC LIMIT 1;";
				$res = $db->query($sql);
				$r = mysql_fetch_assoc($res);
				
				if ($r['last_action'] + $dur < time())
				{
					$sql = 	"INSERT INTO `".$this->table."`
							(`userid`, `timestamp`, `last_action`, `ipadress`, `referer`, `browseragent`, `os`)
							VALUES
							(" . $login->currentUserID() . ", " . time() . ", " . time() . ", '" . $ip . "', '" . $this->getUserReferer() . "', '" . $this->getUserAgent() . "', '" . $this->getUserOS() . "');";
				} else {
					$sql = "UPDATE `".$this->table."` SET `duration`=" . (time() - $r['timestamp']) . ", `last_action`=" . time() . ", `userid`=" . $login->currentUserID() . " WHERE `timestamp`=" . $r['timestamp'] . " AND `ipadress`='" . $ip . "';";
				}
				
				$db->query($sql);
			}
		}
		
		function getUserReferer() {
			if(isset($_SERVER['HTTP_REFERER']) && !strpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME'])) {
				$refer = trim($_SERVER['HTTP_REFERER']);
				if(strpos($refer, "http://") === 0) {
					$refer = substr($refer, 7);
				}
				if(strpos($refer, "www.") === 0) {
					$refer = substr($refer, 4);
				}
				return $refer;
			}
			return "";
		}
		
		function getUserOS() {
			$OSlist = array(
				'Windows 3.11' => 'Win16',
				'Windows 95' => 'Windows 95|Win95|Windows_95',
				'Windows 98' => 'Windows 98|Win98',
				'Windows 2000' => 'Windows NT 5.0|Windows 2000',
				'Windows XP' => 'Windows NT 5.1|Windows XP',
				'Windows Server 2003' => 'Windows NT 5.2',
				'Windows Vista' => 'Windows NT 6.0',
				'Windows 7' => 'Windows NT 6.1',
				'Windows NT 4.0' => '(Windows NT 4.0|WinNT4.0|WinNT)',
				'Windows ME' => 'Windows ME',
				'Open BSD' => 'OpenBSD',
				'Sun OS' => 'SunOS',
				'Linux' => '(Linux|X11)',
				'Mac OS' => '(Mac_PowerPC|Macintosh)',
				'QNX' => 'QNX',
				'BeOS' => 'BeOS',
				'OS/2' => 'OS\/2',
				'Bot'=>'(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|msnbot|Ask Jeeves\/Teoma|ia_archiver)'
				);

			$agent=$_SERVER['HTTP_USER_AGENT'];
			$userOS = "";
			foreach($OSlist as $curOS=>$string){
				if (preg_match("[".$string."]", $agent)){
					$userOS = $curOS;
					break;
				}
			}
			
			return $userOS;
		}
		
		
		function getUserAgent(){
			$agentList = array(
				'Internet Explorer 9.0' => 'MSIE 9.0',
				'Internet Explorer 8.0' => 'MSIE 8.0',
				'Internet Explorer 7.0' => 'MSIE 7.0',
				'Internet Explorer 6.0' => 'MSIE 6.0',
				'Internet Explorer 5.5' => 'MSIE|msie',
				'Chrome' => 'Chrome',
				'Opera' => 'Opera',
				'Konqueror' => 'Konqueror',
				'Lynx' => 'Lynx',
				'iCab' => 'iCab',
				'Safari' => 'Safari',
				'Webwasher' => 'webwasher',
				'Mozilla' => 'gecko|Mozilla|Firefox',
				'inktomi' => 'inktomi',
				'lycos' => 'lycos',
				'Yahoo' => 'Yahoo',
				'Netscape' => 'Nav|Gold|X11|Netscape',
				'Proxy' => 'wwwoffle|fairad',
				'Bot' =>'googlebot|msnbot|webcrawler|Infoseek|W3C_Validator|W3C-checklink'
			);
		
			$agent=$_SERVER['HTTP_USER_AGENT'];
			$userAgent = "";
			foreach($agentList as $curAgent=>$string){
				if (preg_match("[".$string."]", $agent)){
					$userAgent = $curAgent;
					break;
				}
			}
			
			return $userAgent;
		}
		
		function userOnline()
		{
			global $db;
			return $db->num_rows($this->table, "`last_action`>" . (time() - $this->duration));
		}
		
		function visitorsToday()
		{
			global $db, $config;
			if ($config->get('stat', 'show-bots') == '1')
				return $db->num_rows($this->table, $this->time['today']['end'] . " > `timestamp` AND `timestamp` > " . $this->time['today']['start']);
			else
				return $db->num_rows($this->table, $this->time['today']['end'] . " > `timestamp` AND `timestamp` > " . $this->time['today']['start'] . " AND `os` != 'Bot'");
		}
		
		function visitorsYesterday()
		{
			global $db, $config;
			if ($config->get('stat', 'show-bots') == '1')
				return $db->num_rows($this->table, $this->time['yesterday']['end'] . " > `timestamp` AND `timestamp` > " . $this->time['yesterday']['start']);
			else
				return $db->num_rows($this->table, $this->time['yesterday']['end'] . " > `timestamp` AND `timestamp` > " . $this->time['yesterday']['start'] . " AND `os` != 'Bot'");
		}
		
		function visitorsThisMonth()
		{
			global $db, $config;
			if ($config->get('stat', 'show-bots') == '1')
				return $db->num_rows($this->table, $this->time['this_month']['end'] . " > `timestamp` AND `timestamp` > " . $this->time['this_month']['start']);
			else
				return $db->num_rows($this->table, $this->time['this_month']['end'] . " > `timestamp` AND `timestamp` > " . $this->time['this_month']['start'] . " AND `os` != 'Bot'");
		}
		
		function visitorsOverall()
		{
			global $db, $config;
			if ($config->get('stat', 'show-bots') == '1')
				return $db->num_rows($this->table, 1);
			else
				return $db->num_rows($this->table, "`os` != 'Bot'");
		}
		
		function listOnline()
		{
			global $db;
			$tbl_u = MYSQL_TABLE_PREFIX . 'users';
			$tbl_s = MYSQL_TABLE_PREFIX . 'stat';
			$list = $db->selectList($tbl_u, "*", 
						"`".$tbl_u."`.`lastaction`>" . (time() - $this->duration));
			foreach ($list as $i => $l) {
				$duration = $db->selectOne($tbl_s, "duration", "`userid`=".$l['userid']." AND `timestamp` > ".(time() - $this->duration), "timestamp DESC");
				$list[$i]['duration'] = $duration;
			}
			return $list;
		}
		
		function lastSeen()
		{
			global $db, $config;
			
			$tbl_u = MYSQL_TABLE_PREFIX . 'users';
			
			$limit = (int)$config->get('core', 'stat-last-seen-length');
			$limit = $limit > 0 ? $limit : 10;
			$list = $db->selectList($tbl_u, "*", "`lastaction` > 0", "`lastaction` DESC", $limit);
			
			return $list;
		}
		
		function visitorsPerDay()
		{
			global $db;
			
			// Get first and last day and calculate difference in days
			$sql = "SELECT MIN(`last_action`), MAX(`last_action`) FROM `".$this->table."`;";
			$result = $db->query($sql);
			$row = mysql_fetch_assoc($result);
			$timeElapsed = $row['MAX(`last_action`)'] - $row['MIN(`last_action`)'];
			$days = floor($timeElapsed / 86400.0);
			
			// Get visitors in this timespan
			$visitors = $this->visitorsOverall();
			
			// Calculate visitors per day
			@$visitorsPerDay = round($visitors / $days, 2);
			
			return $visitorsPerDay;
		}
		
		function visitorsTablePerMonth($month, $year) {
			global $db, $config;
			
			$month = (int)$month;
			$year = (int)$year;
			if ($month < 1) $month = 1;
			if ($month > 12) $month = 12;
			
			$m_start = mktime(0, 0, 0, $month, 1, $year);
			$m_days = date("t", $m_start);
			$m_end = mktime(23, 59, 59, $month, $m_days, $year);
			
			$table = array();
			$max = 0;
			
			for ($day = 1; $day <= $m_days; $day++) {
				$start = mktime(0, 0, 0, $month, $day, $year);
				$end = mktime(23, 59, 59, $month, $day, $year);
				if ($config->get('stat', 'show-bots') == '1')
					$sql = "SELECT * FROM `".MYSQL_TABLE_PREFIX."stat` AS `s` WHERE `timestamp` > ".$start." AND `timestamp` < ".$end;
				else
					$sql = "SELECT * FROM `".MYSQL_TABLE_PREFIX."stat` AS `s` WHERE `timestamp` > ".$start." AND `timestamp` < ".$end . " AND `os` != 'Bot'";
				$result = $db->query($sql);
				$value = mysql_num_rows($result);
				if ($value > $max) $max = $value;
				$table['elements'][] = array(
					'value' => $value,
					'day' => $day,
					'weekday' => date("D", $start)
				);
			}
			
			$table['max'] = $max;
			return $table;
		}
		
		function visitorsTablePerYear($year) {
			global $db, $config;
			$year = (int)$year;
			$max = 0;
			$table = array();
			for ($month = 1; $month <= 12; $month++) {
				$start = mktime(0, 0, 0, $month, 1, $year);
				$end = mktime(23, 59, 59, $month, date("t", $start), $year);
				if ($config->get('stat', 'show-bots') == '1')
					$sql = "SELECT * FROM `".MYSQL_TABLE_PREFIX."stat` AS `s` WHERE `timestamp` > ".$start." AND `timestamp` < ".$end;
				else
					$sql = "SELECT * FROM `".MYSQL_TABLE_PREFIX."stat` AS `s` WHERE `timestamp` > ".$start." AND `timestamp` < ".$end . " AND `os` != 'Bot'";
				$result = $db->query($sql);
				$value = mysql_num_rows($result);
				if ($value > $max) $max = $value;
				$table['elements'][] = array(
					'value' => $value,
					'month' => date("M", $start)
				);
			}
			$table['max'] = $max;
			return $table;
		}
		
		function makeOsList($timestamp = 0) {
			global $db, $lang;
			$os_all = $db->selectList($this->table, 'os', "timestamp>=".(int)$timestamp);
			$os_list = array();
			$os_list_final = array();
			foreach($os_all as $os) {
				@$os_list[$os['os']]++;
			}
			// delete the empty referer
			unset($os_list['']);
			// unset Bot if wanted
			if(!$this->show_bots_enabled) {
				unset($os_list['Bot']);
			}
			arsort($os_list, SORT_NUMERIC);
			foreach($os_list as $key => $value) {
				$os_list_final[] = array(
					'value' => $value,
					'name' => $key
				);
			}
			$times = 0;
			$os_count = count($os_list_final);
			if($os_count>9) {
				while($os_count > 9 OR $times == $os_list_final[count($os_list_final)-2]['value']) {
					$times = $os_list_final[$os_count-2]['value'];
					$os_list_final[$os_count-2]['name'] = $lang->get('text_other');
					$os_list_final[$os_count-2]['value'] += $os_list_final[$os_count-1]['value'];
					unset($os_list_final[$os_count-1]);
					$os_count--;
				}
			}
			return $os_list_final;
		}
		
		function makeBrowseragentList($timestamp=0) {
			global $db, $lang;
			$browseragent_all = $db->selectList($this->table, 'browseragent', "timestamp>=".(int)$timestamp);
			$browseragent_list = array();
			$browseragent_list_final = array();
			foreach($browseragent_all as $browseragent) {
				@$browseragent_list[$browseragent['browseragent']]++;
			}
			// delete the empty os
			unset($browseragent_list['']);
			// unset Bot if wanted
			if(!$this->show_bots_enabled) {
				unset($browseragent_list['Bot']);
			}
			arsort($browseragent_list, SORT_NUMERIC);
			foreach($browseragent_list as $key => $value) {
				$browseragent_list_final[] = array(
					'value' => $value,
					'name' => $key
				);
			}
			$times = 0;
			$browser_count = count($browseragent_list_final);
			if($browser_count>9) {
				while($browser_count > 9 OR $times == $browseragent_list_final[count($browseragent_list_final)-2]['value']) {
					$times = $browseragent_list_final[$browser_count-2]['value'];
					$browseragent_list_final[$browser_count-2]['name'] = $lang->get('text_other');
					$browseragent_list_final[$browser_count-2]['value'] += $browseragent_list_final[$browser_count-1]['value'];
					unset($browseragent_list_final[$browser_count-1]);
					$browser_count--;
				}
			}
			return $browseragent_list_final;
		}
		
		function makeRefererList($timestamp=0) {
			global $db, $lang;
			$referer_all = $db->selectList($this->table, 'referer', "timestamp>=".(int)$timestamp);
			$referer_list = array();
			$referer_list_final = array();
			foreach($referer_all as $referer) {
				if(strpos($referer['referer'], "?") !== false) {
					$referer['referer']  = substr($referer['referer'], 0, strpos($referer['referer'], "?"));
				}
				@$referer_list[$referer['referer']]++;
			}
			// delete the empty referer
			unset($referer_list['']);
			arsort($referer_list, SORT_NUMERIC);
			foreach($referer_list as $key => $value) {
				$referer_list_final[] = array(
					'value' => $value,
					'name' => $key
				);
			}
			$times = 0;
			$referer_count = count($referer_list_final);
			if($referer_count>9) {
				while($referer_count > 9 OR $times == $referer_list_final[count($referer_list_final)-2]['value']) {
					$times = $referer_list_final[$referer_count-2]['value'];
					$referer_list_final[$referer_count-2]['name'] = $lang->get('text_other');
					$referer_list_final[$referer_count-2]['value'] += $referer_list_final[$referer_count-1]['value'];
					unset($referer_list_final[$referer_count-1]);
					$referer_count--;
				}
			}
			return $referer_list_final;
		}
		
		function runningSince() {
			global $db;
			return $db->selectOne('stat', 'timestamp', "1", "`timestamp` ASC");
		}
	}
	
?>