<?php
	
	$lang->addModSpecificLocalization($mod);
	require_once($mod_dir."/fileadmin.function.php");
	$breadcrumbs->addElement($lang->get('fileadmin'), makeURL('fileadmin'));
	
	if ($rights->isAllowed($mod, 'use')) {
		if (!file_exists($dir) || fileperms($dir) < 0777) {
			$notify->add($lang->get('fileadmin'), $lang->get('dir_missing'));
		}
		else {
			// Everything okay, display explorer.
			$smarty->assign('path', $template_dir."/default.tpl");
			$smarty->assign('directory_path', $template_dir."/directory.tpl");
			
			// Get max upload size
			$max_size = $config->get('fileadmin', 'max-upload-size');
			if ((int)$max_size == 0)
				$max_size = 10485760;
			
			$smarty->assign('max_upload_size', formatFileSize($max_size));
			
			if (@$_GET['path'] == '')
				$_GET['path'] = $dir;
			
			if (@is_dir($_GET['path'])) {
				// Create Folder
				if (isset($_POST['create_dir']) && trim($_POST['create_dir_name']) != '' && !is_dir($_GET['path'].'/'.$_POST['create_dir_name'])) {
					@mkdir($_GET['path'].'/'.$_POST['create_dir_name'], 0777);
					@chmod($_GET['path'].'/'.$_POST['create_dir_name'], 0777);
				}
				
				// Upload File
				if (isset($_POST['upload_file'])) {
					$ul = new Upload();
					$ul->allowed_types = '*';
					$ul->dir = $_GET['path'].'/';
					$ul->max_byte_size = $max_size;
					$result = $ul->uploadFile();
					if (0 != $result)
						$notify->add($lang->get('fileadmin'), 'Upload failed: '.$result);
				}
				
				// Delete File
				if (isset($_POST['delete_file_filename'])) {
					unlink($_GET['path'].'/'.$_POST['delete_file_filename']);
				}
				
				// Delete Folder
				if (isset($_POST['delete_dir'])) {
					@rmdir($_GET['path']);
				}
			}
			
			// Make directory tree
			$root['name'] = 'fileadmin';
			$root['path'] = $dir;
			$root['url'] = makeURL($mod, array('path' => $dir));
			if ($_GET['path'] == $dir)
				$root['current'] = true;
			@$children = makeDirectoryTree($root, $_GET['path']);
			$root['children'] = $children;
			$smarty->assign('tree', $root);
			
			// Make file list
			@$path = is_dir($_GET['path']) ? $_GET['path'] : $dir;
			$filelist = makeFileList($path);
			$smarty->assign('files', $filelist);
			
			// Make URL list
		}
	}
	else {
		$notify->add($lang->get('fileadmin'), $lang->get('no_access'));
	}
	
?>