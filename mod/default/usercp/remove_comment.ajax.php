<?php
	$comment = $db->selectOneRow('comments', '*', "`commentid`=".(int)$_GET['commentid']);
	if ($comment['userid'] == $login->currentUserId()) {
		$db->delete('comments', "`commentid`=".(int)$_GET['commentid']);
		echo '<span style="color:#00FF00;">'.$lang->get('removed').'</span>';
	}
?>