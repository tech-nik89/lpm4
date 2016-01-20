<?php
	
	function media($s)
	{
		global $db, $lang;
		$tbl_cat = MYSQL_TABLE_PREFIX . 'media_categories';
		$tbl_downloads = MYSQL_TABLE_PREFIX . 'media_downloads';
		$tbl_images = MYSQL_TABLE_PREFIX . 'media_images';
		$tbl_movies = MYSQL_TABLE_PREFIX . 'media_movies';
		$return = array();
		
		$result = $db->selectList($tbl_cat, "*",   "INSTR(`name`, '" . $s . "') > 0");
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('category');
			$title = $r['name'];
			$description = '';
			$url = makeURL('media', array('categoryid' => $r['categoryid']));
			$relevance = strcount($r['name'], $s);
			
			$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
		}
		
		$result = $db->selectList($tbl_downloads, "*",   "INSTR(`name`, '" . $s . "') > 0 OR INSTR(`description`, '" . $s . "') > 0");
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('download');
			$title = $r['name'];
			$description = $r['description'];
			$url = makeURL('media', array('categoryid' => $r['categoryid'], 'downloadid' => $r['downloadid']));
			$relevance = strcount($r['name'].$r['description'], $s);
			
			$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
		}
		
		$result = $db->selectList($tbl_movies, "*",   "INSTR(`name`, '" . $s . "') > 0 OR INSTR(`description`, '" . $s . "') > 0");
		
		if (count($result) > 0)
		foreach ($result as $i => $r)
		{
			$engine = $lang->get('movie');
			$title = $r['name'];
			$description = $r['description'];
			$url = makeURL('media', array('categoryid' => $r['categoryid'], 'movieid' => $r['movieid']));
			$relevance = strcount($r['name'].$r['description'], $s);
			
			$return[] = array('engine' => $engine,
							  'title' => $title,
							  'description' => $description,
							  'url' => $url,
							  'relevance' => $relevance);
		}
		
		return $return;
	}

?>