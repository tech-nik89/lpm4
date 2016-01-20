<?php
	
	function content($s)
	{
		global $db, $lang;
		$tbl = MYSQL_TABLE_PREFIX . 'content';
		$return = array();
		
		$result = $db->queryToList("select c.key, c.title, c.text, c.box_content
			from (
			select `key`, max(version) as latest
			from ".$tbl."
			group by `key`
			)
			as x
			inner join ".$tbl." as c
			on c.key = x.key and c.version = x.latest
			where title like '%".$s."%'
			or text like '%".$s."%'
			or box_content like '%".$s."%'");
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('engines_content');
			$title = cutString($r['title']);
			$description = $r['text'];
			$url = makeURL($r['key']);
			$relevance = strcount($r['text'].' '.$r['title'].' '.$r['title'].' '.$r['title'], $s);
			
			$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
		}
		
		return $return;
	}

?>