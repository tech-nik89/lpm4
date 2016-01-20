<?php
	
	$table = MYSQL_TABLE_PREFIX . "contact";
	$lang->addModSpecificLocalization($mod);
	
	$contact = $config->get($mod, 'title');
	if ($contact == '')
		$contact = $lang->get('contact');
	
	$description = $config->get($mod, 'description');
	if ($description == '')
		$description = $lang->get('contact_description');
	
	$smarty->assign('contact_description', $description);	
	$smarty->assign('contact', $contact);
	
	if ($login->currentUser() !== false || $config->get($mod, 'login-required') == '0')
	{
		
		if (@$_GET['uniqid'] != '')
			@$uniqid = $_GET['uniqid'];
		else
			$uniqid = uniqid();
			
		$uniqid = secureMySQL($uniqid);
		if (isset($_POST['send']))
		{
			$c = $db->num_rows($table, "`uniqid`='".$uniqid."'");
			if ($c == 0) {
			
				$s = secureMySQL($_POST['subject']);
				$t = secureMySQL($_POST['text']);
				$m = secureMySQL($_POST['email']);
				
				if (trim($s) != '' && trim($t) != '' && trim($m) == '') {
    				$sql = "INSERT INTO `" . $table . "`
    						(`userid`, `timestamp`, `uniqid`, `subject`, `text`)
    						VALUES
    						(".$login->currentUserID().", ".time().", '".$uniqid."', '".$s."', '".$t."');";
    				$db->query($sql);
    				
    				$notify->add($lang->get('contact'), $lang->get('sent'));
    				
    				global $log;
    				$log->add('contact', 'contact request sent');
    				
    				if ($config->get($mod, 'send-mail') == '1') {
    					$addr = $config->get($mod, 'email-adress');
    					if ($addr != '') {
    						$eMail->setVar('subject', $s);
    						$eMail->setVar('text', str_replace('\n', "<br />", $t));
    						$eMail->send($lang->get('email_subject'), $lang->get('email_text'), $addr);
    						$log->add('contact', 'email notification sent to ' . $addr);
    					}
    				}
				}
			} else {
				$notify->add($lang->get('contact'), $lang->get('already_sent'));
			}
			
			$smarty->assign('subject', $_POST['subject']);
			$smarty->assign('text', $_POST['text']);
		}
			
		$smarty->assign('url', makeURL($mod, array('uniqid' => $uniqid)));
		
		$smarty->assign('path', $template_dir . "/contact.tpl");
		
	} else
		$smarty->assign('path', $template_dir . "/notloggedin.tpl");
?>