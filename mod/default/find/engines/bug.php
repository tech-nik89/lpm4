<?php
	
	function bug($s)
	{
		global $db;
		global $lang;
		global $login;
		$tbl = MYSQL_TABLE_PREFIX . 'bugtracker_issues';
		$return = array();
		
		$result = $db->selectList($tbl, "*",   "(INSTR(`summary`, '" . $s . "') > 0 OR
												INSTR(`description`, '" . $s . "') > 0)");
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('engines_bug');
			$title = $r['summary'];
			$description = $r['description'];
			$url = makeURL('bug', array('issueid' => $r['issueid']));
			$relevance = strcount($r['summary'].$r['description'], $s);
			
			@$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
		}
		
		return $return;
	}

?>