<?php
	
	function users($s)
	{
		global $db, $lang;
		$tbl = MYSQL_TABLE_PREFIX . 'users';
		$return = array();
		
		$result = $db->selectList($tbl, "*",   "INSTR(`nickname`, '" . $s . "') > 0 OR
												INSTR(`prename`, '" . $s . "') > 0");
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('engines_user');
			$title = $r['nickname'];
			$description = $r['prename'] . " '" . $r['nickname'] . "' " . stripLastName($r['lastname']);
			$url =  makeURL('profile', array('userid' => $r['userid']));
			$relevance = strcount($r['prename'].$r['nickname'], $s);
			
			$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
		}
		
		return $return;
	}

?>