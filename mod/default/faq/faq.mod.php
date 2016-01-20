<?php
	/**
	 * Project: Higher For Hire
	 * File: faq.mod.php
	 *
	 * Rights used
	 *   manage				Manage FAQ
	**/
	
	//Tabelle festlegen
	$table = MYSQL_TABLE_PREFIX . 'faq';
	//Sprachfile laden
	$lang->addModSpecificLocalization($mod);
	//Navigation erneuern
	$breadcrumbs->addElement($lang->get('faq'), makeURL('faq'));
	//Rechte prüfen und Menüpunkte anhängen
	if ($rights->isAllowed($mod, 'manage'))
	{
		$menu->addSubElement($mod, $lang->get('faq_editor'), 'admin');
		$right['manage'] = true;
	}

	switch($_GET['mode']){
		
	case "admin":
		if ($right['manage'])
				{
					// add a breadcrumb
					$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('mode' => 'admin')));
					
					// include the template
					$smarty->assign('path', $template_dir . '/admin.tpl');
					
					// save procedure
					if (isset($_POST['save']))
					{
						// get the questions
						$c = $db->selectList($table,'*','true','');
						
						// walk through the questions and update
						if (count($c) > 0)
						foreach ($c as $question)
						{
							
							// delete?
							if ($_POST['delete_' . $question['id']] == "1")
								$db->query("DELETE FROM ".$table." WHERE id=".intval($question['id']));	
							else
							{
								$db->query("UPDATE ".$table." SET faqorder=".intval($_POST['order_' . $question['id']])." WHERE id=".intval($question['id']));							
							}
						}
						
						// add new element
						}elseif(isset($_POST['submit'])){
							if($_POST['sid']>0){
								$db->query("UPDATE ".$table." SET faqorder=".intval($_POST['new_order']).", question='".secureMySQL($_POST['new_question'])."', answer='".secureMySQL($_POST['new_answer'])."' WHERE id=".intval($_POST['sid']));
							}else{
								$db->query("INSERT INTO ".$table." VALUES (NULL,'".intval($_POST['new_order'])."','".secureMySQL($_POST['new_question'])."','".secureMySQL($_POST['new_answer'])."')");
							}		
						//EDIT Element
						}elseif(isset($_GET['edit'])){
							$edit = $db->selectOneRow($table,"*","id=".intval($_GET['edit']));
							$smarty->assign('edit', $edit);						
						}
					
					// get questions
					$rows = $db->selectList($table,'*','true','');
					if($rows){
					foreach($rows as $row){
						$row['edit'] = makeURL($mod, array('mode' => 'admin', 'edit' => $row['id']));
						$list[] = $row;
					}
					$smarty->assign('list', $list);
					}
				}
		break;
	default:
		$smarty->assign('path', $template_dir . '/index.tpl');
		$rows = $db->selectList($table,'*','true','');
		if($rows){
			$smarty->assign('list', $rows);
		}
		break;
			
	}
?>