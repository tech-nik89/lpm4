<?php
	
	$lang->addModSpecificLocalization($mod);
	$breadcrumbs->addElement($lang->get('formmaker'), makeURL('formmaker'));
	
	if ($rights->isAllowed($mod, 'manage')) {
	
		@$formid = (int)$_GET['formid'];
		@$mode = $_GET['mode'];
		
		switch ($mode) {
			case 'add':
				$smarty->assign('path', $template_dir.'/edit.tpl');
				if (isset($_POST['save']) && allFilled($_POST['title'], $_POST['key'])) {
					$db->insert('formmaker',
						array(
							'title', 
							'key', 
							'description', 
							'action', 
							'address',
							'submit',
							'submit_message'
						),
						array(
							"'".$_POST['title']."'",
							"'".$_POST['key']."'",
							"'".$_POST['description']."'",
							"'".$_POST['action']."'",
							"'".$_POST['address']."'",
							"'".$_POST['submit']."'",
							"'".$_POST['submit_message']."'"
						)
					);
					$notify->add($lang->get('formmaker'), $lang->get('save_done'));
					redirect(makeURL($mod, array('formid' => mysql_insert_id())));
				}
				break;
			case 'records':
				@$recordid = (int)$_GET['recordid'];
				if ($recordid == 0) {
					$smarty->assign('path', $template_dir.'/records.tpl');	
					$records = $db->selectList('formmaker_data', '`submitid`, `timestamp` ', '`formid`='.$formid);
					foreach ($records as $i => $record) {
						$records[$i]['url'] = makeURL($mod, array('formid' => $formid, 'mode' => 'records', 'recordid' => $record['submitid']));
					}
					$smarty->assign('records', $records);
				}
				else {
					if (isset($_POST['delete'])) {
						$db->delete('formmaker_data', '`submitid`='.$recordid);
						$notify->add($lang->get('form'), $lang->get('delete_record_done'));
						redirect(makeURL($mod));
					}
					$smarty->assign('path', $template_dir.'/record.tpl');	
					$record = $db->selectOneRow('formmaker_data', '*', '`submitid`='.$recordid);
					$smarty->assign('record', $record);
				}
				break;
			default:
				if ($formid == 0) {
					$smarty->assign('path', $template_dir.'/default.tpl');
					$menu->addSubElement($mod, $lang->get('add'), 'add');
					$forms = $db->selectList('formmaker');
					foreach ($forms as $i => $form) {
						$forms[$i]['url'] = makeURL($form['key']);
						$forms[$i]['edit_url'] = makeURL($mod, array('formid' => $form['formid']));
						$forms[$i]['records_url'] = makeURL($mod, array('formid' => $form['formid'], 'mode' => 'records'));
					}
					$smarty->assign('forms', $forms);
				}
				else {
					$smarty->assign('path', $template_dir.'/edit.tpl');
					if (isset($_POST['save'])) {
						$db->update('formmaker',
							"`title`='".secureMySQL($_POST['title'])."',
							`key`='".secureMySQL($_POST['key'])."',
							`description`='".secureMySQL($_POST['description'])."',
							`action`='".secureMySQL($_POST['action'])."',
							`address`='".secureMySQL($_POST['address'])."',
							`submit`='".secureMySQL($_POST['submit'])."',
							`submit_message`='".secureMySQL($_POST['submit_message'])."'",
							'`formid`='.$formid
						);
						$notify->add($lang->get('formmaker'), $lang->get('save_done'));
					}
					if (isset($_POST['delete'])) {
						$db->delete('formmaker_elements', '`formid`='.$formid);
						$db->delete('formmaker_data', '`formid`='.$formid);
						$db->delete('formmaker', '`formid`='.$formid);
						$notify->add($lang->get('formmaker'), $lang->get('form_delete_done'));
						redirect(makeURL($mod));
					}
					
					if (isset($_POST['add_element'])) {
						@$db->insert('formmaker_elements',
							array(
								'formid',
								'type',
								'title',
								'values',
								'order',
								'required'
							),
							array(
								$formid,
								(int)$_POST['type'],
								"'".$_POST['title']."'",
								"'".$_POST['values']."'",
								(int)$_POST['order'],
								(int)$_POST['required']
							)
						);
					}
					if (isset($_POST['edit_element'])) {
						@$db->update('formmaker_elements',
							"`type`=".((int)$_POST['type']).",
							`title`='".secureMySQL($_POST['title'])."',
							`values`='".secureMySQL($_POST['values'])."',
							`order`=".(int)$_POST['order'].",
							`required`=".(int)$_POST['required'],
							"`elementid`=".(int)$_POST['elementid']
						);
					}
					if (isset($_POST['delete_element'])) {
						$db->delete('formmaker_elements',
							'`elementid`='.(int)$_POST['elementid']);
					}
					$form = $db->selectOneRow('formmaker', '*', '`formid`='.$formid);
					$form['elements'] = $db->selectList('formmaker_elements', '*', '`formid`='.$formid, '`order`');
					$smarty->assign('form', $form);
				}
		}
	
	}
	else {
		$notify->add($lang->get('formmaker'), $lang->get('not_allowed'));
	}
	
?>