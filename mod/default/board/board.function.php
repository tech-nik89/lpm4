<?php
	
	// board functions #################### //
	
	function boardNewOrder()
	{
		global $db;
		$tbl_board = MYSQL_TABLE_PREFIX . 'board';
		
		$sql = "SELECT `order` FROM `" . $tbl_board . "` ORDER BY `order` DESC LIMIT 1;";
		$result = $db->query($sql);
		$row = mysql_fetch_assoc($result);
		return $row['order'] + 1;
	}
	
	function boardAdd($board, $description = '', $assigned_groupid = 0)
	{
		global $db;
		$tbl_board = MYSQL_TABLE_PREFIX . 'board';
		
		if (trim($board) != '')
		{
			$b = secureMySQL($board);
			$d = secureMySQL($description);
			
			$sql = "INSERT INTO `" . $tbl_board . "`
					(`board`, `order`, `description`, `assigned_groupid`)
					VALUES
					('" . $b . "', " . boardNewOrder() . ", '" . $d . "', " . (int)$assigned_groupid . ");";

			$db->query($sql);
			
			return mysql_insert_id();
		}
		
	}
	
	function boardEdit($boardid, $board, $order, $description = '', $assigned_groupid = 0)
	{
		global $db;
		$tbl_board = MYSQL_TABLE_PREFIX . 'board';
		
		if (trim($board) != '')
		{
			$b = secureMySQL($board);
			$o = (int)$order;
			$d = secureMySQL($description);
			
			$sql = "UPDATE `" . $tbl_board . "`
					SET
					`board`='" . $b . "', `order` = " . $o . ", `description`='" . $d . "', `assigned_groupid`=" . (int)$assigned_groupid . "
					WHERE `boardid`=" . (int)$boardid . ";";
					
			$db->query($sql);
			
		}
	}
	
	function boardRemove($boardid)
	{
		global $db;
		$tbl_board = MYSQL_TABLE_PREFIX . 'board';
		

		$sql = "DELETE FROM `" . $tbl_board . "` WHERE `boardid`=" . (int)$boardid . ";";
		$db->query($sql);
	
	}
	
	
	function boardLastPost($boardid)
	{
		global $db;
		global $config;
	
		$ppp = $config->get('board', 'posts-per-page');
		$thread = $db->selectOneRow(MYSQL_TABLE_PREFIX."thread", "*", "`boardid`=".(int)$boardid, "`lastpost` DESC", "1");
		if(isset($thread['threadid'])) {
			$post = $db->selectOneRow('post', 'postid', "`threadid`=".$thread['threadid'], "`postid` DESC", "1");
			return makeLastPost($boardid, $thread['threadid'], $post['postid'], $ppp);
		} else {
			return makeLastPost($boardid, 0, 0, $ppp);
		}
	}
	
	// thread functions #################### //
	
	function threadAdd($boardid, $thread)
	{
		global $db;
		global $login;
		$tbl_thread = MYSQL_TABLE_PREFIX . 'thread';
		
		$b = (int)$boardid;
		$t = secureMySQL($thread);
		
		$sql = "INSERT INTO `" . $tbl_thread . "`
				(`boardid`, `thread`, `userid`)
				VALUES
				(" . $b . ", '" . $t . "', " . $login->currentUserID() . ");";
		
		$db->query($sql);
		
		return mysql_insert_id();
		
	}
	
	function threadEdit($threadid, $thread)
	{
		global $db;
		$tbl_thread = MYSQL_TABLE_PREFIX . 'thread';
		
		$t = secureMySQL($thread);
		$id = (int)$threadid;
		$sql = "UPDATE `" . $tbl_thread . "` SET `thread` ='" . $t . "' WHERE `threadid`=" . $id . ";";
		$db->query($sql);
	}
	
	function threadRemove($threadid)
	{
		global $db;
		$tbl_thread = MYSQL_TABLE_PREFIX . 'thread';
		$tbl_post = MYSQL_TABLE_PREFIX . 'post';
		
		$id = (int)$threadid;
		$sql = "DELETE FROM `" . $tbl_thread . "` WHERE `threadid`=" . $id . ";";
		$db->query($sql);
		$sql = "DELETE FROM `" . $tbl_post . "` WHERE `threadid`=" . $id . ";";
		$db->query($sql);
		
	}
	
	function threadMove($threadid, $boardid)
	{
		global $db;
		$tbl_thread = MYSQL_TABLE_PREFIX . 'thread';
		
		$sql = "UPDATE `" . $tbl_thread . "` SET `boardid`=" . (int)$boardid . " WHERE `threadid`=" . (int)$threadid . ";";
		$db->query($sql);
		
	}
	
	// post functions ##################### //
	function postAdd($threadid, $post, $attachments = '')
	{
		global $db;
		global $login;
		$tbl_thread = MYSQL_TABLE_PREFIX . 'thread';
		$tbl_post = MYSQL_TABLE_PREFIX . 'post';
		$tbl_newposts = MYSQL_TABLE_PREFIX . 'newposts';
		
		$p = trim(secureMySQL($post));
		$a = trim(secureMySQL($attachments));
		
		if ($p != '')
		{
			$sql = "INSERT INTO `" . $tbl_post . "`
					(`threadid`, `post`, `userid`, `timestamp`, `attachments`)
					VALUES
					(" . (int)$threadid . ", '" . $p . "', " . $login->currentUserID() . ", " . time() . ", '" . $a . "');";
			
			$db->query($sql);
			
			$id = mysql_insert_id();
			
			$sql = "UPDATE `" . $tbl_thread . "` SET `lastpost`=" . $id . " WHERE `threadid`=" . (int)$threadid . ";";
			$db->query($sql);
			
			$db->delete($tbl_newposts, "`threadid`=".(int)$threadid);
			threadRead($threadid);
			
			threadSubscriptionNotify($threadid);
			
			return $id;
		}
	}
	
	function postRemove($postid)
	{
		global $db;
		$tbl_post = MYSQL_TABLE_PREFIX . 'post';
		
		$sql = "DELETE FROM `" . $tbl_post . "` WHERE `postid`=" . $postid . ";";
		$db->query($sql);
		
	}
	
	function postEdit($postid, $post)
	{
		global $db;
		$tbl_post = MYSQL_TABLE_PREFIX . 'post';
		global $login;
	
		$sql = "UPDATE `" . $tbl_post . "` SET
				`post`='" . secureMySQL($post) . "' 
				WHERE `postid`=" . (int)$postid . ";";
				
		$db->query($sql);
		
	
		
	}
	
	// Count functions ########################## //
	
	function numThreads($boardid)
	{
		$tbl_thread = MYSQL_TABLE_PREFIX . 'thread';
		global $db;
		
		return $db->num_rows($tbl_thread, "`boardid`=" . (int)$boardid . ";");
	}
	
	function numPostsInThread($threadid)
	{
		$tbl_post = MYSQL_TABLE_PREFIX . 'post';
		global $db;
		
		return $db->num_rows($tbl_post, "`threadid`=" . (int)$threadid . ";");
	}
	
	function numPostsInBoard($boardid)
	{
		$tbl_post = MYSQL_TABLE_PREFIX . 'post';
		$tbl_thread = MYSQL_TABLE_PREFIX . 'thread';
		global $db;
		
		$sum = 0;
		
		$tl = $db->selectList($tbl_thread, "*", "`boardid`=" . (int)$boardid);
		
		if (count($tl)>0)
			foreach ($tl as $t)
				$sum += $db->num_rows($tbl_post, "`threadid`=" . $t['threadid']);
				
		return $sum;
		
	}
	
	// List functions ########################### //
	
	function boardList()
	{
		global $db, $login, $rights;
		$tbl_board = MYSQL_TABLE_PREFIX . 'board';
		$list = array();
		
		if ($login->currentUser() !== false)
		{
			$groups = $rights->getGroups($login->currentUserID());
			if (count($groups) > 0)
				foreach ($groups as $g)
					$w[] = "`assigned_groupid` = ".$g['groupid'];
			$w[] = "`assigned_groupid` = 0";
			$where = implode(" OR ", $w);
			$sql = "SELECT * FROM `" . $tbl_board . "` WHERE " . $where . " ORDER BY `order` ASC;";
		} else {
			$sql = "SELECT * FROM `" . $tbl_board . "` WHERE `assigned_groupid` = 0 ORDER BY `order` ASC;";
		}
		$result = $db->query($sql);
		
		while ($row = mysql_fetch_assoc($result))
		{
			$row['board'] = stripslashes($row['board']);
			$row['threads'] = numThreads($row['boardid']);
			$row['posts'] = numPostsInBoard($row['boardid']);
			$row['url'] = makeURL('board', array('boardid' => $row['boardid']));
			$row['lastpost'] = boardLastPost($row['boardid']);
			$row['newposts'] = makeNewPostsIcon(boardNewPosts($row['boardid']), false);
			$list[] = $row;
		}
		
		return $list;
	}
	
	function threadList($boardid, $page)
	{
		global $db;
		$tbl_thread = MYSQL_TABLE_PREFIX . 'thread';
		$tbl_users = MYSQL_TABLE_PREFIX . 'users';
		global $config;
		global $lang;
		
		if ($page == 0) $page = 1;
		$tpp = $config->get('board', 'threads-per-page');
		$ppp = $config->get('board', 'posts-per-page');
		$limit = ($page - 1) * $tpp;
		
		$sql = "SELECT * FROM `" . $tbl_thread . "`, `" . $tbl_users . "` WHERE `" . $tbl_thread . "`.`boardid`=" . (int)$boardid . " AND `" . $tbl_thread . "`.`userid`=`" . $tbl_users . "`.`userid` ORDER BY `" . $tbl_thread . "`.`sticky` DESC, `" . $tbl_thread . "`.`lastpost` DESC LIMIT " . $limit . ", " . $tpp . ";";
		$result = $db->query($sql);
		
		while ($row = mysql_fetch_assoc($result))
		{
			if ($row['sticky'] == 1)
				$row['thread'] = makeHTMLBold($lang->get('sticky') . ":") . " " . $row['thread'];
			
			$post = $db->selectOneRow('post', 'postid', "`threadid`=".$row['threadid'], "`postid` DESC", "1");
			$row['last_post'] = makeLastPost($boardid, $row['threadid'], $post['postid'], $ppp);
			$row['thread'] = stripslashes($row['thread']);
			$row['answers'] = numPostsInThread($row['threadid'])-1;
			$row['url'] = makeURL('board', array('boardid' => $_GET['boardid'], 'threadid' => $row['threadid']));
			
			$row['newposts'] = makeNewPostsIcon(threadNewPosts($row['threadid']), userPosts($row['threadid']));
			
			$list[] = $row;
		}
		
		return $list;
	}
	
	function postList($threadid, $page)
	{
		global $db;
		$tbl_post = MYSQL_TABLE_PREFIX . 'post';
		global $config;
		global $mod;
		global $user;
		global $avatar;
		global $bbcode;
		global $rights;
		global $login;
		
		if ($page == 0) $page = 1;
		$ppp = $config->get('board', 'posts-per-page');
		$limit = ($page - 1) * $ppp;
		
		$sql = "SELECT * FROM `" . $tbl_post . "` WHERE `threadid`=" . (int)$threadid . " ORDER BY `timestamp` ASC LIMIT " . $limit . ", " . $ppp . ";";
		$result = $db->query($sql);
		
		$i = $limit;
		
		while ($row = mysql_fetch_assoc($result))
		{
			$i++;
			$row['number'] = $i;
			
			$u = $user->getUserByID($row['userid']);
			
			$row['post'] = $bbcode->parse(strip_tags(($row['post'])));
			// $row['post'] = convertLinks($row['post']);
			$row['time'] = timeElapsed($row['timestamp']);
			$row['avatar'] = $avatar->makeAvatar($u['avatar']);
			$row['author'] = makeHTMLURL($u['nickname'], makeURL('profile', array('userid' => $row['userid'])));
			
			$row['quote_url'] = makeURL('board', array('boardid' => $_GET['boardid'], 'threadid' => $threadid, 'quoteid' => $row['postid'], 'page' => lastPage($threadid, $ppp)), "addPost");
			$row['edit_url'] = makeURL('board', array('boardid' => $_GET['boardid'], 'threadid' => $threadid, 'postid' => $row['postid'], 'mode' => 'edit'));
			$row['remove_url'] = makeURL('board', array('boardid' => $_GET['boardid'], 'threadid' => $threadid, 'postid' => $row['postid'], 'mode' => 'remove'));
			
			$row['posts'] = getUserPostCount($row['userid']);
			
			/*
			unset($att);
			$attachments = explode(",", $row['attachments']);
			if (count($attachments) > 0)
				foreach ($attachments as $a)
				{
					if ($a != '')
						$att[] = $a;
				}
			
			$row['attachments'] = $att;
			*/
			
			$row['usergroups'] = $rights->getGroups($row['userid'], 1);
			
			$list[] = $row;
		}
		
		return $list;
	}
	
	function makeLastPost($boardid, $threadid, $postid, $ppp)
	{
		global $lang;
		global $db;
		global $user;
		global $debug;
		
		if ($postid > 0)
		{
		
			$debug->add('ppp', $ppp);
			
			$page = lastPage($threadid, $ppp);
			
			$post = $db->selectOneRow(MYSQL_TABLE_PREFIX . 'post', "*", "`postid`=".(int)$postid);
			$u = $user->getUserByID($post['userid']);
			
			$s[] = "%a"; $r[] = $u['nickname'];
			$s[] = "%t"; $r[] = timeElapsed($post['timestamp']);
			
			return makeHTMLURL(str_replace($s, $r, $lang->get('last_post_by')), makeURL('board', array('boardid' => $boardid, 'threadid' => $threadid, 'page' => $page), "post".$postid));
		
		} else {
			
			return $lang->get('last_post_none');
			
		}
	}
	
	function lastPage($threadid, $ppp)
	{
		global $debug;
		
		$count = numPostsInThread($threadid);
		$debug->add('board', 'posts in thread '.$threadid . ': '.$count);
		$page = floor(($count - 1) / $ppp) + 1;
		
		return $page;
	}
	
	function getQuote($postid)
	{
		global $db;
		
		$sql = "SELECT * FROM `" . MYSQL_TABLE_PREFIX . "post` WHERE `postid`=" . (int)$postid . " LIMIT 1;";
		$result = $db->query($sql);
		
		$row = mysql_fetch_assoc($result);
		$row['post'] = stripslashes($row['post']);
		return $row;
	}
	
	function incHits($threadid)
	{
		global $db;
		global $login;
		$threadstarter = $db->selectOne('thread', 'userid', "`threadid`=".(int)$threadid);
		if ($threadstarter != $login->currentUserId()) {
			$sql = "UPDATE `".MYSQL_TABLE_PREFIX."thread` SET `hits`=`hits`+1 WHERE `threadid`=".(int)$threadid.";";
			$db->query($sql);
		}
	}
	
	// Sticky
	function threadStick($threadid, $mode)
	{
		global $db;
		$sql = "UPDATE `" . MYSQL_TABLE_PREFIX . "thread` SET `sticky`=".(int)$mode." WHERE `threadid`=".(int)$threadid.";";
		$db->query($sql);
	}
	
	
	function threadClose($threadid, $mode)
	{
		global $db;
		$sql = "UPDATE `" . MYSQL_TABLE_PREFIX . "thread` SET `closed`=".(int)$mode." WHERE `threadid`=".(int)$threadid.";";
		$db->query($sql);
	}
	
	// New posts
	function threadRead($threadid)
	{
		global $db;
		global $login;
		$tbl = MYSQL_TABLE_PREFIX . "newposts";
		
		if ($login->currentUser() !== false) {
			if ($db->num_rows($tbl, "`userid`=".$login->currentUserID()." AND `threadid`=".(int)$threadid) == 0)
				$db->insert($tbl, array("userid", "threadid"), array($login->currentUserID(), (int)$threadid));
		}
		
	}
	
	function threadNewPosts($threadid)
	{
		global $db;
		global $login;
		$tbl = MYSQL_TABLE_PREFIX . "newposts";
		
		if ($login->currentUser() === false)
			return false;
		
		if ($db->num_rows($tbl, "`userid`=".$login->currentUserID()." AND `threadid`=".(int)$threadid) == 0)
			return true;
		else
			return false;
	}
	
	function boardNewPosts($boardid)
	{
		global $db;
		global $login;
		$tbl_t = MYSQL_TABLE_PREFIX . "thread";
		
		if ($login->currentUser() === false)
			return false;
		
		$threads = $db->selectList($tbl_t, "*", "`boardid`=".(int)$boardid, "`lastpost` DESC");
		if (count($threads) > 0)
			foreach ($threads as $thread)
			{
				if (threadNewPosts($thread['threadid']))
					return true;
			}
			
		return false;
	}
	
	function userPosts($threadid)
	{
		global $db;
		global $login;
		$tbl = MYSQL_TABLE_PREFIX . "post";
		
		if ($db->num_rows($tbl, "`userid`=".$login->currentUserID(). " AND `threadid`=".(int)$threadid) > 0)
			return true;
		else
			return false;
	}
	
	function makeNewPostsIcon($newposts, $userposts)
	{
		if ($newposts)
		{
			if ($userposts)
			{
				return "thread_new_inv.png";
			} else {
				return "thread_new.png";
			}
		} else {
			if ($userposts)
			{
				return "thread_old_inv.png";
			} else {
				return "thread_old.png";
			}
		}
	}
	
	function markAsRead($boardid = 0)
	{
		global $db;
		$tbl_thread = MYSQL_TABLE_PREFIX.'thread';
		
		if ($boardid == 0)
			$list = $db->selectList($tbl_thread, "*");
		else
			$list = $db->selectList($tbl_thread, "*", "`boardid`=".(int)$boardid);
		
		if (count($list) > 0)
			foreach ($list as $thread)
				threadRead($thread['threadid']);
		
	}
	
	function getUserPostCount($userid)
	{
		global $db;
		$tbl = MYSQL_TABLE_PREFIX . 'post';
		return $db->num_rows($tbl, "`userid`=".(int)$userid);
	}
	
	// Thread scubscriptions /////////////////////////////////////////////
	
	function threadSubscribe($threadid)
	{
		global $db, $login, $notify, $lang;
		if ($login->currentUser() !== false) {
			if (!threadSubscribed($threadid)) {
				$db->insert('thread_abo', 
					array('threadid', 'userid'),
					array((int)$threadid, $login->currentUserId())
					);
				$notify->add($lang->get('thread_subscribe'), $lang->get('thread_subscribe_done'));
			}
		}
	}
	
	function threadSubscribed($threadid)
	{
		global $login, $db;
		if ($db->num_rows('thread_abo', 
			"`threadid`=".(int)$threadid." AND `userid`=".$login->currentUserId()) == 0)
			return false;
		else
			return true;
	}
	
	function threadUnSubscribe($threadid)
	{
		global $db;
		global $login;
		if ($login->currentUserId() !== false) {
			$db->delete('thread_abo', "`userid`=".$login->currentUserId()." AND `threadid`=".(int)$threadid);
		}
	}
	
	function threadSubscriptionNotify($threadid)
	{
		global $login, $db, $eMail, $lang, $config;
		if ($config->get('board', 'enable-subscriptions') != '1')
			return;
		
		$thread = $db->selectOneRow('thread', "*", "`threadid`=" . (int)$threadid);
		$eMail->setVar('thread', $thread['thread']);
		$eMail->setVar('url', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['HOST_REQUEST_URI']."/".
			makeURL('board', array('boardid' => $thread['boardid'], 'threadid' => $thread['threadid'])));
		
		$subscribers = $db->selectList('thread_abo`, `'.MYSQL_TABLE_PREFIX.'users', 
			"*", "`threadid`=".(int)$threadid." AND `".MYSQL_TABLE_PREFIX."users`.`userid` = `".
			MYSQL_TABLE_PREFIX."thread_abo`.`userid`");
		
		foreach ($subscribers as $subscriber) {
			
			if ($subscriber['userid'] != $login->currentUserId()) {
				$eMail->send($lang->get('thread_subscription_subject'),
							$lang->get('thread_subscription_text'),
							$subscriber['email']);
			}
		}
	}
	
?>