<?php
	if(isset($_POST['delete'])) {
		if(isset($_POST['categoryId'])) {
			$categoryId = (int) $_POST['categoryId'];
			$category = $db->selectOneRow('calendar_categories', "*", "`categoryId` = '".$categoryId."'");
			if($category >= 0 && $rights->isAllowed($mod, 'manage')) {
			
				$db->delete('calendar_categories', "`categoryId` = '".$categoryId."'");
			}
		}	
	} 

	if(isset($_POST['save'])) {
		if(trim($_POST['title']) != "" && trim($_POST['backgroundcolor']) != "" && trim($_POST['fontcolor']) != "") {
			//Update
			$categoryId = (int) $_POST['categoryId'];
			if($db->num_rows("calendar_categories", "categoryId ='".$categoryId."'") == 1) {
				$db->update('calendar_categories', "title='".$_POST['title']."', 
													backgroundcolor='".$_POST['backgroundcolor']."',
													fontcolor='".$_POST['fontcolor']."',
													description='".$_POST['description']."'",
													"`categoryId`='".$categoryId."'");
			}
			//Insert
			else {
				$db->insert('calendar_categories', array('title', 'backgroundcolor', 'fontcolor', 'description'),
													array("'".$_POST['title']."'", "'".$_POST['backgroundcolor']."'", "'".$_POST['fontcolor']."'", "'".$_POST['description']."'"));
			
			}
		} else {
		//Error
		var_dump($_POST);
		}
	} 
	
	$categories = $db->selectList("calendar_categories", "*", "1", "title ASC");
	
	
	$smarty->assign('categories', $categories );
	
	$smarty->assign('path', $template_dir."/categories.tpl");
	$smarty->assign('headline', $lang->get('categories'));

?>