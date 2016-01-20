<?php
	
	if ($_GET['mod'] == 'content') {
		$tpl_file = $template_dir."/default.tpl";
		
		$additional_content = $db->selectOne('content', 'box_content', "`key`='".secureMySQL($_GET['key'])."'", "`version` DESC");
		if (trim($additional_content) != '') {
			$smarty->assign('content_box_value', $additional_content);
			$visible = true;
		}
	}
	
?>