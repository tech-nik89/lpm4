<?php
	
	function calendar($s)
	{
		global $db;
		global $lang;
		global $login;
		global $rights;
		$return = array();
		
		$tbl = MYSQL_TABLE_PREFIX . 'calendar';
		$isallowed = $rights->isAllowed('calendar', 'manage');
		
		if ($isallowed)
		{
			$result = $db->selectList($tbl, "*", "INSTR(`title`, '".$s."') > 0 OR
												INSTR(`description`, '".$s."') > 0");
		}
		else
		{
			if ($login->currentUser() === false)
			{
				$result = $db->selectList($tbl, "*", "(INSTR(`title`, '".$s."') > 0 OR
												INSTR(`description`, '".$s."') > 0 )
					AND ( (`visible`=2)  OR  (`visible`=0 AND `userid`=".$login->currentUserID().") )");
			}
			else
			{
				$result = $db->selectList($tbl, "*", "(INSTR(`title`, '".$s."') > 0 OR
												INSTR(`description`, '".$s."') > 0 ) 
					AND ( (`visible`=2)  OR  (`visible`=0 AND `userid`=".$login->currentUserID().") OR (`visible`=1) )");
			}
		}
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('engines_calendar');
			$title = $r['title'];
			$description = $r['description'];
			$url = makeURL('calendar', array('mode' => 'view', 'calendarid' => $r['calendarid'], 'day' => $r['start']));
			$relevance = strcount($r['description'], $s);
			
			$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
		}
		
		return $return;
	}
	
?>