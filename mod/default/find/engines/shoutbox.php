<?php
	
	function shoutbox($s)
	{
		global $db, $lang;
		$tbl = MYSQL_TABLE_PREFIX . 'shoutbox';
		$return = array();
		
		$result = $db->selectList($tbl, "*",   "INSTR(`text`, '" . $s . "') > 0");
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('engines_shoutbox');
			$title = $s;
			$description = $r['text'];
			$url = makeURL('shoutbox');
			$relevance = strcount($r['text'], $s);
			
			$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
		}
		
		return $return;
	}

?>