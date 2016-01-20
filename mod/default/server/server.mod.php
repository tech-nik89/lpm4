<?php
	
	@$serverid = (int)$_GET['serverid'];
	@$mode = $_GET['mode'];
	$tbl = MYSQL_TABLE_PREFIX . 'servers';
	
	$isallowed = $rights->isAllowed($mod, 'manage');
	
	$lang->addModSpecificLocalization($mod);
	$breadcrumbs->addElement($lang->get('servers'), makeURL($mod));
	
	
	switch ($mode) {
		case 'removeserver':
			if (!$isallowed)
				break;
			
			if (isset($_POST['yes'])) {
				$db->delete($tbl, "`serverid`=".$serverid);
				$notify->add($lang->get('servers'), $lang->get('removeserver_done'));
			} 
			else {
			
				$smarty->assign('path', $template_dir."/removeserver.tpl");
				$server = $db->selectOneRow($tbl, "*", "`serverid`=".$serverid);
				
				$breadcrumbs->addElement($server['name'], makeURL($mod, array('serverid' => $serverid)));
				$breadcrumbs->addElement($lang->get('removeserver'), makeURL($mod, array('mode' => 'removeserver', 'serverid' => $serverid)));
				$smarty->assign('url_no', makeURL($mod, array('serverid' => $serverid)));
				
			}
			
			break;
		case 'editserver':
			if (!$isallowed)
				break;
			
			$menu->addSubElement($mod, $lang->get('removeserver'), 'removeserver', array('serverid' => $serverid));
			
			if (isset($_POST['save']) && trim($_POST['name']) != '') {
				$db->update($tbl, 
					"`name`='".secureMySQL($_POST['name'])."',
					`description`='".secureMySQL($_POST['description'])."',
					`game`='".secureMySQL($_POST['game'])."',
					`gameq`='".secureMySQL($_POST['gameq'])."',
					`ipadress`='".secureMySQL($_POST['ipadress'])."',
					`port`='".secureMySQL($_POST['port'])."'",
					"`serverid`=".$serverid);
				$notify->add($lang->get('server'), $lang->get('save_done'));
			}
			
			require_once($mod_dir."/games.php");
			$smarty->assign('games', $games);
			
			$smarty->assign('path', $template_dir."/addeditserver.tpl");
			$server = $db->selectOneRow($tbl, "*", "`serverid`=".$serverid);
			$smarty->assign('server', $server);
			
			$breadcrumbs->addElement($server['name'], makeURL($mod, array('serverid' => $serverid)));
			$breadcrumbs->addElement($lang->get('editserver'), makeURL($mod, array('mode' => 'editserver', 'serverid' => $serverid)));
			
			break;
		case 'addserver':
			if (!$isallowed)
				break;
			
			if (isset($_POST['save']) && trim($_POST['name']) != '') {
				$db->insert($tbl, array('name', 'description', 'game', 'gameq', 'ipadress', 'port', 'userid'),
					array("'".$_POST['name']."'", "'".$_POST['description']."'", "'".$_POST['game']."'", "'".$_POST['gameq']."'",  
						"'".$_POST['ipadress']."'", (int)$_POST['port'], $login->currentUserId()));
				$notify->add($lang->get('server'), $lang->get('save_done'));
			}
			
			require_once($mod_dir."/games.php");
			$smarty->assign('games', $games);
			
			$menu->addSubElement($mod, $lang->get('addserver'), 'addserver');
			$breadcrumbs->addElement($lang->get('addserver'), makeURL($mod, array('mode' => 'addserver')));
			$smarty->assign('path', $template_dir."/addeditserver.tpl");
			
			break;
			
		default:
		
			if ($serverid == 0) {
				if ($isallowed)
					$menu->addSubElement($mod, $lang->get('addserver'), 'addserver');
				
				$smarty->assign('path', $template_dir."/default.tpl");
				$serverlist = $db->selectList($tbl, "*");
				if (null != $serverlist && count($serverlist) > 0) {
					foreach ($serverlist as $i => $server) {
						$serverlist[$i]['url'] = makeURL($mod, array('serverid' => $server['serverid']));
					}
				}
				$smarty->assign('serverlist', $serverlist);
			}
			else {
				if ($isallowed)
					$menu->addSubElement($mod, $lang->get('editserver'), 'editserver', array('serverid' => $serverid));
					
				$smarty->assign('path', $template_dir."/view.tpl");
				$server = $db->selectOneRow($tbl, "*", "`serverid`=".$serverid);
				$server['description'] = $bbcode->parse($server['description']);
				$smarty->assign('server', $server);
				
				$breadcrumbs->addElement($server['name'], makeURL($mod, array('serverid' => $serverid)));
			}
	}
?>