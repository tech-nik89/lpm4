<?php
	if ($this->isInstalled('article')) {
		$area['title'] = $lang->get('article');
		$area['content'] = '';
		$articles = $db->selectList('article', "*", "1", "`timestamp` DESC", "5");
		if (null != $articles && count($articles) > 0) {
			foreach ($articles as $article) {
				$area['content'] = $area['content'] . "<p>&raquo; ".makeHtmlURL($article['title'], 
					makeURL('article', array('articleid' => $article['articleid'], 'categoryid' => $article['categoryid']))) . 
					' (' . timeElapsed($article['timestamp']) . ')</p>';
			}
		}
		$areas[] = $area;
	}
?>