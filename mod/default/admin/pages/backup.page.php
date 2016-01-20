<?php
	
	$menu->addSubElement($mod, $lang->get('backup'), 'backup', array('action' => 'backup'));
	$menu->addSubElement($mod, $lang->get('restore'), 'backup', array('action' => 'restore'));
	
	if (@$_GET['action'] == 'backup' || @$_GET['action'] == '') {
	
		$breadcrumbs->addElement($lang->get('backup'), makeURL($mod, array('mode' => 'backup', 'action' => 'backup')));
			
		$smarty->assign('tables', $backup->listTables());
		$smarty->assign('path', $template_dir."/backup.tpl");
		
		if (isset($_POST['doBackup'])) {
			@$b = $backup->backupTables($_POST['tables']);
			if (isset($_POST['download'])) {
				$file = "backup-".date("dmY-Hi").".xml";
				header("Content-type: application/octet-stream");
				header('Content-disposition: attachment; filename='.$file);
				echo $b; 
				die();
			}
			else {
				$smarty->assign('backup', $b);
			}
		}
	}
	else {
		$breadcrumbs->addElement($lang->get('restore'), makeURL($mod, array('mode' => 'backup', 'action' => 'restore')));
		$smarty->assign('path', $template_dir."/restore.tpl");
		
		if (isset($_POST['doRestore'])) {
			$backup->restore($_POST['backup']);
			$notify->add($lang->get('backup'), $lang->get('backup_restored'));
		}
		
		if (isset($_POST['submit'])) {
			
			// set allowed file types
			$allowed_types = "(xml)";

			// is really a file?
			if(is_uploaded_file($_FILES["file"]["tmp_name"])) 
			{
				 // valid extension?
				if(preg_match("/\." . $allowed_types . "$/i", $_FILES["file"]["name"]))
				{
					$h = fopen($_FILES["file"]["tmp_name"], "rb");
					$fileContent = fread($h, filesize($_FILES["file"]["tmp_name"]));
					fclose($h);
					
					$backup->restore($fileContent);
					$notify->add($lang->get('backup'), $lang->get('backup_restored'));
			
				} else
					$msg = $this->lang->get('upload_bad_extension');
			} else
				$msg = $this->lang->get('upload_failed');
			
		}
	}
	
?>