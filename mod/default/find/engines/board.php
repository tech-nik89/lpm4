<?php
	
	function board($s)
	{
		global $db, $lang;
		$return = array();
		
		$tbl = MYSQL_TABLE_PREFIX . 'post';
		$tbl_thread = MYSQL_TABLE_PREFIX . 'thread';
		
		$result = $db->selectList($tbl, "*",   "INSTR(`post`, '" . $s . "') > 0");
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$thread = $db->selectOneRow($tbl_thread, "*", "`threadid`=" . $r['threadid']);
			
			$engine = $lang->get('thread');
			$title = $thread['thread'];
			$description = $r['post'];
			$url = makeURL('board', array('boardid' => $thread['boardid'], 'threadid' => $r['threadid']));
			$relevance = strcount($r['post'], $s);
			
			$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
			
		}
		
		return $return;
	}

?>