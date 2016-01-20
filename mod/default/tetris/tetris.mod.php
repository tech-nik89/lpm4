<?php
	
	$u = $login->currentUser();
	$makeMeMaster = false;
	
	$db->delete('tetris_player', "`nickname`='".$u['nickname']."'");
	
	if($u) {
		$nick = $u['nickname'];
		if($rights->isAllowed('tetris', 'admin', $u['userid'])) {
			$makeMeMaster = true;
		}
	} else {
		$nick = "Player" . rand(0, 999);
	}
	
	if ($db->num_rows(MYSQL_TABLE_PREFIX.'tetris_player', "`master`>0") == 0) {
		$master = 1;
	} else {
		$master = 0;
	}
	
	if($makeMeMaster) {
		$master = 2;
	}
	
	$uniquid = uniqid();

	$db->insert(MYSQL_TABLE_PREFIX.'tetris_player',
		array("nickname", "ipadress", "last_action", "last_real_action", "master", "uniquid"),
		array("'".$nick."'", "'".getRemoteAdr()."'", time(), time(), $master, "'".$uniquid."'")
		);
	
	$field = array();
	for ($y = 0; $y < 19; $y++) {
		for ($x = 0; $x < 10; $x++) {
			$field[$y][$x] = $x."_".$y;
		}
	}
	
	$smarty->assign('uniquid', $uniquid);
	$smarty->assign('nick', urlencode($nick));
	$smarty->assign('field', $field);
	$smarty->assign('path', $template_dir."/tetris.tpl");
?>