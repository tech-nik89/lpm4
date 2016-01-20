<?php
	
	/* HFH Clan Module */
	
	$lang->addModSpecificLocalization($mod);
	$mode = $_GET['mode'];
	@$clanid = (int)$_GET['clanid'];
	
	require_once($mod_dir."/clan.function.php");
	$breadcrumbs->addElement($lang->get('clans'), makeURL($mod));
	
	$hasClan = userHasClan($login->currentUserId());
	$smarty->assign('loggedin', $login->currentUser() !== false);
	
	switch ($mode) {
		default:
			
			if ($clanid == 0) {
				if ($login->currentUser() !== false && !$hasClan) {
					$menu->addSubElement($mod, $lang->get('newclan'), 'newclan');
				}
				
				$smarty->assign('path', $template_dir."/default.tpl");
				
				$clanlist = $db->selectList("clan`, ".MYSQL_TABLE_PREFIX."users`", 
					"*",
					"`userid` = `leaderid`",
					"`name` DESC");
				if (isset($clanlist) && count($clanlist) > 0) {
					foreach ($clanlist as $i => $clan) {
						$clanlist[$i]['url'] = makeURL($mod, array('clanid' => $clan['clanid']));
					}
				}
				$smarty->assign('clanlist', $clanlist);
			}
			else {
				
				$smarty->assign('myid', $login->currentUserId());
				$smarty->assign('path', $template_dir."/clan.tpl");
				$clan = $db->selectOneRow('clan', "*", "`clanid`=".$clanid);
			
				if (!$hasClan && isset($_POST['join'])) {
					if ($clan['password'] == md5($_POST['password'])) {
						$db->insert('clan_member',
							array('clanid', 'userid'),
							array($clanid, $login->currentUserId())
						);
						addClanPrefix($login->currentUserId(), $clan['prefix']);
						$hasClan = true;
					}
					else {
						$notify->add($lang->get('clan'), $lang->get('badpassword'));
					}
				}
				
				if (isset($_POST['leave'])) {
					$db->delete('clan_member', "`userid`=".$login->currentUserId()." AND `clanid`=".$clanid);
					removeClanPrefix($login->currentUserId(), $clan['prefix']);
					$hasClan = false;
				}
				
				$breadcrumbs->addElement($clan['name'], makeURL($mod, array('clanid' => $clanid)));
				$clan['leader'] = $user->getUserById($clan['leaderid']);
				$clan['leader']['url'] = makeURL('profile', array('userid' => $clan['leaderid']));
				$clan['members'] = $db->selectList("clan_member`, `".MYSQL_TABLE_PREFIX."users",
					"*", "`".MYSQL_TABLE_PREFIX."users`.`userid`=`".MYSQL_TABLE_PREFIX."clan_member`.`userid`
					AND `clanid`=".$clanid);
				if (isset($clan['members']) && count($clan['members']) > 0) {
					foreach ($clan['members'] as $i => $member) {
						$clan['members'][$i]['url'] = makeURL('profile', array('userid' => $member['userid']));
					}
				}
				$smarty->assign('clan', $clan);
				
				$smarty->assign('myclan', isMyClan($clanid));
				
				if ($clan['leaderid'] == $login->currentUserId()) {
					$menu->addSubElement($mod, $lang->get('editclan'), 'editclan', array('clanid' => $clan['clanid']));
					$menu->addSubElement($mod, $lang->get('removeclan'), 'removeclan', array('clanid' => $clan['clanid']));
				}
			}
			break;
			
		case 'editclan':
			
			$clan = $db->selectOneRow('clan', "*", "`clanid`=".$clanid);
			if ($clan['leaderid'] != $login->currentUserId())
				break;
			
			if (isset($_POST['save']) && trim($_POST['name'])) {
				$pw = "";
				if (trim($_POST['password'])) {
					$pw = "`password`='".md5($_POST['password'])."',";
				}
				$db->update("clan",
					"`name`='".secureMySQL($_POST['name'])."',"
					.$pw."
					`description`='".secureMySQL($_POST['description'])."'",
					"`clanid`=".$clanid);
				
				$clan['name'] = $_POST['name'];
				$clan['description'] = $_POST['description'];
				
				$notify->add($lang->get('clans'), $lang->get('editclan_done'));
			}
			
			$breadcrumbs->addElement($clan['name'], makeURL($mod, array('clanid' => $clanid)));
			$breadcrumbs->addElement($lang->get('editclan'), makeURL($mod, array('clanid' => $clanid, 'mode' => 'editclan')));
			
			$smarty->assign('clan', $clan);
			$smarty->assign('path', $template_dir."/addedit.tpl");
			$smarty->assign('mode', 'edit');
			
			break;
		
		case 'removeclan':	
			
			$clan = $db->selectOneRow('clan', "*", "`clanid`=".$clanid);
			if ($clan['leaderid'] != $login->currentUserId())
				break;
			
			if (isset($_POST['yes'])) {
				if ($config->get('clan', 'enable-prefix') == '1') {
					$members = $db->selectList('clan_member', "*", "`clanid`=".$clanid);
					if (isset($members) && count($members) > 0) {
						foreach ($members as $member) {
							removeClanPrefix($member['userid'], $clan['prefix']);
						}
					}
				}
				$db->delete('clan_member', "`clanid`=".$clanid);
				$db->delete('clan', "`clanid`=".$clanid);
				redirect(makeURL($mod));
			}
			
			$breadcrumbs->addElement($clan['name'], makeURL($mod, array('clanid' => $clanid)));
			$breadcrumbs->addElement($lang->get('removeclan'), makeURL($mod, array('clanid' => $clanid, 'mode' => 'removeclan')));
			
			$smarty->assign('path', $template_dir."/remove.tpl");
			$smarty->assign('url', array('no' => makeURL($mod, array('clanid' => $clanid))));
			
			break;
			
		case 'newclan':
			
			if ($login->currentUser() === false || $hasClan)
				break;
			
			if (isset($_POST['save'])) {
				if (trim($_POST['name']) != '') {
					if ($config->get('clan', 'enable-prefix') == '0' || strpos($_POST['prefix'], $config->get('clan', 'prefix-seperator')) == 0) {
						$db->insert('clan',
							array('prefix', 'name', 'description', 'leaderid', 'password'),
							array("'".$_POST['prefix']."'", "'".$_POST['name']."'", 
								"'".$_POST['description']."'", $login->currentUserId(),
								"'".md5($_POST['password'])."'")
						);
						$id = mysql_insert_id();
						$db->insert('clan_member',
							array('clanid', 'userid'),
							array($id, $login->currentUserId())
						);
						addClanPrefix($login->currentUserId(), $_POST['prefix']);
						redirect(makeURL($mod));
					}
					else {
						$notify->add($lang->get('clans'), $lang->get('prefix_error'));
					}
				}
			}
			
			$breadcrumbs->addElement($lang->get('newclan'), makeURL($mod, array('mode' => 'newclan')));
			
			$smarty->assign('path', $template_dir."/addedit.tpl");
			
			break;
	}
	
	$smarty->assign('hasclan', $hasClan);
	
?>