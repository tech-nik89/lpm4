<?php
	
	/**
	 * Project: Higher For Hire
	 * File: buddylist.notify.php
	 *
	**/
	
	if ($login->currentUser() !== false && ($_GET['mod'] != 'buddylist' || $_GET['mode'] != 'requests'))
	{
		$count = $db->num_rows(MYSQL_TABLE_PREFIX . 'buddylist', "`buddyid`=".$login->currentUserId()." AND `accepted`=0");
		if ($count > 0)
		{
			$lang->addModSpecificLocalization('buddylist');
			$menu->addSubElement('buddylist', str_replace("%c", $count, $lang->get('new_buddyrequests')), 'requests');
		}
	}
	
?>