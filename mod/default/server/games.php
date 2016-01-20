<?php
	
	$h = fopen($mod_dir."/gameq/GameQ/games.ini", "r");
	
	$games = array();
	
	while (!feof($h)) {
		$row = trim(fgets($h, 4096));
		if (substr($row, 0, 1) == '[') {
			$game['gameq'] = substr($row, 1, strlen($row) - 2);
		}
		if (substr($row, 0, 4) == 'name') {
			$v = explode('"', $row);
			$game['name'] = $v[1];
			$games[] = $game;
			unset($game);
		}
	}
	
	fclose($h);
	
?>