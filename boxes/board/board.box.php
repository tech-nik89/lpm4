<?php
	
	$lang->addModSpecificLocalization('board');
	
	$tbl_p = MYSQL_TABLE_PREFIX.'post';
	$tbl_t = MYSQL_TABLE_PREFIX.'thread';
	$tbl_b = MYSQL_TABLE_PREFIX.'board';
	$tbl_gu = MYSQL_TABLE_PREFIX.'group_users';
	$tbl_g = MYSQL_TABLE_PREFIX.'groups';
	
	$limit = (int)$config->get('board', 'box-posts');
	$limit = $limit > 0 ? $limit : 5;
	
	$ppp = $config->get('board', 'posts-per-page');
	require_once('./mod/default/board/board.function.php');
	
	if($config->get('board', 'box-thread-once') == 1)
	{
		$sql=$db->query("SELECT posts.userid as userid, posts.timestamp, threads.thread, boards.boardid, threads.threadid, threads.lastpost 
					FROM `".$tbl_t."` AS threads 
					INNER JOIN `".$tbl_p."` AS posts
					ON threads.lastpost=posts.postid
					INNER JOIN `".$tbl_b."` AS boards
					ON threads.boardid=boards.boardid
					LEFT JOIN `".$tbl_g."` AS groups
					ON boards.`assigned_groupid`=groups.groupid
					LEFT JOIN `".$tbl_gu."` AS group_users
					ON boards.`assigned_groupid`=group_users.groupid
					WHERE group_users.userid=".$login->currentUserID()." OR (group_users.userid is NULL AND groups.groupid IS NULL)
					ORDER BY posts.timestamp DESC
					LIMIT $limit;");
	}
	else
	{
		$sql=$db->query("SELECT posts.userid as userid, posts.timestamp, threads.thread, boards.boardid, threads.threadid, threads.lastpost FROM 
					`".$tbl_p."` AS posts 
					INNER JOIN `".$tbl_t."` AS threads 
					ON threads.threadid=posts.threadid
					INNER JOIN `".$tbl_b."` AS boards
					ON threads.boardid=boards.boardid
					LEFT JOIN `".$tbl_g."` AS groups
					ON boards.`assigned_groupid`=groups.groupid
					LEFT JOIN `".$tbl_gu."` AS group_users
					ON boards.`assigned_groupid`=group_users.groupid
					WHERE group_users.userid=".$login->currentUserID()." OR (group_users.userid is NULL AND groups.groupid IS NULL)
					ORDER BY posts.timestamp DESC
					LIMIT $limit;");
	}
	$list=array();
	while($list_entry=mysql_fetch_assoc($sql))
	{
		$list[]=$list_entry;
	}
	
	if (null != $list && count($list) > 0) {
		foreach ($list as $i => $l) {
			$u = $user->getUserById($l['userid']);
			$list[$i]['url'] =	makeURL('board', array('boardid' => $l['boardid'], 'threadid' => $l['threadid'], 'page' => lastPage($l['threadid'], $ppp)), "post".$l['lastpost']);
			$list[$i]['info'] = '(' . makeHTMLURL($u['nickname'], makeURL('profile', array('userid' => $u['userid']))) . ', ' . timeElapsed($l['timestamp']) . ')';
		}
	}
	
	$smarty->assign('box_board_posts', $list);
	$tpl_file = $template_dir."/posts.tpl";
	$visible = true;
	
?>