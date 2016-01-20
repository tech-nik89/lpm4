<?php
	
	global $boxes;
			
	$breadcrumbs->addElement($lang->get('boxes'), makeURL($mod, array('mode' => 'boxes')));
	
	$b = $boxes->getAll();
	
	if (isset($_POST['save'])) {
		foreach ($b as $i) {
			if (@$_POST['remove_'.$i['boxid']] == '1') {
				$boxes->remove($i['boxid']);
			}
			else {
				$boxes->move($i['boxid'], $_POST['position_'.$i['boxid']], 
					$_POST['order_'.$i['boxid']]);
				$boxes->edit($i['boxid'], $_POST['title_'.$i['boxid']],
					@$_POST['visible_'.$i['boxid']], 
					@$_POST['requires_login_'.$i['boxid']],
					@$_POST['domainid_'.$i['boxid']]);
			}
		}
		
		if (trim($_POST['file_new']) != '') {
			$boxes->add($_POST['title_new'], $_POST['file_new'], 
				$_POST['position_new'], $_POST['order_new'], @$_POST['visible_new'],
				@$_POST['requires_login_new'],
				@$_POST['domainid_new']);
		}
		
		$b = $boxes->getAll();
	}
	
	$smarty->assign('availableList', $boxes->listAvailable());
	$smarty->assign('dlist', getDomainList());
	$smarty->assign('boxes', $b);
	$smarty->assign('path', $template_dir."/boxes.tpl");
	
?>