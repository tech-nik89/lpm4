<?php
	
	// add a breadcrumb
	$breadcrumbs->addElement($lang->get('groups'), makeURL($mod, array('mode' => 'groups')));
	
	// include the template
	$smarty->assign('path', $template_dir . '/groups.tpl');
	
	// Add matrix submenus
	$menu->addSubElement($mod, $lang->get('group_matrix'), 'groups', array('action' => 'group_matrix'));
	$menu->addSubElement($mod, $lang->get('user_matrix'), 'groups', array('action' => 'user_matrix'));
	
	switch ($action)
	{
		case 'edit':

			// save changes of group
			if (isset($_POST['group_save']) and trim($_POST['name']) != '')
				@$rights->editGroup($_GET['groupid'], $_POST['name'], $_POST['description'], $_POST['display'], $_POST['admin']);
			
			// get group details
			$group = $rights->getGroup($_GET['groupid']);
			$smarty->assign('group', $group);
			
			// add a breadcrumb
			$breadcrumbs->addElement($group['name'], makeURL($mod, array( 'mode' => 'groups', 'action' => 'edit', 'groupid' => $group['groupid'])));
			
			// save rights
			if (isset($_POST['rights_save']))
			{
				$arights = $rights->getAllRights();
				foreach ($arights as $r)
				{
					if (@$_POST[$r['mod'] . "_" . $r['name']] == "1")
						$rights->assignRightToGroup($group['groupid'], $r['mod'], $r['name']);
					else
						$rights->removeRightFromGroup($group['groupid'], $r['mod'], $r['name']);
				}
			}
			
			// get all rights
			$arights = $rights->getAllRights();

			// get rights of this group
			$grights = $rights->getRights($_GET['groupid']);
			
			// add "isallowed" if group has this right
			if (count($arights) > 0)
				foreach ($arights as $i => $a)
				{
					if (count($grights) > 0)
						foreach ($grights as $j => $g)
						{
							if ($a['mod'] == $g['mod'] && $a['name'] == $g['name'])
							{
								$arights[$i]['isallowed'] = 1;
								break;
							}
						}
				}
			
			// assign to template
			$smarty->assign('rightlist', $arights);
			
			break;
		
		case 'group_matrix':
			
			$breadcrumbs->addElement($lang->get('group_matrix'), makeURL($mod, array('action' => 'group_matrix', 'mode' => 'groups')));
			
			$smarty->assign('path', $template_dir."/group_matrix.tpl");
			$tbl_g = MYSQL_TABLE_PREFIX . 'groups';
			$tbl_gr = MYSQL_TABLE_PREFIX . 'group_rights';
			$tbl_r = MYSQL_TABLE_PREFIX . 'rights';
			
			$rlist = $db->selectList($tbl_r, "*");
			$glist = $db->selectList($tbl_g, "*");
			
			$list['&nbsp;']['&nbsp;'] = '&nbsp;';
			if (null != $glist && count($glist) > 0) {
				foreach ($glist as $g) {
					$list['&nbsp;'][$g['groupid']] = makeHTMLURL('<strong>'.$g['name'].'</strong>', makeURL($mod, array('mode' => 'groups', 'action' => 'edit', 'groupid' => $g['groupid'])));
				}
			}
			
			if (null != $rlist && count($rlist) > 0) {
				foreach ($rlist as $r) {
					$list[$r['mod'].' / '.$r['name']]['name'] = $r['mod'] . ' ~ ' . $r['name'];
					if (null != $glist && count($glist) > 0) {									
						foreach ($glist as $g) {
							$list[$r['mod'].' / '.$r['name']][$g['name']] = "&nbsp;";
						}
					}
				}
			}
			
			$sql = "SELECT `".$tbl_g."`.`groupid`, `mod`, `".$tbl_gr."`.`name` AS `right_name`, `".$tbl_g."`.`name` AS `group_name` 
					FROM `".$tbl_gr."` INNER JOIN `".$tbl_g."` ON `".$tbl_g."`.`groupid` = `".$tbl_gr."`.`groupid`
					ORDER BY `".$tbl_g."`.`groupid`;";
			$result = $db->query($sql);
			
			while ($row = mysql_fetch_assoc($result)) {
				$list[$row['mod'].' / '.$row['right_name']][$row['group_name']] = 'X'; 
			}
			
			$smarty->assign('matrix', $list);
			
			break;
			
		case 'user_matrix':
			
			$breadcrumbs->addElement($lang->get('user_matrix'), makeURL($mod, array('action' => 'user_matrix', 'mode' => 'groups')));
			
			$smarty->assign('path', $template_dir."/user_matrix.tpl");
			
			$tbl_g = MYSQL_TABLE_PREFIX . 'groups';
			$tbl_gu = MYSQL_TABLE_PREFIX . 'group_users';
			$tbl_u = MYSQL_TABLE_PREFIX . 'users';
			
			$glist = $db->selectList($tbl_g, "*");
			$ulist = $db->selectList($tbl_gu . "`, `" . $tbl_u, "*", "`".$tbl_u."`.`userid`=`".$tbl_gu."`.`userid` GROUP BY `".$tbl_u."`.`userid`");
			
			$list['&nbsp;']['&nbsp;'] = '&nbsp;';
			if (null != $glist && count($glist) > 0) {
				foreach ($glist as $g) {
					$list['&nbsp;'][$g['groupid']] = makeHTMLURL('<strong>'.$g['name'].'</strong>', makeURL($mod, array('mode' => 'groups', 'action' => 'edit', 'groupid' => $g['groupid'])));
				}
			}
			
			if (null != $ulist && count($ulist) > 0) {
				foreach ($ulist as $u) {
					$list[$u['userid']][0] = makeHTMLURL($u['nickname'], makeURL($mod, array('mode' => 'users', 'action' => 'memberships', 'userid' => $u['userid'])));
					if (null != $glist && count($glist) > 0) {
						foreach ($glist as $g) {
							$list[$u['userid']][$g['groupid']] = "&nbsp;";
						}
					}
				}
			}
			
			$rlist = $db->selectList($tbl_gu, "*");
			
			foreach ($rlist as $r) {
				$list[$r['userid']][$r['groupid']] = 'X'; 
			}
			
			$smarty->assign('matrix', $list);
			
			break;
			
		case 'memberships':
			
			$group = $rights->getGroup($_GET['groupid']);
			$smarty->assign('path', $template_dir ."/group_memberships.tpl");
			$breadcrumbs->addElement($group['name'], makeURL($mod, array( 'mode' => 'groups', 'action' => 'edit', 'groupid' => $group['groupid'])));
			$breadcrumbs->addElement($lang->get('options_memberships'), makeURL($mod, array('mode' => 'groups', 'action' => 'memberships', 'groupid' => $group['groupid'])));
			
			$tbl_gu = MYSQL_TABLE_PREFIX . 'group_users';
			$tbl_u = MYSQL_TABLE_PREFIX . 'users';
			
			$list = $db->selectList($tbl_u . "`, `" . $tbl_gu, "*", "`".$tbl_gu."`.`groupid`=".$group['groupid']." AND `".$tbl_gu."`.`userid` = `".$tbl_u."`.`userid`");
			if (null != $list && count($list) > 0) {
				foreach ($list as $i => $l) {
					$list[$i]['url'] = makeURL($mod, array('mode' => 'users', 'action' => 'memberships', 'userid' => $l['userid']));
				}
			}
			$smarty->assign('list', $list);
			
			break;
		
		case 'remove':
		
			// remove right
			$rights->removeGroup($_GET['groupid']);
			
			$action = '';
			
		default:
			
			// add group if we want
			if (isset($_POST['group_new']) and trim($_POST['name']) != '')
				@$rights->createGroup($_POST['name'], $_POST['description'], $_POST['display'], $_POST['admin']);
			
			
			$g = $rights->getAllGroups();
			foreach ($g as $i => $v)
			{
				$g[$i]['url_edit'] = makeURL($mod, array('mode' => 'groups', 'action' => 'edit', 'groupid' => $v['groupid']));
				$g[$i]['url_remove'] = makeURL($mod, array('mode' => 'groups', 'action' => 'remove', 'groupid' => $v['groupid']));
				$g[$i]['url_memberships'] = makeURL($mod, array('mode' => 'groups', 'action' => 'memberships', 'groupid' => $v['groupid']));
			}	
			$smarty->assign('groups', $g);
	}
	
?>