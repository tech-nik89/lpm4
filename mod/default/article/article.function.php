<?php
	
	global $ts;
	$ts['1week'] = time() - 604800;
	$ts['2weeks'] = $ts['1week'] - 604800;
	
	function GetCategories() {
		global $db;
		$mod = 'article';
		$categories = $db->selectList('article_categories', '*', '`parentid` = 0', '`title` ASC');
		foreach ($categories as $i => $category) {
			$categories[$i]['url'] = makeURL($mod, array('categoryid' => $category['categoryid']));
			$subcategories = $db->selectList('article_categories', '*', '`parentid` = ' . $category['categoryid'], '`title` ASC');
			foreach ($subcategories as $j => $subcategory) {
				$subcategories[$j]['url'] = makeURL($mod, array('categoryid' => $subcategory['categoryid']));
				$subcategories[$j]['articles'] = $db->num_rows('article', '`categoryid`='.$subcategory['categoryid'].' AND `published`=1');
			}
			$categories[$i]['subcategories'] = $subcategories;
		}
		return $categories;
	}
	
	function GetArticlesThisWeek() {
		global $db, $ts, $comments;
		$articles = $db->selectList('article', '*', '`timestamp` > '.$ts['1week'].' AND `published`=1', '`timestamp` DESC');
		foreach ($articles as $i => $article) {
			$articles[$i]['url'] = makeURL('article', array('categoryid' => $article['categoryid'], 'articleid' => $article['articleid']));
			$articles[$i]['comments'] = $comments->count('article', $article['articleid']);
		}
		return $articles;
	}
	
	function GetArticlesLastWeek() {
		global $db, $ts, $comments;
		$articles = $db->selectList('article', '*', '`timestamp` > '.$ts['2weeks'].' AND `timestamp` < '.$ts['1week'].' AND `published`=1', '`timestamp` DESC');
		foreach ($articles as $i => $article) {
			$articles[$i]['url'] = makeURL('article', array('categoryid' => $article['categoryid'], 'articleid' => $article['articleid']));
			$articles[$i]['comments'] = $comments->count('article', $article['articleid']);
		}
		return $articles;
	}
	
	function GetArticlesOlder() {
		global $db, $ts, $comments;
		$articles = $db->selectList('article', '*', '`timestamp` < '.$ts['2weeks'].' AND `published`=1', '`timestamp` DESC');
		foreach ($articles as $i => $article) {
			$articles[$i]['url'] = makeURL('article', array('categoryid' => $article['categoryid'], 'articleid' => $article['articleid']));
			$articles[$i]['comments'] = $comments->count('article', $article['articleid']);
		}
		return $articles;
	}
	
?>