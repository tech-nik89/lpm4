<?php
	if(isset($_GET['categoryId'])) {
		$categoryId = (int) $_GET['categoryId'];
		$category = $db->selectOneRow('calendar_categories', "*", "`categoryId` = '".$categoryId."'");
		if($category >= 0 && $rights->isAllowed($mod, 'manage')) {
		
			$db->delete('calendar_categories', "`categoryId` = '".$categoryId."'");
		}
	}	
?>