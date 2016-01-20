<?php
	
	global $current_language;
	
	$lang->addModSpecificLocalization($mod);
	$isAllowed = $rights->isAllowed($mod, 'manage');
	
	if ('manage' == @$_GET['mode'] && $isAllowed) {
		$breadcrumbs->addElement('Tag-Cloud', makeURL($mod));
		
		$smarty->assign('languages', array_merge(array('' => ''), $lang->listLanguages()));
		
		$menu->addSubElement($mod, $lang->get('back'), '');
		$smarty->assign('path', $template_dir.'/manage.tpl');
		
		if (isset($_GET['delete']) && @(int)$_GET['tagid'] > 0) {
			$db->delete('tagcloud', '`tagid`='.(int)$_GET['tagid']);
		}
		
		if (isset($_POST['save'])) {
			@$tagid = (int)trim($_POST['tagid']);
			$weight = (int)trim($_POST['weight']);
			$title = trim($_POST['title']);
			$url = trim($_POST['url']);
			$language = trim($_POST['language']);
			@$domainid = (int)$_POST['domainid'];
			
			if ($title != '' && $url != '') {
				if ($weight > 100)
					$weight = 100;
					
				if ($weight < 0)
					$weight = 0;
				
				$weight = $weight + 10;
				
				if (@$_POST['action'] == 'add') {
					$db->insert('tagcloud',
						array('title', 'url', 'weight', 'language', 'domainid'),
						array("'".$title."'", "'".$url."'", $weight, "'".$language."'", $domainid)
					);
				}
				else {
					$db->update('tagcloud',
						"`title`='".secureMySQL($title)."',
						`url`='".secureMySQL($url)."',
						`weight`=".$weight.",
						`language`='".secureMySQL($language)."',
						`domainid`=".$domainid,
						'`tagid`='.$tagid
					);
				}
			}
		}
		
		if (isset($_POST['save_preferences'])) {
			$lng = strip_tags($_POST['text_language']);
			$config->set($mod, 'top-text-'.$lng, $_POST['toptext']);
			$config->set($mod, 'header-'.$lng, $_POST['headertext']);
			@$config->set($mod, 'justify', (int)$_POST['justify']);
		}
		
		$smarty->assign('toptext', $config->get($mod, 'top-text'));
		$smarty->assign('headertext', $config->get($mod, 'header'));
		$smarty->assign('justify', $config->get($mod, 'justify'));
		
		$cloudtags = $db->selectList('tagcloud', '*', "1", '`title` ASC');
		$smarty->assign('tags', $cloudtags);
		
	}
	else {
		if ($isAllowed) {
			$menu->addSubElement($mod, $lang->get('edit_tags'), 'manage');
		}
		
		$toptext = $config->get($mod, 'top-text-'.$current_language);
		$headertext = $config->get($mod, 'header-'.$current_language);
		
		$smarty->assign('toptext', $toptext);
		$breadcrumbs->addElement($headertext, '');
		
		$smarty->assign('justify', $config->get($mod, 'justify'));
		
		$r['min'] = 25;		$r['max'] = 120;
		$g['min'] = 25;		$g['max'] = 206;
		$b['min'] = 112;	$b['max'] = 250;
		
		$cloudtags = $db->selectList('tagcloud', '*', "(`language`='' OR `language`='".$current_language."') AND (`domainid` = 0 OR `domainid` = ".@(int)getCurrentDomainIndex().")", '`title` ASC');
		
		// Colorfy cloud tags
		for($i = 0; $i < count($cloudtags); $i++) {
			
			$percent = (100 - $cloudtags[$i]['weight']) / 100;
			
			$r['diff'] = $r['max'] - $r['min'];
			$r['value'] = ($r['diff'] * $percent + $r['min']);
			
			$g['diff'] = $g['max'] - $g['min'];
			$g['value'] = ($g['diff'] * $percent + $g['min']);
			
			$b['diff'] = $b['max'] - $b['min'];
			$b['value'] = ($b['diff'] * $percent + $b['min']);
			
			$r_hex = dechex($r['value']);
			$g_hex = dechex($g['value']);
			$b_hex = dechex($b['value']);
		
			$cloudtags[$i]['color'] = '#'.$r_hex.$g_hex.$b_hex;
		}
		$smarty->assign('tags', $cloudtags);

		$smarty->assign('path', $template_dir.'/default.tpl');
	}
?>