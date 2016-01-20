<?php
	
	// declare tables
	$tbl_board = MYSQL_TABLE_PREFIX . "board";
	$tbl_thread = MYSQL_TABLE_PREFIX . "thread";
	$tbl_post = MYSQL_TABLE_PREFIX . "post";
	
	// check if user is allowed to manage
	if ($rights->isAllowed($mod, 'manage'))
		$isallowed = true; else $isallowed = false;
	
	if ($config->get($mod, 'enable-subscriptions') == '1')
		$subenabled = true;
	else
		$subenabled = false;
	
	$smarty->assign('hide_number_of_posts', $config->get('board', 'disable-number-of-posts'));
	$smarty->assign('isallowed', $isallowed);
	
	$lang->addModSpecificLocalization($mod);
	$breadcrumbs->addElement($lang->get('board'), makeURL($mod));
	
	include($mod_dir . "/board.function.php");
	
	$boardid = isset($_GET['boardid']) ? (int)$_GET['boardid'] : 0;
	$threadid = isset($_GET['threadid']) ? (int)$_GET['threadid'] : 0;
	$postid = isset($_GET['postid']) ? (int)$_GET['postid'] : 0;
	$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
	
	if ($mode == 'mark_as_read')
		markAsRead($boardid);
	
	if ($boardid == 0)
	{
		
		switch ($mode)
		{
			case 'board_manage':
				
				if ($isallowed)
				{
					
					$breadcrumbs->addElement($lang->get('board_manage'), makeURL($mod, array('mode' => 'board_manage')));
					
					$groups = $rights->getAllGroups();
					$smarty->assign('groups', $groups);
					
					if (isset($_POST['save']))
					{
						$add = trim($_POST['board_new']);
						$d = trim($_POST['description_new']);
						
						if ($add != '')
							boardAdd($add, $d, $_POST['assigned_groupid_new']);
						
						$bl = boardList();
						
						if (count($bl) > 0)
						foreach ($bl as $b)
						{
							if (@$_POST['remove_'.$b['boardid']] == "1")
								@boardRemove($b['boardid']);
							else
								@boardEdit($b['boardid'], $_POST['board_'.$b['boardid']], $_POST['order_'.$b['boardid']], $_POST['description_'.$b['boardid']], $_POST['assigned_groupid_'.$b['boardid']]);
						}
					}
					
					$bl = boardList();
					$smarty->assign('path', $template_dir . "/board_manage.tpl");
					$smarty->assign('bl', $bl);
					
					break;
					
				}
				
			case 'subscriptions':
					
				if ($login->currentUser() === false && $subenabled)
					break;
				
				$breadcrumbs->addElement($lang->get('thread_subscriptions'),
					makeURL($mod, array('mode' => 'subscriptions')));
				
				if (isset($_POST['delete'])) {
					threadUnSubscribe($_POST['threadid']);
				}
				
				$subscriptions = $db->selectList('thread_abo`, `'.MYSQL_TABLE_PREFIX.'thread', "*", 
						"`".MYSQL_TABLE_PREFIX."thread_abo`.`userid`=".$login->currentUserId()."
						AND `".MYSQL_TABLE_PREFIX."thread_abo`.`threadid`=
							`".MYSQL_TABLE_PREFIX."thread`.`threadid`");
				
				foreach ($subscriptions as $key => $val) {
					$subscriptions[$key]['url'] = makeURL($mod, 
						array('boardid' => $val['boardid'], 'threadid' => $val['threadid']));
				}
				
				$smarty->assign('path', $template_dir."/subscriptions.tpl");
				$smarty->assign('subscriptions', $subscriptions);
					
				break;
			
			default:
				
				// Board overview
				$smarty->assign('path', $template_dir . "/board_list.tpl");
				$bl = boardList();
				$smarty->assign('bl', $bl);
				
				if ($isallowed)
					$menu->addSubElement($mod, $lang->get('board_manage'), 'board_manage');
				
				if ($login->currentUser() !== false && $subenabled) {
					$menu->addSubElement($mod, $lang->get('thread_subscriptions'), 'subscriptions');
				}
				
				if ($login->currentUser() !== false)
					$menu->addSubElement($mod, $lang->get('mark_all_as_read'), 'mark_as_read');
				
				
		}
		
	} else {
		
		// Get recordset from current board
		$board = $db->selectOneRow($tbl_board, "*", "`boardid`=" . $boardid);
		
		// Check if current user is allowed to view this board
		if ($board['assigned_groupid'] == 0 || $rights->isInGroup($board['assigned_groupid']))
		{
		
			// Set breadcrumb of current board
			$breadcrumbs->addElement($board['board'], makeURL('board', array('boardid' => $boardid)));
			
			switch ($mode)
			{
				
				case 'subscribe':
					
					if ($login->currentUser() === false)
						break;
						
					threadSubscribe($threadid);
					
					break;
				
				case 'thread_add':
					
					if ($login->currentUser() !== false) {
						// Add breadcrumb "Add thread"
						$breadcrumbs->addElement($lang->get('thread_add'), makeURL($mod, array('boardid' => $board['boardid'], 'mode' =>'thread_add')));
						$smarty->assign('path', $template_dir . "/thread_add.tpl");
						
						if (isset($_POST['add'])) {
							if (trim($_POST['post']) != '' && trim($_POST['thread']) != '') {
								$threadid = threadAdd($board['boardid'], $_POST['thread']);
								//postAdd($threadid, $_POST['post']);
								$notify->add($lang->get('board'), $lang->get('thread_added'));
								redirect(makeURL('board', 
									array('boardid' => $board['boardid'], 'threadid' => $threadid)));
							}
							else {
								$notify->add($lang->get('board'), $lang->get('fill_all_fields'));
								break;
							}
						}
						else {
							break;
						}
					}
					
				default:
					
					if ($threadid == 0)
					{
			
						// Add Submenu to start a thread
						if ($login->currentUser() !== false)
						{
							$menu->addSubElement($mod, $lang->get('thread_add'), 'thread_add', array('boardid' => $_GET['boardid']));
							$smarty->assign('url', array('thread_add' => makeURL($mod, array('boardid' => $_GET['boardid'], 'mode' => 'thread_add'))));
							$menu->addSubElement($mod, $lang->get('mark_as_read'), 'mark_as_read', array('boardid' => $_GET['boardid']));
						}
						// Thread overview
						$smarty->assign('path', $template_dir . "/thread_list.tpl");
						
						@$p = (int)$_GET['page'];
						if ($p == 0) $p = 1;
						
						$smarty->assign('board', $board);
						@$tl = threadList($boardid, $p);
						$smarty->assign('tl', $tl);
						@$pages->setValues($_GET['page'], $config->get('board', 'threads-per-page'), $db->num_rows('thread', '`boardid`='.$boardid));
						$smarty->assign('pages', $pages->get('board', array('boardid' => $boardid)));
						
					} else {
						// Show Thread
						
						// Get thread information
						$thread = $db->selectOneRow($tbl_thread, "*", "`threadid`=" . $threadid);
						
						// Set as read
						threadRead($threadid);
						
						if ($login->currentUser() !== false && $subenabled) {
							$menu->addSubElement($mod, $lang->get('thread_subscribe'), 'subscribe', 
								array('boardid' => $boardid, 'threadid' => $threadid));
						}
						
						// Sticky
						if ($mode == 'stick') {
							threadStick($threadid, 1);
							$thread['sticky'] = 1;
						} 
						
						if ($mode == 'release') {
							threadStick($threadid, 0);
							$thread['sticky'] = 0;
						}
						
						if ($mode == 'close') {
							threadClose($threadid, 1);
							$thread['closed'] = 1;
						}
						
						if ($mode == 'open') {
							threadClose($threadid, 0);
							$thread['closed'] = 0;
						}
						
						if ($isallowed) {
							if ($thread['sticky'] == 0)
								$menu->addSubElement($mod, $lang->get('thread_stick'), 'stick', array('boardid' => $boardid, 'threadid' => $threadid));
							else
								$menu->addSubElement($mod, $lang->get('thread_release'), 'release', array('boardid' => $boardid, 'threadid' => $threadid));
							
							if ($thread['closed'] == 0)
								$menu->addSubElement($mod, $lang->get('thread_close'), 'close', array('boardid' => $boardid, 'threadid' => $threadid));
							else
								$menu->addSubElement($mod, $lang->get('thread_open'), 'open', array('boardid' => $boardid, 'threadid' => $threadid));
						}
						
						// add a breadcrumb
						$breadcrumbs->addElement($thread['thread'], makeURL('board', array('boardid' => $boardid, 'threadid' => $threadid)));
						
						switch ($mode){
							case 'remove':
								if ($mode == 'remove') {
									if ($isallowed) {
										if (isset($_POST['yes'])) {
											postRemove($postid);
											$notify->add($lang->get('board'), $lang->get('post_remove_done'));
										} else {
											$smarty->assign('thread_url', makeURL($mod, array('boardid' => $boardid, 'threadid' => $threadid)));
											$smarty->assign('path', $template_dir . '/post_remove.tpl');
											break;
										}
									}
								}
							
							case 'edit':
								if ($mode == 'edit') {
									$post = $db->selectOneRow($tbl_post, "*", "`postid`=" . $postid);
									if ($isallowed || $post['userid'] == $login->currentUserID()) {
										if (isset($_POST['save'])) {
											postEdit($postid, $_POST['post']);
											$notify->add($lang->get('board'), $lang->get('post_edit_done'));
										} else {
											$smarty->assign('post', $post);
											$smarty->assign('path', $template_dir . '/post_edit.tpl');
											break;
										}
									}
								}
							
							case 'thread_move':
								if ($mode == 'thread_move') {
									if ($isallowed) {
										$breadcrumbs->addElement($lang->get('thread_move'), makeURL($mod, array('boardid' => $boardid, 'threadid' => $threadid, 'mode' => 'thread_move')));
										$smarty->assign('path', $template_dir . "/thread_move.tpl");
										
										if (isset($_POST['move'])) {
											threadMove($threadid, $_POST['boardid']);
											$notify->add($lang->get('board'), $lang->get('thread_move_done'));
										} else {
											$bl = boardList();
											if (count($bl) > 0)
												foreach ($bl as $b)
													$boards[$b['boardid']] = $b['board'];
											$smarty->assign('bl', $boards);
											break;
										}
									
									} else
										$notify->add($lang->get('board'), $lang->get('not_allowed'));
								}
								
							case 'thread_remove':
								if ($mode == 'thread_remove') {
									if ($isallowed) {
										$breadcrumbs->addElement($lang->get('thread_remove'), makeURL($mod, array('boardid' => $boardid, 'threadid' => $threadid, 'mode' => 'thread_remove')));
										
										if (isset($_POST['yes'])) {
											threadRemove($threadid);
											$notify->add($lang->get('board'), $lang->get('thread_remove_done'));
										}
										else {
											$smarty->assign('path', $template_dir . "/thread_remove.tpl");
											$smarty->assign('thread_url', makeURL($mod, array("boardid" => $boardid, "threadid" => $threadid)));
										}
									}
									else
										$notify->add($lang->get('board'), $lang->get('not_allowed'));
								
									break;
								}
								
							case 'thread_edit':
								if ($isallowed && $mode == 'thread_edit') {
									$breadcrumbs->addElement($lang->get('thread_edit'), makeURL($mod, array('boardid' => $boardid, 'threadid' => $threadid, 'mode' => 'thread_edit')));
									$smarty->assign('path', $template_dir."/thread_edit.tpl");
									
									if (isset($_POST['save'])) {
										threadEdit($threadid, $_POST['thread']);
									}
									$smarty->assign('thread', $db->selectOne($tbl_thread, 
										'thread', "`threadid`=".$threadid));
									break;
								}
								
							default:
							
								// add admin menu
								if ($isallowed) {
									$menu->addSubElement($mod, $lang->get('thread_move'), 'thread_move', array('boardid' => $boardid, 'threadid' => $threadid));
									$menu->addSubElement($mod, $lang->get('thread_remove'), 'thread_remove', array('boardid' => $boardid, 'threadid' => $threadid));
									$menu->addSubElement($mod, $lang->get('thread_edit'), 'thread_edit', array('boardid' => $boardid, 'threadid' => $threadid));
								}
								
								// Increase hit counter
								if ($mode == '' and @$_GET['page'] == '')
									incHits($threadid);
								
								// add a post
								if (isset($_POST['add']) && $login->currentUser() !== false && $thread['closed'] == 0)
								{
									$postid = postAdd($threadid, $_POST['post'], @$_POST['attachments']);
									redirect(makeURL('board', 
										array('boardid' => $boardid, 'threadid' => $threadid,
												'page' => lastPage($threadid, $config->get('board', 'posts-per-page'))),
											'post'.$postid));
								}
								
								@$quoteid = (int)$_GET['quoteid'];
								if ($quoteid > 0) {
									$q = getQuote($quoteid);
									$u = $user->getUserByID($q['userid']);
									$a = str_replace("%u", $u['nickname'], $lang->get('quote_by'));
									
									$quote = '[quote][i][url="' . makeURL('profile', array('userid' => $q['userid'])) . '"]' . $a . "[/url][/i]\n" . $q['post'] . "[/quote]";
									
									$smarty->assign('quote', $quote);
								} else
									$smarty->assign('quote', '');
								
								// Pages
								$ppp = $config->get('board', 'posts-per-page');
								@$pages->setValues($_GET['page'], $ppp, numPostsInThread($threadid));
								$smarty->assign('pages', $pages->get('board', array('boardid' => $boardid, 'threadid' => $threadid)));
								
								$smarty->assign('path', $template_dir . "/thread.tpl");
								
								$smarty->assign('board', $board);
								$smarty->assign('thread', $thread);
								@$pl = postList($threadid, $_GET['page']);
								$smarty->assign('pl', $pl);
								
								$smarty->assign('number', $pl[count($pl)-1]['number']+1);
								
								// if user is logged in, it is the last page and thread is not closed, show answer field
								$debug->add('board', 'lastpage: '.$pages->lastPage().' page: '.$pages->thisPage());
								if ($login->currentUser() !== false && $thread['closed'] == 0 && $pages->lastPage() == $pages->thisPage() && $mode != 'thread_add') {
									$smarty->assign('showadd', true);
									$u = $login->currentUser();
									$u['avatar'] = $avatar->makeAvatar($u['avatar']);
									$smarty->assign('me', $u);
									
									$smarty->assign('attach_file_button', uploadButton('media/attachments/', 'attachments', $lang->get('attach_file'), true));
								}
								
								
							break;
						}
					}
			}
		}
	}
	
?>