<?php
	
	$dir = 'media/fileadmin';
	
	function makeDirectoryTree($directory, $path) {
		$dirlist = scandir($directory['path']);
		$tree = array();
		foreach ($dirlist as $dir) {
			if ($dir != '.' && $dir != '..' && is_dir($directory['path'].'/'.$dir)) {
				$element = array();
				$element['name'] = $dir;
				$element['path'] = $directory['path'].'/'.$dir;
				if ($element['path'] == $path)
					$element['current'] = true;
				$element['children'] = makeDirectoryTree($element, $path);
				$element['url'] = makeURL('fileadmin', array('path' => $element['path']));
				$tree[] = $element;
			}
		}
		return $tree;
	}
	
	function makeFileList($path) {
		$filelist = scandir($path);
		$list = array();
		foreach ($filelist as $file) {
			if (!is_dir($path.'/'.$file)) {
				$item = array();
				$item['name'] = $file;
				$item['extension'] = pathinfo($path.'/'.$file, PATHINFO_EXTENSION);
				$item['size'] = filesize($path.'/'.$file);
				$item['size_str'] = formatFileSize($item['size']);
				$item['url'] = $path.'/'.$file;
				$list[] = $item;
			}
		}
		return $list;
	}
	
	function makeTree($directory) {
		if (!is_dir($directory['path']))
			return;
		@$dirlist = scandir($directory['path']);
		$tree = array();
		foreach ($dirlist as $dir) {
			if ($dir != '.' && $dir != '..') {
				$element = array();
				$element['dir'] = is_dir($directory['path'].'/'.$dir);
				$element['size'] = !is_dir($directory['path'].'/'.$dir) ? filesize($directory['path'].'/'.$dir) : 0;
				$element['name'] = $dir;
				$element['path'] = $directory['path'].'/'.$dir;
				$element['children'] = makeTree($element);
				$element['url'] = makeURL('fileadmin', array('path' => $element['path']));
				$tree[] = $element;
			}
		}
		return $tree;
	}
?>