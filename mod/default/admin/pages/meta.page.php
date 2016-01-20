<?php

	@$tag_id = (int)$_GET['tagid'];
	@$action = $_GET['action'];
	
	$breadcrumbs->addElement($lang->get('meta_tags'), makeURL($mod, array('mode' => 'meta')));
	
	// Available names
	$meta_names = array('google-site-verification', 'keywords', 'description', 'Copyright', 'Author', 'Robots', 'revisit-after');
	sort($meta_names);
	$smarty->assign('meta_names', $meta_names);
	
	// Available http equivs
	$equiv_names = array('content-language', 'content-style-type', 'Content-Type', 'expires');
	sort($equiv_names);
	$smarty->assign('equiv_names', $equiv_names);
	
	// get a list of available languages
	$langlist = $lang->listLanguages();
	$smarty->assign('languages', $langlist);
	
	// available domains
	$smarty->assign('dlist', getDomainList());
	
	switch ($action) {
		case 'add':
			$breadcrumbs->addElement($lang->get('add'), makeURL($mod, array('mode' => 'meta', 'action' => 'add')));
			$smarty->assign('path', $template_dir . "/meta.edit.tpl");
			
			if (isset($_POST['save'])) {
				$db->insert('meta', array('name', 'domainid', 'http_equiv', 'content', 'language'), array("'".$_POST['name']."'", (int)$_POST['domainid'], "'".$_POST['http_equiv']."'", "'".$_POST['content']."'", "'".$_POST['language']."'"));
				redirect(makeURL($mod, array('mode' => 'meta')));
			}
			
			break;
		case 'edit':
			$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('mode' => 'meta', 'action' => 'edit', 'tagid' => $tag_id)));
			$smarty->assign('path', $template_dir . "/meta.edit.tpl");
			
			if (isset($_POST['save'])) {
				$db->update('meta', 
					"`name`='".secureMySQL($_POST['name'])."', 
					`domainid`=".(int)$_POST['domainid'].", 
					`http_equiv`='".secureMySQL($_POST['http_equiv'])."', 
					`content`='".secureMySQL($_POST['content'])."', 
					`language`='".secureMySQL($_POST['language'])."'",
					"`tagid`=".$tag_id);
				redirect(makeURL($mod, array('mode' => 'meta')));
			}
			
			$tag = $db->selectOneRow('meta', '*', '`tagid`='.$tag_id);
			$smarty->assign('item', $tag);
			
			break;
		default:
			$items = $db->selectList('meta');
			$smarty->assign('items', $items);
			$smarty->assign('path', $template_dir . "/meta.list.tpl");
		break;
	}
	
?>