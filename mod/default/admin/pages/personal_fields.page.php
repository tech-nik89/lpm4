<?php
	
	$table = MYSQL_TABLE_PREFIX . "personal_fields";
				
	// add a breadcrumb
	$breadcrumbs->addElement($lang->get('personal_fields'), makeURL($mod, array('mode' => 'personal_fields')));
	
	// include the template
	$smarty->assign('path', $template_dir . '/personal_fields.tpl');
	
	// add remove and edit
	if (isset($_POST['save']))
	{
		$list = $db->selectList($table);
		
		// add
		if (trim($_POST['value_new']) != '')
			$db->insert($table, array('fieldid', 'value'), array('NULL', "'" . $_POST['value_new'] . "'"));
		
		
		if (count($list)>0)
			foreach ($list as $l)
			{
				// update
				if ($l['value'] != $_POST['value_' . $l['fieldid']])
					$db->update($table, "`value`='" . $_POST['value_' . $l['fieldid']] . "'", "`fieldid`=" . $l['fieldid']);
				
				// delete
				if (@$_POST['delete_' . $l['fieldid']] == "1")
					$db->delete($table, '`fieldid`=' . $l['fieldid']);
					
				
			}
	}
	
	// list all fields
	$list = $db->selectList($table);
	$smarty->assign('list', $list);
	
?>