<?php
	
	$tbl = MYSQL_TABLE_PREFIX . 'shoutbox';
	$tbl_u = MYSQL_TABLE_PREFIX . 'users';
	
	$lockTime = (int)$config->get('shoutbox', 'lock-time');
	if ($lockTime <= 0) $lockTime = 15;
	
	if (@trim($_POST['text']) != '' && $login->currentUser() !== false) {
		$last_post = $db->selectOneRow($tbl, "*", "`userid`=".$login->currentUserId(), "`timestamp` DESC");
		if ($last_post['timestamp'] + $lockTime < time()) {
			$db->insert($tbl, array('userid', 'timestamp', 'text'),
					array($login->currentUserID(), time(), "'".str_replace("\n", "", trim($_POST['text']))."'"));
		}
	}
	
	$posts = $db->selectList($tbl."`, `".$tbl_u, "*", "`".$tbl_u."`.`userid`=`".$tbl."`.`userid`", "`timestamp` DESC", (int)$config->get('shoutbox', 'posts'));
	if (null != $posts && count($posts) > 0) {
		foreach ($posts as $i => $p) {
			$posts[$i]['timestamp_str'] = timeElapsed($p['timestamp']);
			$posts[$i]['url'] = makeURL('profile', array('userid' => $p['userid']));
			$posts[$i]['text'] = $bbcode->parse($p['text']);
		}
	}
	
	if ($config->get('shoutbox', 'reverse') == 1) {
		$posts = array_reverse($posts);
	}
	
	$smarty->assign('posts', $posts);
	$smarty->display('../boxes/shoutbox/posts.tpl');
	
?>