<?php
	
	$breadcrumbs->addElement($lang->get('comments'), makeURL($mod, array('mode' => 'comments')));
			
	if (isset($_POST['remove']))
		$comments->remove($_POST['commentid']);
	
	if (isset($_POST['go'])) {
		$clist = $comments->find($_POST['find']);
		$smarty->assign('find', $_POST['find']);
	}
	else {
	
		$cpp = (int)$config->get('admin', 'comments-per-page');
		$cpp = $cpp > 0 ? $cpp : 10;
		$all = $comments->countAll();
		

		@$page = (int)$_GET['page'];
		if ($page == 0) $page = 1;
		
		$pages->setValues($page, $cpp, $all);
		
		@$dir = secureMySQL($_GET['dir']);
		@$order = secureMySQL($_GET['order']);
		
		if ($order == '')
			$order = 'timestamp';
		
		$other_dir = $dir == 'DESC' ? 'DESC' : 'ASC';
		
		$sort['nickname'] = makeURL($mod, array('mode' => 'comments', 'order' => 'userid', 'dir' => $other_dir));
		$sort['mod'] = makeURL($mod, array('mode' => 'comments', 'order' => 'mod', 'dir' => $other_dir));
		$sort['comment'] = makeURL($mod, array('mode' => 'comments', 'order' => 'text', 'dir' => $other_dir));
		$sort['timestamp'] = makeURL($mod, array('mode' => 'comments', 'order' => 'timestamp', 'dir' => $other_dir));
		
		$clist = $comments->getAll($order, $dir, $pages->currentValue(), $cpp);
		
		$smarty->assign('sort', $sort);
	}
	
	$smarty->assign('comments', $clist);
	$smarty->assign('path', $template_dir . "/comments.tpl");
	$smarty->assign('pages', $pages->get($mod, array('mode' => 'comments')));
	
?>