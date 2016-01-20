<?php
	
	function sendPm($senderid, $recieverid, $subject, $message) {
		global $db;
		$db->insert(MYSQL_TABLE_PREFIX . 'inbox', 
			array('senderid', 'recieverid', 'timestamp', 'subject', 'message'),
			array($senderid, $recieverid, time(), "'".$subject."'", "'".$message."'"));
	}
	
	$lang->addModSpecificLocalization('admin');
	
	if ($rights->isAllowed('admin', 'users')) {
		$me = $login->currentUser();
		
		if ($_POST['pm'] != 'true' && $_POST['mail'] != 'true')
			$_POST['pm'] = 'true';
			
		$pm = $_POST['pm'] == 'true';
		$mail = $_POST['mail'] == 'true';
		$copy = $_POST['copy'] == 'true';
		$send_to = (int)$_POST['send_to'];
		
		$subject = trim(replaceHtmlEntities($_POST['subject']));
		$message = trim($_POST['message']);
		
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
							sendPm($me['userid'], $u['userid'], $subject, $message);
						}
						if ($mail) {
							$eMail->send($subject, str_replace("\n", "<br />", replaceHtmlEntities($message)), $u['email']);
						}
					}
				}
			}
		}
		echo '<img src="mod/default/admin/check.gif" border="0" /> '. str_replace('%c', $counter, $lang->get('send_done'));
	}
?>