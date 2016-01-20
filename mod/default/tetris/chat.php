<?php
		
	$nickname = @mysql_real_escape_string($_GET['nickname']);
	if ($nickname != '') {
		$text = trim(strip_tags(mysql_real_escape_string($_POST['text'])));
		if ($text != '') {
			$db->insert(MYSQL_TABLE_PREFIX.'tetris_chat',
				array('type', 'nickname', 'text'),
				array(10, "'".$nickname."'", "'".$text."'"));
		}
	}
	
	$list = $db->selectList(MYSQL_TABLE_PREFIX.'tetris_chat', "*", "1", "chatid DESC", "8");
	$db->delete(MYSQL_TABLE_PREFIX.'tetris_chat', '`chatid` < LAST_INSERT_ID()-100');
	foreach ($list as $row) {
		echo '<div class="chat_type_'.$row['type'].'">';
		if($row['type'] == 10) {
			echo "<strong>".$row['nickname'].":</strong> ".$row['text']."<br />\n";
		} else {
			echo $row['nickname']." ".$row['text']."<br />\n";
		}
		echo "</div>";
	}
	
?>