<?php
	
	function inbox($s)
	{
		global $db;
		global $lang;
		global $login;
		$return = array();
		
		$tbl = MYSQL_TABLE_PREFIX . 'inbox';
		
		$result = $db->selectList($tbl, "*",   "(INSTR(`subject`, '" . $s . "') > 0 OR
												INSTR(`message`, '" . $s . "') > 0) AND
								  				`recieverid`=".$login->currentUserID());
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('engines_inbox');
			$title = $r['subject'];
			$description = $r['message'];
			$url = makeURL('pmbox', array('mode' => 'inbox', 'pmid' => $r['pmid']));
			$relevance = strcount($r['message'].$r['subject'], $s);
			
			@$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
		}
		
		return $return;
	}

?>