<?php
	
	/**
	 * Project: Higher For Hire
	 * File: portal.mod.php
	 *
	*/
	
	$lang->addModSpecificLocalization($mod);
	
	$breadcrumbs->addElement($lang->get('portal'), makeURL($mod));
	
	$isallowed = $rights->isAllowed($mod, 'manage');
	if ($isallowed) {
		$menu->addSubElement($mod, $lang->get('edit_portal'), 'edit');
	}
	
	if ($isallowed && @$_GET['mode'] == 'edit') {
		
		$breadcrumbs->addElement($lang->get('edit_portal'), makeURL($mod, array('mode' => 'edit')));
		
		if (isset($_POST['save'])) {
			@$config->set($mod, 'topnews', $_POST['topnews']);
			@$config->set($mod, 'news', $_POST['news']);
			@$config->set($mod, 'posts', $_POST['posts']);
			@$config->set($mod, 'poll', $_POST['poll']);
			@$config->set($mod, 'calendar', $_POST['calendar']);
			@$config->set($mod, 'article', $_POST['article']);
		}
		$cfg['topnews'] = $config->get($mod, 'topnews');
		$cfg['news'] = $config->get($mod, 'news');
		$cfg['posts'] = $config->get($mod, 'posts');
		$cfg['poll'] = $config->get($mod, 'poll');
		$cfg['calendar'] = $config->get($mod, 'calendar');
		$cfg['article'] = $config->get($mod, 'article');
		$smarty->assign('cfg', $cfg);
		$smarty->assign('path', $template_dir."/edit.tpl");
	}
	else {
	
		$cfg['topnews'] = $config->get($mod, 'topnews');
		$cfg['news'] = $config->get($mod, 'news');
		$cfg['posts'] = $config->get($mod, 'posts');
		$cfg['poll'] = $config->get($mod, 'poll');
		$cfg['calendar'] = $config->get($mod, 'calendar');
		$cfg['article'] = $config->get($mod, 'article');
	
		$smarty->assign('path', $template_dir."/portal.tpl");
		$areas = array();
		
		if ($cfg['topnews'] == '1')
			require_once($mod_dir."/areas/topnews.area.php");
		if ($cfg['news'] == '1')
			require_once($mod_dir."/areas/news.area.php");
		if ($cfg['posts'] == '1')
			require_once($mod_dir."/areas/posts.area.php");
		if ($cfg['poll'] == '1')
			require_once($mod_dir."/areas/poll.area.php");
		if ($cfg['calendar'] == '1')
			require_once($mod_dir."/areas/calendar.area.php");
		if ($cfg['article'] == '1')
			require_once($mod_dir."/areas/article.area.php");
			
		$smarty->assign('areas', $areas);
	}
?>