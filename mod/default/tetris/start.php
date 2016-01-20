<?php
	$db->delete('tetris_start');

	$p = $db->selectList('tetris_player');
	
	$seed = rand(1, 1000000000)-1;
		
	foreach ($p as $row) {
		$db->insert('tetris_start', array('nickname', 'seed'), array("'".$row['nickname']."'", $seed));
	}
	
	$db->update('tetris_player', '`rows_1`=0, `rows_2`=0, `rows_3`=0, `rows_4`=0');
	
	$db->insert('tetris_chat',
		array('type', 'nickname', 'text'),
		array(1, "'".$_GET['nickname']."'", "'started a new game.'"));
?>