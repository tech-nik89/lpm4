<?php
	
	function news($s)
	{
		global $db, $lang;
		$tbl = MYSQL_TABLE_PREFIX . 'news';
		$return = array();
		
		$result = $db->selectList($tbl, "*",   "INSTR(`title`, '" . $s . "') > 0 OR
												INSTR(`text`, '" . $s . "') > 0");
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('engines_news');
			$title = cutString($r['title']);
			$description = $r['text'];
			$url = makeURL('news', array('newsid' => $r['newsid']));
			$relevance = strcount($r['text'].$r['title'], $s);
			
			$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
		}
		
		return $return;
	}

?>