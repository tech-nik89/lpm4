<p>
<?php

	function getAttackName($strength) {
		$attacklist = array(
			1 => array('a spoon', 'an apple', 'a bottle of rum', 'a mouse', 'a flash grenade', 'a cake', 'a cookie', 'a tennisball', 'mud'),
			2 => array('a stick', 'a smoke grenade', 'a broken bottle', 'a book', 'a soccerball', 'a hammer'),
			3 => array('a barrel', 'a glock', 'a stone', 'a talking orange', 'a chair'),
			4 => array('a donkey', 'a m4a1', 'a huge rock', 'a desert eagle'),
			5 => array('a rocket launcher', 'a refridgerator', 'an electro shocker'),
			6 => array('a redeemer', 'a banana', 'a cow', 'a thunderbolt'),
			7 => array('a cheat')
			);

		if(!isset($attacklist[$strength])) {
			$attack = count($attacklist);
		} else {
			$attack = $strength;
		}
		$name = $attacklist[$attack][rand(0, count($attacklist[$attack])-1)];
		
		return $name;
	}
	
	@$nick = secureMySQL($_GET['nickname']);
	@$score = (int)$_GET['score'];
	@$level = (int)$_GET['level'];
	@$attack = secureMySQL($_GET['attack']);
	@$alive = (int)$_GET['alive'];
	@$madeaclick = secureMySQL($_GET['madeaclick']);
	@$uniquid = secureMySQL($_GET['uniquid']);
	@$field = secureMySQL($_GET['field']);
	
	$user = $db->selectOneRow('tetris_player', '*', "`nickname`= '".$nick."'");
	
	// check if the request was send by hand

	if($user['uniquid'] == $uniquid) {
		// ATTACK
		$attacks = explode('-', $attack);
		foreach($attacks as $attack) {
			if (@$attack > 0 && $attack <= 4) {
				$attack = (int) ($attack * pow(1.1, $level)) - 1;	
				if($attack > 0) {
					$r = $db->selectOneRow(MYSQL_TABLE_PREFIX.'tetris_player', "*", "`nickname` != '".$nick."' AND `alive` = 1", "RAND()");
					if (null != $r) {
						$db->insert(MYSQL_TABLE_PREFIX.'tetris_attack',
							array('nickname', 'rows'),
							array("'".$r['nickname']."'", (int)$attack));
						$db->insert(MYSQL_TABLE_PREFIX.'tetris_chat',
							array('type', 'nickname', 'text'),
							array(0, "'".$nick."'", "'attacked ".$r['nickname']." with ".getAttackName((int) $attack)." (".$attack.").'"));
					}
				}
			}
		}
		
		$attacks = 0;
		$attacklist = $db->selectList(MYSQL_TABLE_PREFIX.'tetris_attack', "*", "`nickname` = '".$nick."'");
		
		foreach ($attacklist as $row) {
			?>
			<script type="text/javascript">
				addRows(<?php echo $row['rows']; ?>);
			</script>
			<?php
		}
	} else {
		
	}
	$newuniquid = uniqid();
		
	$db->delete(MYSQL_TABLE_PREFIX.'tetris_attack', "`nickname` = '".$nick."'");
	
	if ($db->num_rows(MYSQL_TABLE_PREFIX.'tetris_player', "`master`>0") == 0) {
		$master = 1;
	} else {
		$master = 0;
	}

	// PLAYER
	if($madeaclick == 'true') {
		$last_real_action = "`last_real_action`=".time().", ";
	} else {
		$last_real_action = "";
	}
	
	// Check field
	if($field == '') {
		$updatefield='';
	} else {
		$updatefield=", `field`='".$field."'";
	}
	
	// Update player
	if ($master > 0) {
		$db->update(MYSQL_TABLE_PREFIX.'tetris_player', $last_real_action."`last_action`=".time().", `score`=".$score.", `level`=".$level.", `alive`=".$alive.", `master`=".$master.", `uniquid`='".$newuniquid."'".$updatefield, "`nickname`='".$nick."' LIMIT 1");
	} else {
		$db->update(MYSQL_TABLE_PREFIX.'tetris_player', $last_real_action."`last_action`=".time().", `score`=".$score.", `level`=".$level.", `alive`=".$alive.", `uniquid`='".$newuniquid."'".$updatefield, "`nickname`='".$nick."' LIMIT 1");
	}
	
	// Remove player who left
	$db->delete(MYSQL_TABLE_PREFIX.'tetris_player', "`last_action`<".(time()-20));
		
	// Remove player who are not willing to leave but pagesitting
	if($db->num_rows('tetris_player', "`nickname` = '".$nick."' AND `last_real_action`<".(time()-(60*5))) == 1) {
		$db->delete('tetris_player', "`nickname` = '".$nick."'");
		// Redirect
		?>
			<script type="text/javascript">
				redirectPageSitter();
			</script>
		<?php
	}	
		
	$me_master = 0;
	if($db->num_rows('tetris_player', '`alive` > 0') > 1) {
		$me_master = 0;
	} else {
		$me_master = $db->selectOne('tetris_player', 'master', "`nickname` = '".$nick."'");
	}
		
	if ($me_master > 0) {
		?>
		<script type="text/javascript">
			$("#master").css('display', 'block');
		</script>
		<?php
		if ($me_master==2 ) {
			?>
			<script type="text/javascript">
				$("#masteradmin").css('display', 'block');
			</script>
			<?php
		}
	} else {
		?>
		<script type="text/javascript">
			$("#master").css('display', 'none');
		</script>
		<?php
	}
	
	
	$playerlist = $db->selectList(MYSQL_TABLE_PREFIX.'tetris_player', "*", "1", "`score` DESC");
	$i=1;
	echo '<input type="hidden" name="uniquid" id="uniquid" value="'.$newuniquid.'" />';
	echo "<table style=\"border-collapse:collapse; \" width=\"100%\">";
	echo "<tr>";
		echo "<th>Rank</th>";
		echo "<th>Name</th>";
		echo "<th>Level</th>";
		echo "<th>Score</th>";
		echo "<th>Games</th>";
		echo "<th>Wins</th>";
		echo "<th>Field</th>";
	echo "</tr>";
	foreach ($playerlist as $row) {
		$style='';
		if($row['alive'] == 0) {
			$style="background-color:#FFCCCC;";
		}
		if($row['nickname'] == $nick) {
			$style.="font-style:italic;";
		}
		if($row['master'] > 0) {
			$style.="font-weight:bold;";
		}
		echo "<tr style=\"".$style."\">";
		echo "	<td>";
		echo 		$i++;
		echo "	</td>";
		echo "	<td>";
		echo 		$row['nickname'];
		echo "	</td>";
		echo "	<td>";
		echo 		$row['level'];
		echo "	</td>";
		echo "	<td>";
		echo 		$row['score'];
		echo "	</td>";
		echo "	<td>";
		echo 		$row['games'];
		echo "	</td>";
		echo "	<td>";
		echo 		$row['wins'];
		echo "	</td>";
		echo "	<td style=\"overflow:hidden;\">";
		
		if($row['field'] != '') {
			$fieldrows = explode(';', $row['field']);
			echo "<table style=\"border-collapse:collapse; border:1pt solid black;\">";
			foreach($fieldrows as $fieldrow) {
				echo "<tr>";
				$cells = explode(',', $fieldrow);
				foreach($cells as $cell) {
					echo "<td class=\"block_".($cell-1)." minipreview\">";
					echo "</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
		}
		
		echo "	</td>";
		echo "</tr>";
	}
	echo "</table>";
	
	$start = $db->selectOneRow(MYSQL_TABLE_PREFIX.'tetris_start', '*', "`nickname`='".$nick."'");
	if ($start) {
		?>
			<script type="text/javascript">
				start('<?php echo $start['seed']; ?>');
			</script>
		<?php
		$db->update('tetris_player', '`games`=`games`+1', "`nickname`='".$nick."'");
		$db->delete(MYSQL_TABLE_PREFIX.'tetris_start', "`nickname`='".$nick."'");
	}
?>
</p>