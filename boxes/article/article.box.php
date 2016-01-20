<?php
	global $comments;
	$lang->addModSpecificLocalization('calendar');
	
	$tpl_file = $template_dir."/default.tpl";
	$tbl = 'article';
	$list = $db->selectList($tbl, '*', '`published`=1', "`timestamp` DESC", 5);
	
	if (count($list) > 0)
		foreach ($list as $i => $l) {
			$list[$i]['category'] = $db->selectOne('article_categories', 'title', "`categoryid`=".$l['categoryid']);
			$list[$i]['url'] = makeURL('article', array('articleid' =>  $l['articleid'], 'categoryid' => $l['categoryid']));
			$list[$i]['comments'] = $comments->count('article', $l['articleid']);
		}
	
	$smarty->assign('article_box_entries', $list);
	$visible = true;
?>