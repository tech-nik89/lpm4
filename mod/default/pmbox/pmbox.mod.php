<?php	
	
	$breadcrumbs->addElement($lang->get('pmbox'), makeURL($mod));
	
	if ($login->currentUser() !== false) {	
		$menu->addSubElement($mod, $lang->get('inbox'), 'inbox');
		$menu->addSubElement($mod, $lang->get('outbox'), 'outbox');
		$menu->addSubElement($mod, $lang->get('write'), 'write');
		
		if ($_GET['mode'] == '')
			$_GET['mode'] = 'inbox';
		
		$smarty->assign('mode', $_GET['mode']);
		
		$tbl_inbox = MYSQL_TABLE_PREFIX . "inbox";
		$tbl_outbox = MYSQL_TABLE_PREFIX . "outbox";
		
		switch ($_GET['mode']) {
			case "inbox":
				$breadcrumbs->addElement($lang->get('inbox'), makeURL($mod, array('mode' => 'inbox')));
				
				// here we delete one message
				if (isset($_POST['delete']) and @(int)$_GET['pmid'] > 0) {
					$db->delete($tbl_inbox, "`recieverid`=" . $login->currentUserID() . " AND `pmid`=" . (int)$_GET['pmid']);
					$_GET['pmid'] = 0;
				}
				
				if (@(int)$_GET['pmid'] == 0) {
					// include the template
					$smarty->assign('path', $template_dir . '/inbox.tpl');
					
					if (isset($_POST['delete'])) {
						$list = $db->selectList($tbl_inbox, "*", "`recieverid`=" . $login->currentUserID() );
						
						if (count($list) > 0)
							foreach ($list as $v) {
								if (@$_POST['pm_' . $v['pmid']] == "1")
									$db->delete($tbl_inbox, "`recieverid`=" . $login->currentUserID() . " AND `pmid`=" . (int)$v['pmid']);
							}
					}
					
					$list = $db->selectList($tbl_inbox, "*", "`recieverid`=" . $login->currentUserID(), "timestamp DESC");
					
					if (count($list) > 0)
						foreach ($list as $i => $v) {
							$list[$i]['timestamp'] = timeElapsed($list[$i]['timestamp']);
							
							if ($list[$i]['read'] == 0)
								$list[$i]['read'] = $lang->get('no');
							else
								$list[$i]['read'] = $lang->get('yes');
								
							$list[$i]['subject'] = $list[$i]['subject'];
							$list[$i]['message'] = $list[$i]['message'];
							
							$u = $user->getUserByID($v['senderid']);
							$list[$i]['sender'] = $u['nickname'];
							
							$list[$i]['pm_url'] = makeURL($mod, array('mode' => 'inbox', 'pmid' => $v['pmid']));
							$list[$i]['sender_url'] = makeURL('profile', array('userid' => $v['senderid']));
						}
					
					$smarty->assign('list', $list);
				} else {
					// include the template
					$smarty->assign('path', $template_dir . '/pm.tpl');
					
					// read message
					$message = $db->selectOneRow($tbl_inbox, '*', "`recieverid`=" . $login->currentUserID() . " AND `pmid`=" . (int)$_GET['pmid']);
					
					// get sender information
					$u = $user->getUserByID($message['senderid']);
					$me = $user->getUserByID($message['recieverid']);
					
					$message['sender'] = $u['nickname'];
					$message['sender_url'] = makeURL('profile', array('userid' => $message['senderid']));
					
					$message['reciever'] = $me['nickname'];
					$message['reciever_url'] = makeURL('profile', array('userid' => $message['recieverid']));
					
					$message['timestamp_str'] = timeElapsed($message['timestamp']);
					
					// add a breadcrumb
					$breadcrumbs->addElement($message['subject'], makeURL($mod, array('mode' => 'inbox', 'pmid' => $_GET['pmid'])));
					
					// set as read
					$db->update($tbl_inbox, "`read`=1", "`recieverid`=" . $login->currentUserID() . " AND `pmid`=" . (int)$_GET['pmid']);
					
					// generate answer url
					$smarty->assign('answer_url', makeURL($mod, array('mode' => 'write', 'answerid' => $_GET['pmid'])));
					
					// add br to message
					$message['message'] = $bbcode->parse($message['message']);
					
					// assign to template
					$smarty->assign('message', $message);
				}
				break;
			
			case "outbox":
				$breadcrumbs->addElement($lang->get('outbox'), makeURL($mod, array('mode' => 'outbox')));
				
				if (@(int)$_GET['pmid'] == 0) {
					// include the template
					$smarty->assign('path', $template_dir . '/outbox.tpl');
					
					if (isset($_POST['delete'])) {
						$list = $db->selectList($tbl_outbox, "*", "`senderid`=" . $login->currentUserID() );
						
						if (count($list) > 0)
							foreach ($list as $v) {
								if ($_POST['pm_' . $v['pmid']] == "1")
									$db->delete($tbl_outbox, "`senderid`=" . $login->currentUserID() . " AND `pmid`=" . (int)$v['pmid']);
							}
					}
					
					$list = $db->selectList($tbl_outbox, "*", "`senderid`=" . $login->currentUserID() );
					
					if (count($list) > 0)
						foreach ($list as $i => $v) {
							$list[$i]['timestamp'] = timeElapsed($list[$i]['timestamp']);
							
							if (@$list[$i]['read'] == 0)
								$list[$i]['read'] = $lang->get('no');
							else
								$list[$i]['read'] = $lang->get('yes');
								
							$list[$i]['subject'] = $list[$i]['subject'];
							$list[$i]['message'] = $list[$i]['message'];
							
							$u = $user->getUserByID($v['recieverid']);
							$list[$i]['reciever'] = $u['nickname'];
							
							$list[$i]['pm_url'] = makeURL($mod, array('mode' => 'outbox', 'pmid' => $v['pmid']));
							$list[$i]['reciever_url'] = makeURL('profile', array('userid' => $v['recieverid']));
						}
					
					$smarty->assign('list', $list);
				
				} else {
					// include the template
					$smarty->assign('path', $template_dir . '/pm.tpl');
					
					// get message
					$message = $db->selectOneRow($tbl_outbox, '*', "`senderid`=" . $login->currentUserID() . " AND `pmid`=" . (int)$_GET['pmid']);
					
					// get users
					$u = $user->getUserByID($message['recieverid']);
					$me = $login->currentUser();
					
					$message['reciever'] = $u['nickname'];
					$message['reciever_url'] = makeURL('profile', array('userid' => $message['recieverid']));
					
					$message['sender'] = $me['nickname'];
					$message['sender_url'] = makeURL('profile', array('userid' => $message['senderid']));
					
					$message['timestamp_str'] = timeElapsed($message['timestamp']);
					
					// add a breadcrumb containing subject
					$breadcrumbs->addElement($message['subject'], makeURL($mod, array('mode' => 'inbox', 'pmid' => $_GET['pmid'])));
					
					// add br to message
					$message['message'] = $bbcode->parse($message['message']);
					
					$smarty->assign('message', $message);
				}
				break;
	
			case "write":
				$breadcrumbs->addElement($lang->get('write'), makeURL($mod, array('mode' => 'write')));
				
				if (isset($_POST['send']) && trim($_POST['subject'] != '' && trim($_POST['message']) != '')) {
					$u = $user->find($_POST['reciever'], 1);
					
					if ($u !== false) {
						// check if inbox is full
						$otherinbox = $db->num_rows($tbl_inbox, "`recieverid`=" . $u[0]['userid']);
						$allowed = $config->get($mod, 'inbox_limit');
						
						if ($otherinbox < $allowed)  {
							// insert into inbox
							$db->insert($tbl_inbox, array('pmid', 'senderid', 'recieverid', 'timestamp', 'subject', 'message'),
									array('NULL', $login->currentUserID(), $u[0]['userid'], time(), "'" . $_POST['subject'] . "'", "'" . $_POST['message'] . "'"));
							
							// insert into outbox
							$db->insert($tbl_outbox, array('pmid', 'senderid', 'recieverid', 'timestamp', 'subject', 'message'),
									array('NULL', $login->currentUserID(), $u[0]['userid'], time(), "'" . $_POST['subject'] . "'", "'" . $_POST['message'] . "'"));
							
							// notify about success
							$notify->add($lang->get('pmbox'), $lang->get('pm_sent'));
							
							// Send email, if enabled
							if ($config->get($mod, 'email-notification') == '1') {
								$me = $login->currentUser();
								$eMail->setVar('sender', $me['nickname']);
								$eMail->setVar('url', getSelfURL());
								$result = $eMail->send(
									$lang->get('pm_recieved_subject'),
									$lang->get('pm_recieved_text'),
									$u[0]['email']);
								if ($result) {
									$notify->add($lang->get('pmbox'), $lang->get('pm_mail_sent'));
								}
							}
							
							redirect(makeUrl('pmbox'));
							
						} else
							// inbox of reciever is full, notify
							$notify->add($lang->get('pmbox'), $lang->get('inbox_full'));

					}
					else
						// no user found, notify
						$notify->add($lang->get('pmbox'), $lang->get('user_not_found'));
				} else {
					if (isset($_POST['send']))
						$notify->add($lang->get('pmbox'), $lang->get('fill_all_fields'));
				}
				
				if (@$_GET['answerid'] != '' && !isset($_POST['send'])) {
					// get message
					$message = $db->selectOneRow($tbl_inbox, '*', "`recieverid`=" . $login->currentUserID() . " AND `pmid`=" . (int)$_GET['answerid']);
					$text = '';
					
					// get sender nick
					$u = $user->getUserByID($message['senderid']);
					$message['sender'] = $u['nickname'];
					
					$_POST['reciever'] = $message['sender'];
					
					if (strpos($message['subject'], $lang->get('short_reply')) == 0) {
						$_POST['subject'] = $lang->get('short_reply') . ' ' . $message['subject'];
					}
					
					$lines = explode("\n", ($message['message']));
		
					foreach ($lines as $line)
						$text = $text .  "> " . $line . "\n";
					
						
					$s[] = "%n"; $r[] = $message['sender'];
					$s[] = "%d"; $r[] = date("d.m.", $message['timestamp']);
					$s[] = "%t"; $r[] = date("H:i", $message['timestamp']);
						
					$_POST['message'] = "\n\n\n" . str_replace($s, $r, $lang->get('reply_header')) . "\n" . $text;
					
					$breadcrumbs->addElement($lang->get('answer'), makeURL($mod, array('mode' => 'write', 'answerid'=> $_GET['answerid'])));
				}
				
				// include the template
				$smarty->assign('path', $template_dir . '/write.tpl');
				
				// fill fields with old vars
				@$smarty->assign('subject', $_POST['subject']);
				@$smarty->assign('message', $_POST['message']);
				@$smarty->assign('reciever', $_POST['reciever']);
				
				if (@(int)$_GET['userid'] > 0) {
					$u = $user->getUserByID((int)$_GET['userid']);
					$smarty->assign('reciever', $u['nickname']);
				}
				break;
		}
	
	} else
		$notify->add($lang->get('error'), $lang->get('please_log_in'));	
?>