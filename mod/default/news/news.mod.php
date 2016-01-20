<?php
	
	$news = array();
	
	// mysql tables
	$table = MYSQL_TABLE_PREFIX . 'news';
	$tbl_users = MYSQL_TABLE_PREFIX . 'users';
	
	// include language file
	$lang->addModSpecificLocalization($mod);
	
	global $current_language, $lng;
	$lng = secureMySQL($current_language);
	
	$smarty->assign('languages', array_merge(array('' => ''), $lang->listLanguages()));
	
	// add root breadcrumb
	$breadcrumbs->addElement($lang->get('news'), makeURL($mod));
	
	// get functions
	require_once($mod_dir."/news.function.php");
	
	// check if user is allowed to manage this modul
	$isallowed = $rights->isAllowed($mod, 'manage');
	$debug->add('news', 'user isallowed to manage: ' . (int)$isallowed);
	
	// get configuration
	$maximumpreviewlength = $config->get($mod, 'preview-char-length');
	$limit = (int)$config->get($mod, 'news-per-page');
		
	$newsid = isset($_GET['newsid']) ? (int)$_GET['newsid'] : 0;

	
	// add submenu if is allowed
	if ($isallowed)
	{
		$menu->addSubElement($mod, $lang->get('news_add'), 'add');
		$menu->addSubElement($mod, $lang->get('news_edit'), 'edit');
	}
	
	$menu->addSubElement($mod, $lang->get('archive'), 'archive');
	if ($config->get($mod, 'rss') == '1') {
		$menu->addSubElement($mod, 'RSS-Feed', 'rss');
	}
		
	switch ($_GET['mode'])
	{
		case 'rss':
			$breadcrumbs->addElement('RSS-Feed', makeURL($mod, array('mode' => 'rss')));
			$smarty->assign('path', $template_dir."/rss.tpl");
			$smarty->assign('rss_url', getSelfURL()."/media/rss/news.rss");
			break;
		
		case 'beamer':
		  $news = $db->selectOneRow($table, "*", "`newsid`=".$newsid);
		  $news['text'] = str_replace("\n", "<br />", ($news['text']));
		  $smarty->assign('news', $news);
		  $smarty->assign('path', $template_dir."/beamer.tpl");
		  break;
		
		case 'archive':
			
			function makeNews(&$comments, &$db, $table, $tbl_users, $start, $end, $title)
			{
				global $lng;
				$news = '';
				
				$result = $db->query("SELECT * FROM `" . $table . "`, `" . $tbl_users . "`
												WHERE `" . $table . "`.`userid` = `" . $tbl_users . "`.`userid` 
												AND `timestamp`>" . $start . " 
												AND `timestamp`<" . $end . " 
												AND (`language` = '".secureMySQL($lng)."' OR `language` = '')
												AND (`domainid` = 0 OR `domainid` = ".getCurrentDomainIndex().")
												ORDER BY `timestamp` DESC;");
				
				while ($row = mysql_fetch_assoc($result))
				{
					$row['time'] = timeElapsed($row['timestamp']);
					$row['url'] = makeURL('news', array('newsid' => $row['newsid']));
					$row['text'] = stripslashes($row['text']);
					$row['title'] = stripslashes($row['title']);
					$row['user_url'] = makeURL('profile', array('userid' => $row['userid']));
					$row['comments'] = $comments->count('news', $row['newsid']);
					$news[] = $row;
				}
				
				return array('title' => $title, 'news' => $news, 'news_count' => mysql_num_rows($result));
			}
			
			$breadcrumbs->addElement($lang->get('archive'), makeURL($mod, array('mode' => 'archive')));
			$smarty->assign('path', $template_dir . "/archive.tpl");
			
			// make timestamps
			$today['start'] = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
			$today['end'] = mktime(23, 59, 59, date("n"), date("j"), date("Y"));
			$sections[] = makeNews($comments, $db, $table, $tbl_users, $today['start'], $today['end'], $lang->get('today'));
			
			$yesterday['start'] = strtotime("yesterday");
			$yesterday['end'] = $yesterday['start'] + 60 * 60 * 24;
			$sections[] = makeNews($comments, $db, $table, $tbl_users, $yesterday['start'], $yesterday['end'], $lang->get('yesterday'));
			
			$thisweek['start'] = strtotime("Last Monday");
			$thisweek['end'] = strtotime("+1 Week", $thisweek['start']);
			$sections[] = makeNews($comments, $db, $table, $tbl_users, $thisweek['start'], $thisweek['end'], $lang->get('thisweek'));
			
			$lastweek['start'] = strtotime("-1 Week", $thisweek['start']);
			$lastweek['end'] = $thisweek['start'];
			$sections[] = makeNews($comments, $db, $table, $tbl_users, $lastweek['start'], $lastweek['end'], $lang->get('lastweek'));
			
			$thismonth['start'] = strtotime("-1 Month", strtotime("Last Month"));
			$thismonth['end'] = $lastweek['end'];
			$sections[] = makeNews($comments, $db, $table, $tbl_users, $thismonth['start'], $thismonth['end'], $lang->get('thismonth'));
			
			$lastmonth['start'] = strtotime("-1 Month", strtotime("Last Month"));
			$lastmonth['end'] = strtotime("+1 Month", $lastmonth['start']);
			$sections[] = makeNews($comments, $db, $table, $tbl_users, $lastmonth['start'], $lastmonth['end'], $lang->get('lastmonth'));
			
			$thisyear['start'] = strtotime("Last Year");
			$thisyear['end'] = $lastmonth['start'];
			$sections[] = makeNews($comments, $db, $table, $tbl_users, $thisyear['start'], $thisyear['end'], $lang->get('thisyear'));
			
			$before['start'] = 0;
			$before['end'] = $thisyear['start'];
			$sections[] = makeNews($comments, $db, $table, $tbl_users, $before['start'], $before['end'], $lang->get('before'));
			
			$smarty->assign('sections', $sections);
			
			break;
		
		case 'edit':
			
			if ($isallowed)
			{
				
				if ($newsid == 0)
				{
					$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('mode' => 'edit')));
					$smarty->assign('path', $template_dir . "/edit_list.tpl");
				
					$result = $db->query("SELECT * FROM `" . $table . "`, `" . $tbl_users . "`
											WHERE `" . $table . "`.`userid` = `" . $tbl_users . "`.`userid`
											ORDER BY `timestamp` DESC;");
					
					while ($row = mysql_fetch_assoc($result))
					{
						if (isset($_POST['remove']) && @(int)$_POST['remove_'.$row['newsid']] == 1)
							$db->delete($table, "`newsid`=" . $row['newsid']);
						else
						{
							$row['time'] = timeElapsed($row['timestamp']);
							$row['edit'] = makeURL($mod, array('mode' => 'edit', 'newsid' => $row['newsid']));
							$row['text'] = ($row['text']);
							$row['title'] = ($row['title']);
							$news[] = $row;
						}
					}
					
					$smarty->assign('news', $news);
					
				} else {
					
					if (isset($_POST['save']))
					{
						$db->update($table, 
							"`title`='" . secureMySQL($_POST['title']) . "', 
							`text`='" . secureMySQL($_POST['text']) . "', 
							`preview`='".secureMySQL(makePreview($_POST['text'], $maximumpreviewlength))."', 
							`edit_count`=`edit_count`+1,
							`language`='".secureMySQL($_POST['language'])."',
							`domainid`=".(int)$_POST['domainid']
							, "`newsid`=" . $newsid
						);
						$notify->add($lang->get('news'), $lang->get('news_edit_done'));
						require_once($mod_dir."/news.rss.php");
					}
					
					$news = $db->selectOneRow($table, "*", "`newsid`=".$newsid);
					$breadcrumbs->addElement($news['title'], makeURL($mod, array('newsid' => $newsid)));
					$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('newsid' => $newsid, 'mode' => 'edit')));
					
					$smarty->assign('news', $news);
					$smarty->assign('path', $template_dir . "/edit.tpl");
					
					$smarty->assign('domains', getDomainList());
					
				}
				
				break;
			}
			
		case 'add':
			
			if ($isallowed)
			{
				
				$smarty->assign('domains', getDomainList());
				
				$added = false;
				$smarty->assign('groups', $rights->getAllGroups());
				if (isset($_POST['save']))
				{
				
					$title = $_POST['title'];
					$text  = $_POST['text'];
					
					if ($text != '' && $title != '')
					{
						$db->insert($table, array('title', 'text', 'preview', 'userid', 'timestamp', 'language', 'domainid'),
											array("'".$title."'", "'".$text."'", "'".makePreview($text, 18)."'", $login->currentUserID(), time(), "'".$_POST['language']."'", (int)$_POST['domainid']));
						$notify->add($lang->get('news'), $lang->get('news_added'));
						
						// Circular mail
						@$pm = $_POST['send_pm'] == 'true';
						@$mail = $_POST['send_mail'] == 'true';
						@$copy = $_POST['copy_to_me'] == 'true';
						@$send_to = (int)$_POST['send_to'];
						
						if ($send_to > -1) {
							$subject = trim(replaceHtmlEntities($_POST['title']));
							$message = trim($_POST['text']);
							
							if ($message != '' && $subject != '') {
								if ($send_to == 0) {
									$list = $user->listUsers();
								}
								else {
									$list = $rights->getGroupMembers($send_to);
								}
								$counter = 0;
								
								if (null != $list && count($list) > 0) {
									foreach ($list as $u) {
										if ($copy || $u['userid'] != $me['userid']) {
											$counter++;
											if ($pm) {
												sendPm($login->currentUserId(), $u['userid'], $subject, $message);
											}
											if ($mail) {
												$eMail->send($subject, str_replace("\n", "<br />", replaceHtmlEntities($message)), $u['email']);
											}
										}
									}
								}
								$notify->add($lang->get('news'), str_replace('%c', $counter, $lang->get('send_done')));
							}
						}
						
						$added = true;
						require_once($mod_dir."/news.rss.php");
					} else
					
						$notify->add($lang->get('news'), $lang->get('news_error_fill_all'));
					
				} 
				
				if (!$added)
				{
					// add add breadcrumb
					$breadcrumbs->addElement($lang->get('news_add'), makeURL($mod, array('mode' => 'add')));
					
					// assign template path
					$smarty->assign('path', $template_dir . "/add.tpl");
					
					break;
					
				}
			}
			
		default:
			
			$smarty->assign('hide_author', $config->get('news', 'hide-author'));
			
			if ($newsid == 0)
			{
			
				$smarty->assign('path', $template_dir . "/overview.tpl");
				
				$result = $db->query("SELECT * FROM `" . $table . "`, `" . $tbl_users . "`
										WHERE `" . $table . "`.`userid` = `" . $tbl_users . "`.`userid`
										AND (`language` = '".secureMySQL($lng)."' OR `language`='')
										AND (`domainid` = 0 OR `domainid` = ".getCurrentDomainIndex().")
										ORDER BY `timestamp` DESC LIMIT " . $limit . ";");
				while ($row = mysql_fetch_assoc($result))
				{
					$author = $user->getUserByID($row['userid']);
					
					$row['url'] = makeURL($mod, array('newsid' => $row['newsid']));
					$row['time'] = $config->get('news', 'hide-time') != '1' ? timeElapsed($row['timestamp']) : date('d.m.Y', $row['timestamp']);
					$row['title'] = $bbcode->parse($row['title']);
					$row['text'] = $bbcode->parse($row['text']);
					$row['comments'] = $comments->count($mod, $row['newsid']);
					$row['user_url'] = makeURL('profile', array('userid' => $author['userid']));
					
					$news[] = $row;
				}
			
			} else {
				
				if ($login->currentUser() !== false)
				{
					$smarty->assign('loggedin', true);
					if (isset($_POST['add']))
						$comments->add($mod, $login->currentUserID(), $_POST['comment'], $newsid);
				}
				$news = $db->selectOneRow($table, "*", "`newsid`=".$newsid);
				$breadcrumbs->addElement($news['title'], makeURL($mod, array('newsid' => $newsid)));
				
				$author = $user->getUserByID($news['userid']);
				
				$news['time'] = $config->get('news', 'hide-time') != '1' ? timeElapsed($news['timestamp']) : date('d.m.Y', $news['timestamp']);
				$news['title'] = $bbcode->parse($news['title']);
				$news['text'] = $bbcode->parse($news['text']);
				$news['nickname'] = $author['nickname'];
				$news['user_url'] = makeURL('profile', array('userid' => $author['userid']));
				
				$smarty->assign('path', $template_dir . "/news.tpl");
				
				$smarty->assign('comments', $comments->get($mod, $newsid));
			}
			
			$smarty->assign('news', $news);
			
	}
		
?>