<?php
	
	$tbl = MYSQL_TABLE_PREFIX . 'sponsor';
	$isallowed = $rights->isallowed($mod, 'manage');
	$mode = $_GET['mode'];
	
	$lang->addModSpecificLocalization($mod);
	$breadcrumbs->addElement($lang->get('sponsor'), makeURL($mod));
	
	if ($isallowed) {
		$menu->addSubElement($mod, $lang->get('sponsor_add'), 'add');
		$menu->addSubElement($mod, $lang->get('sponsor_edit'), 'edit');
	}
	
	switch ($mode) {
	
		case 'edit':
			
			if ($isallowed) {
				$breadcrumbs->addElement($lang->get('sponsor_edit'), makeURL($mod, array('mode' => 'edit')));
				
				$sponsorid = (int)$_GET['sponsorid'];
				
				if ($sponsorid == 0) {
					if (isset($_POST['remove'])) {
						$sl = $db->selectList($tbl, "*", "1", "`name`");
						$smarty->assign('sl', $sl);
						
						if (isset($sl) && count($sl) > 0) {
							foreach ($sl as $s) {
								if ($_POST['remove_'.$s['sponsorid']] == "1") {
									@unlink('./media/sponsor/' . $s['image']);
									$db->delete($tbl, "`sponsorid`=" . $s['sponsorid']);
								}
							}
						}
					} 
						
					$sl = $db->selectList($tbl, "*", "1", "`name`");
					if (isset($sl) && count($sl)) {
						foreach ($sl as $i => $s) {
							$sl[$i]['url'] = makeURL($mod, array('mode' => 'edit', 'sponsorid' => $s['sponsorid']));
						}
					}
					$smarty->assign('sl', $sl);
					
					$smarty->assign('path', $template_dir . '/remove.tpl');
				}
				else {
					if (isset($_POST['save'])) {
						$db->update('sponsor',
							"`name`='".secureMySQL($_POST['name'])."',
							`homepage`='".secureMySQL($_POST['homepage'])."',
							`description`='".secureMySQL($_POST['description'])."'",
							"`sponsorid`=".$sponsorid);
					}
					
					$sponsor = $db->selectOneRow('sponsor', "*", "`sponsorid`=".$sponsorid);
					$breadcrumbs->addElement($sponsor['name'], makeURL($mod, array('mode' => 'edit', 'sponsorid' => $sponsorid)));
					$smarty->assign('sponsor', $sponsor);
					$smarty->assign('action', $lang->get('edit'));
					$smarty->assign('path', $template_dir."/edit.tpl");
				}
				break;
			}
			
		case 'add':
			
			if ($isallowed) {
				$smarty->assign('action', $lang->get('sponsor_add'));
				$breadcrumbs->addElement($lang->get('sponsor_add'), makeURL($mod, array('mode' => 'add')));
				
				if (isset($_POST['save'])) {
				    $upload = new Upload();
                    $upload->dir = 'media/sponsor/';
				    $upload->uploadFile();
				    
					$db->insert($tbl, array('sponsorid', 'name', 'description', 'homepage', 'image'),
								array('NULL', "'".$_POST['name']."'", "'".$_POST['description']."'","'".$_POST['homepage']."'", "'".$upload->file_name."'"));
				} else {
					$smarty->assign('path', $template_dir . "/add.tpl");
					break;
				}
			}
			
		default:
			$sl = $db->selectList($tbl, "*", "1", "`name`");
			if (count($sl) > 0)
			foreach ($sl as $i => $s){
				$hp = $s['homepage'];
				if (substr($hp, 0, strlen("http://")) != "http://")
					$sl[$i]['homepage'] = 'http://' . $hp;
			}
			$smarty->assign('sl', $sl);
			$smarty->assign('path', $template_dir . "/list.tpl");
	}
	
?>