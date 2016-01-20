<?php
	
	$lang->addModSpecificLocalization('news');
	global $current_language;
	
	$limit = (int)$config->get('news', 'news-box-entries');
	if ($limit == 0)
		$limit = 5;
		
	$tpl_file = $template_dir."/default.tpl";
	$tbl = 'news';
	$list = $db->selectList($tbl, '*', "(`language`='".secureMySQL($current_language)."' OR `language`='') AND (`domainid` = 0 OR `domainid` = ".getCurrentDomainIndex().")", "`timestamp` DESC", $limit);
	
	if (count($list) > 0)
		foreach ($list as $i => $l) {
			$list[$i]['url'] = makeURL('news', array('newsid' =>  $l['newsid']));
			$list[$i]['timestamp_str'] = timeElapsed($l['timestamp']);
		}
	
	$smarty->assign('news_box_entries', $list);
	$visible = true;
	
?>