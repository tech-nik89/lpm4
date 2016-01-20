<?php
	
	// add a breadcrumb
	$breadcrumbs->addElement($lang->get('mods'), makeURL($mod, array('mode' => 'mod')));
	
	// include the template
	$smarty->assign('path', $template_dir . '/mod.tpl');
	
	@$smarty->assign('selected', $_GET['selected']);
	if (@trim($_GET['selected']) != '')
	{
		
		// add breadcrumb
		$breadcrumbs->addElement($_GET['selected'], makeURL($mod, array('mode' => 'mod', 'selected' => $_GET['selected'])));
		
		if (isset($_POST['save']))
		{
			$configlist = $config->getConfigList($_GET['selected']);
			foreach ($configlist as $c)
				$config->set($_GET['selected'], $c['key'], $_POST['value_' . $c['key']]);
			
		}
		
		 $configlist = $config->getConfigList($_GET['selected']);
		 $smarty->assign('configlist', $configlist);
		 $smarty->assign('modul', $_GET['selected']);
		
	} else {
		
		$alist = '';
		if (@trim($_GET['install']) != '')
			$this->installMod($_GET['install']);
		
		if (@trim($_GET['uninstall']) != '')
			$this->uninstallMod($_GET['uninstall']);
		
		// list modules
		$modlist = $this->listInstalled();
		
		// add core module
		$tmp['mod'] = 'core';
		$tmp['config_count'] = (int)$config->getCount('core');
		$tmp['config_url'] = makeURL($mod, array('mode' => 'mod', 'selected' => 'core'));
		$mlist[] = $tmp;
		
		/* add content module
		$tmp['mod'] = 'content';
		$tmp['config_count'] = (int)$config->getCount('content');
		$tmp['config_url'] = makeURL($mod, array('mode' => 'mod', 'selected' => 'content'));
		$mlist[] = $tmp;
		*/
		
		$available = $this->listAvailable();
		
		if (count($modlist) > 0)
		foreach ($modlist as $m)
		{
			$tmp['mod'] = $m['mod'];
			$tmp['version'] = $m['version'];
			$tmp['config_count'] = (int)$config->getCount($m['mod']);
			$tmp['config_url'] = makeURL($mod, array('mode' => 'mod', 'selected' => $m['mod']));
			$tmp['uninstall_url'] = makeURL($mod, array('mode' => 'mod', 'uninstall' => $m['mod']));
			$mlist[] = $tmp;
		}
		
		if (count($available) > 0 && $available != '')
			foreach ($available as $a)
			{
				$tmp['mod'] = $a['mod'];
				$tmp['version'] = $a['version'];
				$tmp['setup'] = makeURL($mod, array('mode' => 'mod', 'install' => $a['mod']));
				$alist[] = $tmp;
			}
		
		$smarty->assign('modlist', $mlist);
		$smarty->assign('available', $alist);
	}
	
?>