<?php

	$lang->addModSpecificLocalization('formmaker');
	$smarty->assign('lang', $lang->getAll());
	$smarty->assign('path', "../mod/default/formmaker/form.tpl");
	$form['elements'] = $db->selectList('formmaker_elements', '*', '`formid`='.(int)$form['formid'], '`order`');
	
	if (isset($_POST['submit_form'])) {
		$all_filled = true;
		foreach ($form['elements'] as $element) {
			@$submit[$element['elementid']] = $_POST['field_'.$element['elementid']];
			if ($element['required'] == 1 && trim($_POST['field_'.$element['elementid']]) == '') {
				$all_filled = false;
			}
		}
		$smarty->assign('submit', $submit);
		if (!$all_filled) {
			$notify->add($lang->get('form'), $lang->get('required_descr'));
		}
		else {
		
			$content = '<p style="size:1.3em;"><strong><u>'.$form['title'].'</u></strong></p>';
			foreach ($form['elements'] as $element) {
				if ($element['type'] > 1) {
				$content .= '<p><strong>'.$element['title'].'</strong>:<br />'
					.$submit[$element['elementid']].'</p>';
				}
			}
			
			switch ($form['action']) {
				case 'store':
					$db->insert('formmaker_data',
						array(
							'formid',
							'timestamp',
							'ipaddress',
							'content'
						),
						array(
							$form['formid'],
							time(),
							"'".getRemoteAdr()."'",
							"'".$content."'"
						)
					);
					$notify->add($lang->get('form'), $form['submit_message']);
					$smarty->assign('submitted', true);
					break;
				case 'mail':
					$addresses = explode(';', $form['address']);
					$subject = $lang->get('form').': '.$form['title'];
					foreach ($addresses as $addr) {
						if (trim($addr) != '') {
							$eMail->send($subject, $content, trim($addr));
						}
					}
					
					$notify->add($lang->get('form'), $form['submit_message']);
					$smarty->assign('submitted', true);
					break;
			}
		}
	}
	
	$smarty->assign('form', $form);

?>