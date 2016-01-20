<?php

	$isAllowed = $rights->isAllowed($mod, 'manage');
	$lang->addModSpecificLocalization($mod);
	$mode = @$_GET['mode'];
	@$movieid = (int)$_GET['movieid'];
	
	global $current_language, $lng;
	$lng = secureMySQL($current_language);
	
	$breadcrumbs->addElement($lang->get('movies'), makeURL($mod));
	
	switch ($mode) {
		case 'delete':
			if ($isAllowed) {
				$db->delete('movies', '`movieid` = '.$movieid);
				redirect(makeURL($mod, array('mode' => 'manage')));
				break;
			}
		case 'edit':
		case 'add':
			if ($isAllowed) {
				$smarty->assign('domains', getDomainList());
				$smarty->assign('languages', array_merge(array('' => ''), $lang->listLanguages()));
				
				$breadcrumbs->addElement($lang->get('manage_movies'), makeURL($mod, array('mode' => 'manage')));
				
				if ($mode == 'add') {
					$breadcrumbs->addElement($lang->get('add'), makeURL($mod, array('mode' => 'add')));
				}
				else {
					$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('mode' => 'edit', 'movieid' => $movieid)));
				}
				
				if (isset($_POST['save'])) {
					if ($movieid == 0) {
						$db->insert('movies',
							array('title', 'description', 'urlid', 'order', 'thumbnail', 'language', 'hidden', 'domainid'),
							array("'".$_POST['title']."'", "'".$_POST['description']."'", "'".$_POST['urlid']."'", (int)$_POST['order'], (int)$_POST['thumbnail'], "'".$_POST['language']."'", @(int)$_POST['hidden'], @(int)$_POST['domainid'])
						);
						redirect(makeURL($mod, array('mode' => 'manage')));
					}
					else {
						$db->update('movies',
							"`title`='".secureMySQL($_POST['title'])."',
							`description`='".secureMySQL($_POST['description'])."',
							`urlid`='".secureMySQL($_POST['urlid'])."',
							`order`=".(int)$_POST['order'].",
							`thumbnail`=".(int)$_POST['thumbnail'].",
							`language`='".secureMySQL($_POST['language'])."',
							`hidden`=".@(int)$_POST['hidden'].",
							`domainid`=".@(int)$_POST['domainid'],
							"`movieid`=".$movieid);
					}
				}
				
				if ($movieid > 0) {
					$movie = $db->selectOneRow('movies', '*', 'movieid = '.$movieid);
					$smarty->assign('movie', $movie);
				}
				
				$smarty->assign('path', $template_dir.'/manage_movie.tpl');
			}
			
			break;
		case 'manage':
			if ($isAllowed) {
				$breadcrumbs->addElement($lang->get('manage_movies'), makeURL($mod, array('mode' => 'manage')));
				$movies = $db->selectList('movies', '*', '1', '`order` ASC');
				$smarty->assign('movies', $movies);
				$smarty->assign('path', $template_dir.'/manage.tpl');
				
				break;
			}
		default:
			if ($isAllowed) {
				$menu->addSubElement($mod, $lang->get('manage_movies'), 'manage');
			}
			
			if ($movieid == 0) {
				$movies = $db->selectList('movies', '*', "`hidden`=0 AND (`language` = '' OR `language` = '".$lng."') AND (`domainid` = 0 OR `domainid`=".@(int)getCurrentDomainIndex().")", '`order` ASC');
				foreach ($movies as $i => $movie) {
					$movies[$i]['description'] = $bbcode->parse($movie['description']);
				}
				$smarty->assign('movies', $movies);
				
				$smarty->assign('path', $template_dir.'/index.tpl');
				$smarty->assign('columns', $config->get($mod, 'columns'));
			}
			else {
				$smarty->assign('hd', $config->get($mod, 'high-definition'));
				$smarty->assign('autoplay', $config->get($mod, 'autoplay'));
				
				$movie = $db->selectOneRow('movies', '*', '`movieid` = '.$movieid);
				$movie['description'] = $bbcode->parse($movie['description']);
				$smarty->assign('movie', $movie);
				
				$breadcrumbs->addElement($movie['title'], makeURL($mod, array('movieid' => $movieid)));
				$smarty->assign('path', $template_dir.'/movie.tpl');
			}
			
		break;
	}

?>