<?php

	require_once("mod/default/media/media.function.php");
	if (!$login->doLogin($_GET['email'], generatePasswordHash($_GET['password']), 0)) {
		die('LOGINFAILED');
	}
	
	$manageAllowed = $rights->isallowed('media', 'manage');
	$uploadAllowed = $rights->isallowed('media', 'upload');
	
	switch (@$_GET['mode']) {
		case 'uploadfile':
			if ($uploadAllowed) {
				$id = (int)$_GET['categoryid'];
				$category = getCategory($id);
				
				$upload = new Upload();
				
				if (@$_GET['type'] == 'image') {
					$upload->dir = 'media/images/'.$category['uniqid'].'/';
				}
				else {
					$upload->dir = 'media/download/'.$category['uniqid'].'/';
				}
				
				if (!is_dir($upload->dir)) {
					mkdir($upload->dir, 0777, true);
				}
				@chmod($upload->dir, 0777);
				$upload->tag_name = 'file';
				$upload->allowed_types = '(.*)';
				
				if ($config->get('media', 'max-upload-size') > 0)
					$upload->max_byte_size = $config->get('media', 'max-upload-size');
				else
					$upload->max_byte_size = 10485760;
					
				$msg = $upload->uploadFile();
				
				switch ($msg) {
					case 0:
						if (@$_GET['type'] != 'image') {
							@addDownload($id, $upload->file_name, '', $upload->file_name);
						}
						echo 'OK';
						break;
					default:
						echo 'ERROR';
						break;
				}
			}
			else {
				echo 'NOTALLOWED';
			}
			
			break;
		case 'createcategory':
			if ($manageAllowed) {
				if (syncCreateCategory($_GET['email'], $_GET['password'], $_GET['categoryname'], $_GET['parentid'])) {
					echo 'OK';
				}
				else {
					echo 'ERROR';
				}
			}
			else {
				echo 'NOTALLOWED';
			}
			break;
		case 'getcategories':

			$categories = createCategoryTreeParented($_GET['email'], $_GET['password']);
			echo "<categories>";
			echo $categories;
			echo "</categories>";
			
			break;
		default:
			$enable_downloads = isset($_GET['syncdownloads']) && $_GET['syncdownloads']=='True';
			$enable_images = isset($_GET['syncimages']) && $_GET['syncimages']=='True';
			$enable_movies = isset($_GET['syncmovies']) && $_GET['syncmovies']=='True';
			
			$data = createMediaTreeXml($_GET['email'], $_GET['password'], $enable_downloads, $enable_images, $enable_movies);
			
			echo "<files>\n";
			if($data) {
				foreach($data as $element) {
					echo("\t<file 
						name=\"".$element['name']."\" 
						path=\"".$element['path']."\" 
						internalpath=\"".$element['path_internal']."/".$element['name']."\" 
						filesize=\"".$element['filesize']."\" 
						hash=\"".$element['hash']."\" 
						parentid=\"".$element['parentid']."\" 
						categoryid=\"".$element['categoryid']."\" />\n");
				}
			}
			echo "</files>\n";
			
			break;
	}
	
?>