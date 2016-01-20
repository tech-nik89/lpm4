<?php
	if ($this->isInstalled('news')) {
		$area['title'] = $lang->get('news');
		$area['content'] = '';
		$topnews = $db->selectList(MYSQL_TABLE_PREFIX.'news', "*"
			, "`domainid` = 0 OR `domainid` = ".getCurrentDomainIndex(), "`timestamp` DESC", "5");
		if (null != $topnews && count($topnews) > 0) {
			foreach ($topnews as $top) {
				$area['content'] = $area['content'] . "<p>&raquo; ".makeHtmlURL($top['title'], 
					makeURL('news', array('newsid' => $top['newsid']))) . 
					' (' . timeElapsed($top['timestamp']) . ')</p>';
			}
		}
		$areas[] = $area;
	}
?>