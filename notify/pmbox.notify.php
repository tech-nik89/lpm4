<?php
	
	/**
	 * Project: Higher For Hire
	 * File: pmbox.notify.php
	 *
	**/
	
	global $notify;
	
	if ($login->currentUser() !== false && ($_GET['mod'] != 'pmbox' || $_GET['mode'] != 'inbox'))
	{
		$count = $db->num_rows(MYSQL_TABLE_PREFIX . 'inbox', "`recieverid`=".$login->currentUserID()." AND `read`=0");
		if ($count > 0)
		{
			$text = str_replace('%n', $count, $lang->get('unread_messages'));
			$menu->addSubElement('pmbox', $text, 'inbox');
			$notify->add($lang->get('inbox'), $text);
		}
	}
	
?>