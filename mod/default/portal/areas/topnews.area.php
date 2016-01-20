<?php
	if ($this->isInstalled('news')) {
		$topnews = $db->selectOneRow(MYSQL_TABLE_PREFIX.'news', "*", 
			"`domainid` = 0 OR `domainid` = ".getCurrentDomainIndex(), "`timestamp` DESC");
		
		$area['title'] = $lang->get('topnews') . ': ' . $topnews['title'];
		$area['content'] = cutString($bbcode->parse($topnews['preview']), 400, makeURL('news', array('newsid' => $topnews['newsid'])));
		$areas[] = $area;
	}
?>