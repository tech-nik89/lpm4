<table width="100%" border="0" cellpadding="0" cellspacing="0">

	<tr>
		<th>&nbsp;</th>
		<th>Nickname</th>
		<th>Score</th>
		<th>Level</th>
	</tr>
	
<?php

	$list = $db->selectList(MYSQL_TABLE_PREFIX.'tetris_highscore', "*", "1", "`score` DESC", "10");
	$i = 0;
	foreach ($list as $row) {
		$i++;
		?>
		<tr>
			<td><?php echo $i; ?>.</td>
			<td><?php echo $row['nickname']; ?></td>
			<td><?php echo $row['score']; ?></td>
			<td><?php echo $row['level']; ?></td>
		</tr>
		<?php
	}

?>

</table>