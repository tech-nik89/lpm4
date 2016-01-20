<?php
	
	$smarty->assign('path', $template_dir."/default.tpl");
	$lang->addModSpecificLocalization($mod);
	
	$tbl = MYSQL_TABLE_PREFIX . 'shoutbox';
	$tbl_u = MYSQL_TABLE_PREFIX . 'users';
	
	$isallowed = $rights->isAllowed($mod, 'manage');
	$smarty->assign('isallowed', $isallowed);
	
	@$page = (int)$_GET['page'];
	if ($page == 0) $page = 1;
	
	$spp = 20;
	$sc = $db->num_rows($tbl, "1");
	@$pages->setValues((int)$_GET['page'], $spp, $sc);
	
	if ($isallowed && isset($_POST['remove'])) {
		$db->delete($tbl, "`shoutid`=".(int)$_POST['shoutid']);
	}
	
	if ($isallowed && isset($_POST['clear'])) {
		$db->delete($tbl, "1");
	}
	
	if ($isallowed && isset($_POST['clearOld'])) {
		// 2592000 = 30 Tage
		$db->delete($tbl, "`timestamp` < " . (time() - 2592000));
	}
	
	$posts = $db->selectList($tbl."`, `".$tbl_u, "*", 
			"`".$tbl_u."`.`userid`=`".$tbl."`.`userid`", "`timestamp` DESC", $pages->currentValue().", ".$spp);
	if (null != $posts && count($posts) > 0) {
		foreach ($posts as $i => $p) {
			$posts[$i]['timestamp_str'] = timeElapsed($p['timestamp']);
			$posts[$i]['url'] = makeURL('profile', array('userid' => $p['userid']));
			$posts[$i]['text'] =  $bbcode->parse($p['text']);
		}
	}
	$smarty->assign('posts', $posts);
	@$smarty->assign('pages', $pages->get($mod));
?>