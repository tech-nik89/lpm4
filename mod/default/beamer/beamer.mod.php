<?php

	// Load translations
	$lang->addModSpecificLocalization($mod);
	
	require_once($mod_dir."/beamer.function.php");
	
	@$beamerid = (int)$_GET['beamerid'];
	$mode = $_GET['mode'];
	$tbl_beamerlist = MYSQL_TABLE_PREFIX . 'beamer_list';
	$isAllowed = $rights->isAllowed($mod, 'configure');
	
	if ($mode != 'run')
		$breadcrumbs->addElement($lang->get('beamer'), makeURL($mod));
	
	switch ($mode) {
		
		default:
			
			if ($beamerid == 0) {
				// Load Template
				$smarty->assign('path', $template_dir."/default.tpl");
				
				// Get Beamerlist
				$beamerlist = $db->selectList($tbl_beamerlist, "*");
				if ($isAllowed) {
					foreach ($beamerlist as $i => $beamer) {
						$beamerlist[$i]['url'] = makeURL($mod, array('beamerid' => $beamer['beamerid'], 'mode' => 'edit'));
					}
				}
				$smarty->assign('beamerlist', $beamerlist);
				
				if ($isAllowed) {
					$menu->addSubElement($mod, $lang->get('beamer_add'), 'add');
				}
			}
			
			break;
			
		case 'add':
			
			if ($isAllowed) {
				
				$breadcrumbs->addElement($lang->get('beamer_add'), makeURL($mod, array('mode' => 'add')));
				
				// Load Template
				$smarty->assign('action', $lang->get('beamer_add'));
				$smarty->assign('path', $template_dir."/add_edit.tpl");
				
				if (isset($_POST['save']) && trim($_POST['name']) != '') {
					$db->insert($tbl_beamerlist, array('name', 'description'),
						array("'".$_POST['name']."'", "'".$_POST['description']."'"));
					$notify->add($lang->get('beamer'), $lang->get('beamer_add_done'));
				}
			}
			
			break;
			
		case 'newmovie':
			
			if (!$isAllowed || $beamerid == 0)
				break;
				
			$beamer = $db->selectOneRow('beamer_list', "*", "`beamerid`=".$beamerid);
			$breadcrumbs->addElement($beamer['name'], 
				makeURL($mod, array('beamerid' => $beamerid, 'mode' => 'edit')));
			$breadcrumbs->addElement($lang->get('newmovie'), 
				makeURL($mod, array('mode' => 'newmovie', 'beamerid' => $beamerid)));
			
			$smarty->assign('files', listAvailableMovies());
			$smarty->assign('path', $template_dir."/newmovie.tpl");
			
			if (isset($_POST['btnAdd']) && trim($_POST['file']) != '') {
				$db->insert('beamer_movie',
					array('beamerid', 'file'),
					array($beamerid, "'".$_POST['file']."'"));
				$id = mysql_insert_id();
				
				$db->insert('beamer_mod',
					array('beamerid', 'duration', 'url'),
					array($beamerid, 5, 
						"'".str_replace("&amp;", "&", makeURL($mod, array('mode' => 'movie', 'movieid' => $id)))."'"
					)
				);
				
				redirect(makeURL($mod, array('mode' => 'edit', 'beamerid' => $beamerid)));
			}
			
			break;
			
		case 'movie':
			
			$smarty->assign('path', $template_dir."/movie.tpl");
			$smarty->assign('movie', $db->selectOneRow('beamer_movie', "*", "`movieid`=".(int)$_GET['movieid']));
			
			break;
			
		case 'newmessage':
			if (!$isAllowed || $beamerid == 0)
				break;
			
			$beamer = $db->selectOneRow('beamer_list', "*", "`beamerid`=".$beamerid);
			$breadcrumbs->addElement($beamer['name'], 
				makeURL($mod, array('beamerid' => $beamerid, 'mode' => 'edit')));
			$breadcrumbs->addElement($lang->get('newmessage'), 
				makeURL($mod, array('mode' => 'newmessage', 'beamerid' => $beamerid)));
				
			$smarty->assign('path', $template_dir."/newmessage.tpl");
			
			if (isset($_POST['btnAdd']) && trim($_POST['txtMessage']) != '') {
				$message = secureMySQL($_POST['txtMessage']);
				$db->insert('beamer_message',
					array('beamerid', 'text'),
					array($beamerid, "'".$message."'"));
				$id = mysql_insert_id();
				
				$db->insert('beamer_mod',
					array('beamerid', 'duration', 'url'),
					array($beamerid, 5, 
						"'".str_replace("&amp;", "&", makeURL($mod, array('mode' => 'message', 'messageid' => $id)))."'"
					)
				);
				
				redirect(makeURL($mod, array('mode' => 'edit', 'beamerid' => $beamerid)));
			
			}
			
			break;
		
		case 'message':
			
			$smarty->assign('path', $template_dir."/message.tpl");
			$smarty->assign('message', 
				$bbcode->parse(
					$db->selectOne('beamer_message', 'text', "`messageid`=".(int)$_GET['messageid'])
				)
			);
			
			break;
		
		case 'edit':
			
			if (!$isAllowed || $beamerid == 0)
				break;
				
			$beamer = $db->selectOneRow('beamer_list', "*", "`beamerid`=".$beamerid);
			$smarty->assign('beamer', $beamer);
			$breadcrumbs->addElement($beamer['name'], makeURL($mod, array('beamerid' => $beamerid, 'mode' => 'edit')));
				
			$smarty->assign('NewMessageUrl', 
				str_replace("&amp;", "&", 
					makeURL($mod, array('mode' => 'newmessage', 'beamerid' => $beamerid))
				)
			);
			/*
			$smarty->assign('NewMovieUrl', 
				str_replace("&amp;", "&", 
					makeURL($mod, array('mode' => 'newmovie', 'beamerid' => $beamerid))
				)
			);
			*/
				
			$smarty->assign('custom', false);
			
			require_once($mod_dir."/available_modules.php");
			if (isset($available) && count($available) > 0) {
				foreach ($available as $i => $av) {
					$available[$i]['url'] = str_replace("&amp;", "&", $av['url']);
				}
			}
			
			$messages = $db->selectList('beamer_message', "*",
				"`beamerid`=".$beamerid);
				
			if (isset($messages) && count($messages) > 0) {
				$available[] = array('name' => '---', 'url' => '-');
				foreach ($messages as $message) {
					$available[] = array(
						'name' => cutString($message['text'], 30),
						'url' => str_replace('&amp;', '&',
							makeURL($mod, array('mode' => 'message', 'messageid' => $message['messageid'])))
					);
				}
			}
			
			/*
			$movies = $db->selectList('beamer_movie', "*",
				"`beamerid`=".$beamerid);
				
			if (isset($movies) && count($movies) > 0) {
				$available[] = array('name' => '---', 'url' => '-');
				foreach ($movies as $movie) {
					$available[] = array(
						'name' => cutString($movie['file'], 30),
						'url' => str_replace('&amp;', '&',
							makeURL($mod, array('mode' => 'movie', 'movieid' => $movie['movieid'])))
					);
				}
			}
			*/
			
			$smarty->assign('available', $available);
			
			if (isset($_POST['remove'])) {
				$db->delete('beamer_mod', "`beamerid`=".$beamerid);
				$db->delete('beamer_list', "`beamerid`=".$beamerid);
				$notify->add($lang->get('beamer'), $lang->get('beamer_remove_done'));
			}
			else {
			
				$smarty->assign('path', $template_dir."/edit.tpl");
				
				if (isset($_POST['save'])) {
					$modlist = $db->selectList('beamer_mod', "*", "`beamerid` = ".$beamerid);
					if (count($modlist) > 0) {
						foreach ($modlist as $mod) {
							if (@$_POST['remove_'.$mod['id']] == '1') {
								$db->delete('beamer_mod', "`beamerid`=".$beamerid." AND `id`=".$mod['id']);
							}
							else {
								$db->update('beamer_mod', 
									"`order`=".(int)$_POST['order_'.$mod['id']].",
									 `duration`=".(int)$_POST['duration_'.$mod['id']].", 
									 `url`='".secureMySQL($_POST['url_'.$mod['id']])."'",
									 "`id`=".$mod['id']);
							}
						}
					}
					if (trim($_POST['url_0']) != '') {
						$db->insert('beamer_mod',
							array('beamerid', 'order', 'duration', 'url'),
							array($beamerid, (int)$_POST['order_0'], (int)$_POST['duration_0'], "'".$_POST['url_0']."'"));
					}
				}
				
				$modlist = $db->selectList('beamer_mod', "*", "`beamerid` = ".$beamerid);
				$smarty->assign('list', $modlist);

			}
				
			break;
			
		case 'run':
			
			$smarty->assign('path', $template_dir."/run.tpl");
			$modlist = $db->selectList('beamer_mod', "*", "`beamerid` = ".$beamerid);
			$smarty->assign('list', $modlist);
			
			break;
	}
	
?>