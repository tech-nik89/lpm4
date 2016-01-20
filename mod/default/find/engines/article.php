<?php
	
	function article($s)
	{
		global $db, $lang;
		$tbl = MYSQL_TABLE_PREFIX . 'article';
		$return = array();
		
		$result = $db->selectList($tbl, "*",   "INSTR(`title`, '" . $s . "') > 0 OR
												INSTR(`text`, '" . $s . "') > 0");
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('engines_article');
			$title = $r['title'];
			$description = $r['preview'];
			$url = makeURL('article', array('articleid' => $r['articleid']));
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