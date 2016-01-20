<?php
	
	function outbox($s)
	{
		global $db;
		global $lang;
		global $login;
		$tbl = MYSQL_TABLE_PREFIX . 'outbox';
		$return = array();
		
		$result = $db->selectList($tbl, "*",   "(INSTR(`subject`, '" . $s . "') > 0 OR
												INSTR(`message`, '" . $s . "') > 0) AND
								  				`senderid`=".$login->currentUserID());
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('engines_outbox');
			$title = $r['subject'];
			$description = $r['message'];
			$url = makeURL('pmbox', array('mode' => 'outbox', 'pmid' => $r['pmid']));
			$relevance = strcount($r['subject'].$r['message'], $s);
			
			@$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
		}
		
		return $return;
	}

?>