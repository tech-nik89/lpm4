<?php
	
	class Sessions
	{
		function listActive()
		{
			global $db;
			global $config;
			
			$limit = (int)$config->get('login', 'session-time');
			$tbl_users = MYSQL_TABLE_PREFIX . 'users';
			
			$time = time() - $limit;
			
			$sql = "SELECT * FROM `" . $tbl_users . "` WHERE `lastaction`>=" . $time;
			$result = $db->query($sql);
			
			while ($row = mysql_fetch_assoc($result))
			{
				$row['str_lastaction'] = timeElapsed($row['lastaction']);
				$row['url'] = makeURL('profile', array('userid' => $row['userid']));
				$list[] = $row;
			}
			
			return $list;
		}
	}
	
?>