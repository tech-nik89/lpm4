<?php
	
	$search = array('http://', 'www.', '/');
	$replace = '';
	
	$breadcrumbs->addElement($lang->get('domains'), makeURL($mod, array('mode' => 'domains')));
	
	$menu->addSubElement($mod, $lang->get('add'), 'domains', array('action' => 'add'));
	$smarty->assign('dlist', getDomainList());
	
	$smarty->assign('languages', $lang->listLanguages());
	
	switch ($action) {
		case 'add':
			$breadcrumbs->addElement($lang->get('add'), makeURL($mod, array('mode' => 'domains', 'action' => 'add')));
			$smarty->assign('path', $template_dir.'/edit.domains.tpl');
			
			if (isset($_POST['save'])) {
				@$name = str_replace($search, $replace, trim($_POST['name']));
				@$comment = (trim($_POST['comment']));
				@$template = (trim($_POST['template']));
				@$alias = (int)$_POST['alias'];
				@$l = $_POST['language'];
				
				if ($name != '') {
					$db->insert('domains', array('name', 'comment', 'template', 'alias', 'language'), array("'".$name."'", "'".$comment."'", "'".$template."'", $alias, "'".$l."'"));
					redirect(makeURL($mod, array('mode' => 'domains')));
				}
			}
			break;
		case 'edit':
			@$domainid = (int)$_GET['domainid'];
			
			$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('mode' => 'domains', 'action' => 'edit', 'domainid' => $domainid)));
			$smarty->assign('path', $template_dir.'/edit.domains.tpl');
			
			$domain = $db->selectOneRow('domains', '*', '`domainid`='.$domainid);
			$smarty->assign('domain', $domain);
			
			if (isset($_POST['save'])) {
				@$name = secureMySQL(str_replace($search, $replace, trim($_POST['name'])));
				@$comment = secureMySQL(trim($_POST['comment']));
				@$template = secureMySQL(trim($_POST['template']));
				@$alias = (int)$_POST['alias'];
				@$l = secureMySQL(trim($_POST['language']));
				
				if ($name != '') {
					$db->update('domains', "`name`='".$name."', `comment`='".$comment."', `template`='".$template."', `alias`=".$alias.", `language`='".$l."'", "`domainid`=".$domainid);
					redirect(makeURL($mod, array('mode' => 'domains')));
				}
			}
			break;
		case 'delete':
			@$domainid = (int)$_GET['domainid'];
			$breadcrumbs->addElement($lang->get('delete'), makeURL($mod, array('mode' => 'domains', 'action' => 'delete', 'domainid' => $domainid)));
			$smarty->assign('path', $template_dir.'/delete.domains.tpl');
			
			if (isset($_POST['yes'])) {
				$db->delete('domains', '`domainid`='.$domainid);
				redirect(makeURL($mod, array('mode' => 'domains')));						
			}
			
			break;
		default:
			$smarty->assign('path', $template_dir.'/domains.tpl');
			$domains = $db->selectList('domains');
			
			foreach ($domains as $i => $domain) {
				$domains[$i]['alias_name'] = $db->selectOne('domains', 'name', '`domainid` = '.(int)$domain['alias']);
			}
			
			$smarty->assign('domains', $domains);
			break;
	}
	
	// list available templates
	$smarty->assign('tlist', listTemplates());
	
?>