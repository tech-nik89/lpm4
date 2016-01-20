<?php
	@$score = (int)$_GET['score'];
	@$lines = (int)$_GET['lines'];
	@$level = (int)$_GET['level'];
	@$nick = secureMySQL($_GET['nickname']);
	@$field = secureMySQL($_GET['field']);
	
	$db->insert('tetris_chat',
		array('type', 'nickname', 'text'),
		array(1, "'".$nick."'", "'died.'"));
	
	$db->update('tetris_player', "`field`='".$field."'", "`nickname`='".$nick."'");
	
	if($db->num_rows('tetris_player', '`alive` > 0') == 2) {
		$winner = $db->selectOneRow('tetris_player', '*', "`alive`=1 && `nickname`!='".$nick."'");
		
		$db->insert('tetris_chat',
			array('type', 'nickname', 'text'),
			array('1', "'".$winner['nickname']."'", "'[Wins!]'"));
		$db->update('tetris_player', '`wins`=`wins`+1', "`nickname`='".$winner['nickname']."'");
	}

	if ($score < 100) {
		die();
	}	
	
	$user = $db->selectOneRow('tetris_player', '*', "`nickname`= '".$nick."'");

	// validate if the reported highscore could be possible
	if($level - 1 > $user['level'] || $score * 0.8 > $user['score']) {
		die();
	}	
	
	$db->insert(MYSQL_TABLE_PREFIX.'tetris_highscore',
			array('nickname', 'score', 'lines', 'level', 'timestamp'),
			array("'".$nick."'", $score, $lines, $level, time()));
?>