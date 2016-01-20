<?php
	
	require_once($mod_dir."/import_export.function.php");
	
	if ($isallowed) {
		$smarty->assign('path', $template_dir."/import_ical.tpl");
		$breadcrumbs->addElement($lang->get('import_ical'), makeURL($mod, array('mode' => 'import_ical')));
		
		if (isset($_POST['go'])) {
			$upload = new Upload();
			$upload->allowed_types = '(ics)';
			$upload->dir = 'media/ical/';
			$result = $upload->uploadFile();
			if ($result == 0) {
				$ical = file('media/ical/'.$upload->file_name);
				if ($ical) {
					ical_import($ical);
					$notify->add($lang->get('import_ical'), 'Import successfully finished.');
				}
				else {
					$notify->add($lang->get('import_ical'), 'Import failed. Could not read '.$upload->file_name);
				}
			}
			else {
				$notify->add($lang->get('upload'), 'Upload failed. Error code: '.$result);
			}
		}
	}
?>