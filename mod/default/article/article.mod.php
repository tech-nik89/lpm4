<?php
	
	require_once($mod_dir."/article.function.php");
	
	$lang->addModSpecificLocalization($mod);
	@$articleid = (int)$_GET['articleid'];
	@$categoryid = (int)$_GET['categoryid'];
	@$mode = $_GET['mode'];
	$breadcrumbs->addElement($lang->get('article'), makeURL($mod));
	$categories = array();
	
	if ($rights->isAllowed($mod, 'publish')) {
		$menu->addSubElement($mod, $lang->get('write'), 'write');
		$menu->addSubElement($mod, $lang->get('none_published_articles'), 'publish');
	}
	if ($rights->isAllowed($mod, 'manage')) {
		$menu->addSubElement($mod, $lang->get('categories'), 'categories');
	}
	
	switch ($mode) {
		case 'publish':
			if ($rights->isAllowed($mod, 'publish')) {
				$breadcrumbs->addElement($lang->get('none_published_articles'), makeURL($mod, array('mode' => 'publish')));
				if (isset($_POST['publish'])) {
					$artid = (int)$_POST['articleid'];
					$db->update('article', '`published`=1, `timestamp`='.time(), '`articleid`='.$artid);
					$notify->add($lang->get('article'), $lang->get('published_done'));
				}
				$smarty->assign('path', $template_dir."/none_published.tpl");
				$art = $db->selectList('article', '*', '`published` != 1');
				foreach ($art as $i => $a) {
					$art[$i]['url'] = makeURL($mod, array('categoryid' => $a['categoryid'], 'articleid' => $a['articleid']));
				}
				$articles['none_published'] = $art;
				$smarty->assign('articles', $articles);
				break;
			}
		case 'delete':
			if ($rights->isAllowed($mod, 'manage')) {
				if (isset($_POST['yes'])) {
					$db->delete('article', "`articleid`=".$articleid);
					$notify->add($lang->get('article'), $lang->get('delete_done'));
					redirect(makeURL($mod));
				}
				$breadcrumbs->addElement($lang->get('delete'), makeURL($mod, array('mode' => 'delete', 'articleid' => $articleid)));
				$smarty->assign('path', $template_dir."/delete.tpl");
				$smarty->assign('no_url', makeURL($mod, array('mode' => 'edit', 'articleid' => $articleid)));
				break;
			}
		case 'edit':
			if ($rights->isAllowed($mod, 'publish')) {
				$categories = GetCategories();
				
				$menu->addSubElement($mod, $lang->get('delete'), 'delete', array('articleid' => $articleid));
				
				if (isset($_POST['save'])) {
					$article['title'] = trim($_POST['title']);
					$article['preview'] = trim($_POST['preview']);
					$article['text'] = trim($_POST['text']);
					$article['categoryid'] = (int)$_POST['categoryid'];
					$article['published'] = (int)$_POST['published'];
					if ($article['title'] != '' && $article['preview'] != '' && $article['text'] != '') {
						$db->update('article', 
							"`title`='".secureMySQL($article['title'])."', 
							`preview`='".secureMySQL($article['preview'])."', 
							`text`='".mysql_real_escape_string($article['text'])."', 
							`categoryid`=".$article['categoryid'].",
							`published`=".$article['published'], 
							"`articleid`=".$articleid
						);
						$notify->add($lang->get('article'), $lang->get('changes_saved'));
					}
					else {
						$notify->add($lang->get('article'), $lang->get('fill_all_fields'));
					}
				}
				
				$smarty->assign('path', $template_dir."/addedit.tpl");
				$article = $db->selectOneRow('article', '*', '`articleid`='.$articleid);
				$smarty->assign('article', $article);
				
				$category = $db->selectOneRow('article_categories', '*', '`categoryid`='.$article['categoryid']);
				$parentcategory = $db->selectOneRow('article_categories', '*', '`categoryid`='.$category['parentid']);
				$breadcrumbs->addElement($parentcategory['title'], makeURL($mod, array('categoryid' => $parentcategory['categoryid'])));
				$breadcrumbs->addElement($category['title'], makeURL($mod, array('categoryid' => $article['categoryid'])));
				$breadcrumbs->addElement($article['title'], makeURL($mod, array('categoryid' => $article['categoryid'], 'articleid' => $articleid)));
				$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('categoryid' => $article['categoryid'], 'articleid' => $articleid, 'mode' => 'edit')));
				
				break;
			}
		
		case 'write':
			if ($rights->isAllowed($mod, 'publish')) {
				$categories = GetCategories();
				$smarty->assign('path', $template_dir."/addedit.tpl");
				if (isset($_POST['save'])) {
					$article['title'] = trim($_POST['title']);
					$article['preview'] = trim($_POST['preview']);
					$article['text'] = ($_POST['text']);
					$article['categoryid'] = (int)$_POST['categoryid'];
					@$article['published'] = (int)$_POST['published'];
					if ($article['title'] != '' && $article['preview'] != '' && $article['text'] != '') {
						$db->query("INSERT INTO `".MYSQL_TABLE_PREFIX."article` 
						(categoryid, authorid, timestamp, title, preview, `text`, `published`)
						VALUES
						(".$article['categoryid'].", ".$login->currentUserId().", ".time().", '".secureMySQL($article['title'])."', '".secureMySQL($article['preview'])."', '".mysql_real_escape_string($article['text'])."', ".$article['published'].");");
						
						$notify->add($lang->get('article'), $lang->get('write_done'));
					}
					else {
						$notify->add($lang->get('article'), $lang->get('fill_all_fields'));
					}
					$smarty->assign('article', $article);
				}
			}
			break;
		
		case 'categories':
			if ($rights->isAllowed($mod, 'manage')) {
				$breadcrumbs->addElement($lang->get('categories'), makeURL($mod, array('mode' => 'categories')));
				$categories = GetCategories();
				$smarty->assign('path', $template_dir."/categories.tpl");
				if (isset($_POST['save'])) {
					$all_categories = $db->selectList('article_categories', '*', '1', '`title` ASC');
					if (trim($_POST['title_new']) != '') {
						$db->insert('article_categories',
							array('parentid', 'title', 'description'),
							array((int)$_POST['parent_new'], "'".$_POST['title_new']."'", "'".$_POST['description_new']."'")
						);
					}
					foreach ($all_categories as $category) {
						if (@$_POST['delete_'.$category['categoryid']] == '1') {
							$db->delete('article_categories', "`categoryid`=".$category['categoryid']);
						}
						else {
							$db->update('article_categories', "`title`='".secureMySQL($_POST['title_'.$category['categoryid']])."', `description`='".secureMySQL($_POST['description_'.$category['categoryid']])."', `parentid`=".(int)$_POST['parent_'.$category['categoryid']], "`categoryid`=".$category['categoryid']);
						}
					}
					$categories = GetCategories();
				}
				break;
			}
		
		default:
			if ($articleid > 0) {
				$smarty->assign('path', $template_dir."/article.tpl");
				
				$article = $db->selectOneRow('article', '*', "`articleid`=".$articleid);
				// $article['text'] = $bbcode->parse($article['text']);
				$article['author'] = $user->getUserById($article['authorid']);
				
				$category = $db->selectOneRow('article_categories', '*', '`categoryid`='.$article['categoryid']);
				$parentcategory = $db->selectOneRow('article_categories', '*', '`categoryid`='.$category['parentid']);
				$breadcrumbs->addElement($parentcategory['title'], makeURL($mod, array('categoryid' => $parentcategory['categoryid'])));
				$breadcrumbs->addElement($category['title'], makeURL($mod, array('categoryid' => $article['categoryid'])));
				$breadcrumbs->addElement($article['title'], makeURL($mod, array('categoryid' => $article['categoryid'], 'articleid' => $articleid)));

				$smarty->assign('article', $article);

				if ($login->currentUser() !== false) {
					$smarty->assign('loggedin', true);
					if (isset($_POST['add'])) {
						$comments->add($mod, $login->currentUserID(), $_POST['comment'], $articleid);
					}
				}
				$smarty->assign('comments', $comments->get($mod, $articleid));
				
				if ($rights->isAllowed($mod, 'publish')) {
					$menu->addSubElement($mod, $lang->get('edit'), 'edit', array('articleid' => $articleid));
				}
			}
			else {
				if ($categoryid == 0) {
					$smarty->assign('path', $template_dir."/default.tpl");
					$categories = GetCategories();
					$articles['this_week'] = GetArticlesThisWeek();
					$articles['last_week'] = GetArticlesLastWeek();
					$articles['older'] = GetArticlesOlder();
					$smarty->assign('articles', $articles);
				}
				else {
					$category = $db->selectOneRow('article_categories', '*', '`categoryid`='.$categoryid);
					
					if ($category['parentid'] > 0) {
						$smarty->assign('path', $template_dir."/subcategory.tpl");
						$articles = $db->selectList('article', '*', "`categoryid`=".$categoryid." AND `published`=1", "`timestamp` DESC");
						foreach ($articles as $i => $article) {
							$articles[$i]['url'] = makeURL($mod, array('categoryid' => $categoryid, 'articleid' => $article['articleid']));
							$articles[$i]['comments'] = $comments->count('article', $article['articleid']);
						}
						$smarty->assign('articles', $articles);
					}
					else {
						$smarty->assign('path', $template_dir."/category.tpl");
						$subcategories = $db->selectList('article_categories', '*', '`parentid`='.$categoryid);
						foreach ($subcategories as $i => $cat) {
							$subcategories[$i]['url'] = makeURL($mod, array('categoryid' => $cat['categoryid']));
						}
						$smarty->assign('subcategories', $subcategories);
					}
					
					$parentcategory = $db->selectOneRow('article_categories', '*', '`categoryid`='.$category['parentid']);
					$breadcrumbs->addElement($parentcategory['title'], makeURL($mod, array('categoryid' => $parentcategory['categoryid'])));
					$breadcrumbs->addElement($category['title'], makeURL($mod, array('categoryid' => $categoryid)));
					
					$smarty->assign('category', $category);
				}
			}
	}
	
	$smarty->assign('categories', $categories);
?>