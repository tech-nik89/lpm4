<?php
	//Tabelle festlegen
	$table = MYSQL_TABLE_PREFIX . 'imprint';
	//Sprachfile laden
	$lang->addModSpecificLocalization($mod);
	//Navigation erneuern
	$breadcrumbs->addElement($lang->get('imprint'), makeURL('imprint'));
	//Rechte prüfen und Menüpunkte anhängen
	if ($rights->isAllowed($mod, 'manage'))
	{
		$menu->addSubElement($mod, $lang->get('imprint_edit'), 'admin');
		$right['manage'] = true;
	}
	
	switch($_GET['mode']){
		case "admin":
			if ($right['manage']){
				if(@$_POST['save']){
					$db->query("DELETE FROM ".$table." WHERE true");
					
					if($_POST['same']=="1"){
						$_POST['cont_name'] = $_POST['owner_name'];
						$_POST['cont_street'] = $_POST['owner_street'];
						$_POST['cont_loc'] = $_POST['owner_loc'];
						
					}
					
					$imprint = htmlspecialchars($_POST['imprint']);
					
					
					$db->query("INSERT INTO ".$table." VALUES ('".secureMySQL($_POST['owner_name'])."','".secureMySQL($_POST['owner_street'])."','".secureMySQL($_POST['owner_loc'])."','".secureMySQL($_POST['owner_tel'])."','".secureMySQL($_POST['owner_mail'])."','".secureMySQL($_POST['cont_name'])."','".secureMySQL($_POST['cont_street'])."','".secureMySQL($_POST['cont_loc'])."','".secureMySQL($_POST['court'])."','".($imprint)."')");
					
					global $log;
					$log->add('imprint', 'updated');
					
				}
				
				$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('mode' => 'admin')));
				
				$smarty->assign('path', $template_dir . '/admin.tpl');
				
				$entry = $db->selectOneRow($table,"*");
				if($entry['owner_name']!=""){
					$entry['same'] = ($entry['owner_name']==$entry['cont_name']?"checked='checked'":"");
				}
				if($entry['same']=="checked='checked'"){
					$entry['cont_name'] = "";
					$entry['cont_street'] = "";
					$entry['cont_loc'] = "";			
				}
				$smarty->assign('edit', $entry);
			}
		break;
		default:
			$entry = $db->selectOneRow($table,"*");
			$smarty->assign('path', $template_dir . '/default.tpl');
			$entry['imprint']=$bbcode->parse(htmlspecialchars_decode($entry['imprint']));
			$smarty->assign('entry', $entry);
		break;
	}