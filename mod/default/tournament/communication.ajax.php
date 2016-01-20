<?php
	
	if ($login->currentUser() !== false) {
		if ($_POST['text'] != '') {
			$db->insert('tournamentcomm',
				array('tournamentid', 'userid', 'encid', 'roundid', 'text', 'timestamp'),
				array((int)$_POST['tournamentid'], $login->currentUserId(), (int)$_POST['encid'],
					(int)$_POST['roundid'], "'".$_POST['text']."'", time()));
		}
		$list = $db->selectList('tournamentcomm`, `'.MYSQL_TABLE_PREFIX.'users', '*',
			"`tournamentid`=".(int)$_POST['tournamentid']."
			AND `encid`=".(int)$_POST['encid']."
			AND `roundid`=".(int)$_POST['roundid']."
			AND `".MYSQL_TABLE_PREFIX."tournamentcomm`.`userid`=`".MYSQL_TABLE_PREFIX."users`.`userid`",
			"`timestamp` DESC");
		foreach ($list as $i => $item) {
			$list[$i]['timestamp_str'] = timeElapsed($item['timestamp']);
		}
		$smarty->assign('list', $list);
		$smarty->display('../mod/default/tournament/communication.tpl');
	}
	
?>