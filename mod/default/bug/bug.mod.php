<?php
	
	// Bug tracker module
	$issuesPerPage = 20;
	
	require_once($mod_dir."/bug.function.php");
	
	$lang->addModSpecificLocalization($mod);
	$breadcrumbs->addElement($lang->get('bugtracker'), makeURL($mod));
	@$projectid = (int)$_GET['projectid'];
	@$issueid = (int)$_GET['issueid'];
	$mode = $_GET['mode'];
	
	$menu->addSubElement($mod, $lang->get('find'), 'find');
	
	$colors = array('#FF4545', '#F5D000', '#0087F5', '#FFFF00', '#FF00BB', '#09FF00', '#FFFFFF');
	$smarty->assign('colors', $colors);
	
	$isallowed = $rights->isAllowed($mod, 'manage');
	$smarty->assign('isallowed', $isallowed);
	
	$users = $rights->getUsersByRight($mod, 'manage');
	$smarty->assign('assignToList', $users);
	
	if ($isallowed && $mode == 'cleanup') {
		$smarty->assign('path', $template_dir."/cleanup.tpl");
		$breadcrumbs->addElement($lang->get('cleanup'), makeURL($mod, array('mode' => 'cleanup')));
		if (isset($_POST['sbtRemoveClosed'])) {
			$db->delete('bugtracker_issues', "`state` = 6");
			$notify->add($lang->get('bugtracker'), $lang->get('remove_closed_done'));
		}
	}
	elseif ($mode == 'find') {
		$breadcrumbs->addElement($lang->get('find'), makeURL($mod, array('mode' => 'find')));
		$smarty->assign('find_url', makeURL('find', array('engine' => 'bug')));
		$smarty->assign('path', $template_dir."/find.tpl");
	}
	else {
		if (0 == $projectid) {
			if (0 == $issueid) {
				switch ($mode) {
					default:
						if ($isallowed) {
							$menu->addSubElement($mod, $lang->get('cleanup'), 'cleanup');
							$menu->addSubElement($mod, $lang->get('addProject'), 'addProject');
							$menu->addSubElement($mod, $lang->get('categories'), 'categories');
						}
						
						$projects = $db->selectList('bugtracker_projects', "*", 1, "`name` ASC");
						foreach ($projects as $i => $project) {
							$projects[$i]['url'] = makeURL($mod, array('projectid' => $project['projectid']));
							$projects[$i]['issues'] = $db->num_rows('bugtracker_issues', "`projectid`=".$project['projectid']);
							$projects[$i]['issues_new'] = $db->num_rows('bugtracker_issues', "`projectid`=".$project['projectid']." AND `state`=0");
							$projects[$i]['issues_assigned'] = $db->num_rows('bugtracker_issues', "`projectid`=".$project['projectid']." AND `state`=1");
							$projects[$i]['issues_solved'] = $db->num_rows('bugtracker_issues', "`projectid`=".$project['projectid']." AND `state`=5");
							@$projects[$i]['issues_solved_relative'] = round($projects[$i]['issues_solved'] / $projects[$i]['issues'] * 100, 1);
						}
						$smarty->assign('projects', $projects);
						$smarty->assign('path', $template_dir."/overview.tpl");
						break;
						
					case 'categories':
						if (!$isallowed)
							break;
						
						$menu->addSubElement($mod, $lang->get('categories'), 'categories');
						
						if (isset($_POST['save'])) {
							$categories = $db->selectList('bugtracker_categories');
							foreach ($categories as $category) {
								if ('1' == @$_POST['delete_'.$category['categoryid']]) {
									$db->delete('bugtracker_categories', "`categoryid`=".$category['categoryid']);
								}
								else {
									if ($category['name'] != $_POST['category_'.$category['categoryid']]) {
										$db->update('bugtracker_categories', "`name`='".secureMySQL($_POST['category_'.$category['categoryid']])."'",
											"`categoryid`=".$category['categoryid']);
									}
								}
							}
							if (allFilled($_POST['category_new'])) {
								$db->insert('bugtracker_categories', array('name'), array("'".$_POST['category_new']."'"));
							}
						}
						
						$breadcrumbs->addElement($lang->get('categories'), makeURL($mod, array('mode' => 'categories')));
						$smarty->assign('path', $template_dir."/categories.tpl");
						$categories = $db->selectList('bugtracker_categories');
						$smarty->assign('categories', $categories);
						
						break;
						
					case 'addProject':
						if (!$isallowed)
							break;
						
						$menu->addSubElement($mod, $lang->get('addProject'), 'addProject');
						$breadcrumbs->addElement($lang->get('addProject'), makeURL($mod, array('mode' => 'addProject')));
						$smarty->assign('path', $template_dir."/edit.tpl");
						
						if (isset($_POST['save']) && '' != trim($_POST['name'])) {
							$db->insert('bugtracker_projects', array('name', 'description'), array("'".$_POST['name']."'", "'".$_POST['description']."'"));
							$notify->add($lang->get('project'), $lang->get('addProjectDone'));
							$project['name'] = $_POST['name'];
							$project['description'] = $_POST['description'];
							$smarty->assign('project', $project);
						}
						break;
				}
			}
			else {
				
				$issue = $db->selectOneRow('bugtracker_issues', '*', "`issueid`=".$issueid);
				if ($issue != null) {
					
					if ($isallowed) {
						if (isset($_POST['sbtAssignTo'])) {
							$db->update('bugtracker_issues', "`assignedto`=".(int)$_POST['txtAssignTo'], "`issueid`=".$issueid);
							if ($issue['state'] == 0)
								$db->update('bugtracker_issues', "`state`=1", "`issueid`=".$issueid);
								
							if ($config->get('bug', 'send-mail') == '1') {
								$assignedToUser = $user->getUserById((int)$_POST['txtAssignTo']);
								$eMail->setVar('summary', $issue['summary']);
								$eMail->setVar('project', $db->selectOne('bugtracker_projects', 'name', "`projectid`=".$issue['projectid']));
								$eMail->setVar('issueid', $issue['issueid']);
								$eMail->setVar('url', getSelfUrl().makeURL('bug', array('issueid' => $issue['issueid'])));
								$eMail->send(
									$lang->get('mail_notify_assignment_subject'),
									$lang->get('mail_notify_assignment_text'),
									$assignedToUser['email']);
							}
						}
						if (isset($_POST['sbtSetState'])) {
							$db->update('bugtracker_issues', "`state`=".(int)$_POST['txtState'], "`issueid`=".$issueid);
						}
						if (isset($_POST['sbtRemove'])) {
							$db->delete('bugtracker_issues', "`issueid`=".$issueid);
							redirect(makeURL($mod));
						}
					}
					
					if (isset($_POST['sbtAddComment'])) {
						$comments->add($mod, $login->currentUserID(), $_POST['txtNewComment'], $issueid);
					}
					
					$smarty->assign('notes', $comments->get($mod, $issueid));
					
					$issue = $db->selectOneRow('bugtracker_issues', '*', "`issueid`=".$issueid);
					$project = $db->selectOneRow('bugtracker_projects', '*', "`projectid`=".$issue['projectid']);
					$reporter = $user->getUserById($issue['userid']);
					
					if ($issue['userid'] == $login->currentUserId())
						$smarty->assign('iReporter', true);
					
					$issue['reporter'] = $reporter;
					$issue['issueid'] = addZeros($issue['issueid']);
					$issue['description'] = $bbcode->parse($issue['description']);
					$issue['additional'] = $bbcode->parse($issue['additional']);
					$issue['category'] = $db->selectOne('bugtracker_categories', 'name', "`categoryid`=".$issue['categoryid']);
					
					$breadcrumbs->addElement($project['name'], makeURL($mod, array('projectid' => $project['projectid'])));
					$breadcrumbs->addElement('#'.addZeros($issue['issueid']), makeURL($mod, array('issueid' => $issueid)));
					
					$smarty->assign('path', $template_dir."/issue.tpl");
					
					$smarty->assign('issue', $issue);
					$smarty->assign('project', $project);
				}
				
			}
		}
		else {
			$project = $db->selectOneRow('bugtracker_projects', "*", "`projectid`=".$projectid);
			$breadcrumbs->addElement($project['name'], makeURL($mod, array('projectid' => $projectid)));
			
			if ($isallowed) {
				$menu->addSubElement($mod, $lang->get('editProject'), 'editProject', array('projectid' => $projectid));
			}
			
			switch ($mode) {
				default:
					if ($login->currentUser() !== false)
						$menu->addSubElement($mod, $lang->get('addIssue'), 'addIssue', array('projectid' => $projectid));
					
					$smarty->assign('path', $template_dir."/project.tpl");
					$smarty->assign('project', $project);
					$smarty->assign('categories', $db->selectList('bugtracker_categories'));
					
					
					if (isset($_POST['sbtFilter'])) {
						if ($_POST['txtState'] != '') {
							$filter[] = '`state`='.(int)$_POST['txtState'];
						}
						$sFilter['state'] = (int)$_POST['txtState'];
						if ($_POST['txtAssignedTo'] != '') {
							$filter[] = '`assignedto`='.(int)$_POST['txtAssignedTo'];
						}
						$sFilter['assignedto'] = (int)$_POST['txtAssignedTo'];
						if ($_POST['txtPriority'] != '') {
							$filter[] = '`priority`='.(int)$_POST['txtPriority'];
						}
						$sFilter['priority'] = (int)$_POST['txtPriority'];
						if ($_POST['txtEffect'] != '') {
							$filter[] = '`effect`='.(int)$_POST['txtEffect'];
						}
						$sFilter['effect'] = (int)$_POST['txtEffect'];
						if ($_POST['txtCategory'] != '') {
							$filter[] = '`categoryid`='.(int)$_POST['txtCategory'];
						}
						$sFilter['categoryid'] = (int)$_POST['txtCategory'];
						
						$sFilter['order'] = secureMySQL($_POST['txtOrder']);
						$sFilter['direction'] = secureMySQL($_POST['txtDirection']);
						
						$_SESSION['bugtracker_filter'] = $filter;
						$_SESSION['bugtracker_sfilter'] = $sFilter;
					}
					if (isset($_POST['sbtClear'])) {
						$_SESSION['bugtracker_filter'] = null;
						$_SESSIOn['bugtracker_sfilter'] = null;
					}
					if (@$filter == null && @$_SESSION['bugtracker_filter'] != null)
						$filter = $_SESSION['bugtracker_filter'];
					
					if (isset($_SESSION['bugtracker_sfilter'])) {
						$smarty->assign('filter', @$_SESSION['bugtracker_sfilter']);
					}
					else {
						$smarty->assign('filter', 
							array(	'state' => 1, 
									'categoryid' => -1, 
									'priority' => -1, 
									'assignedto' => $login->currentUserId(), 
									'effect' => -1, 
									'order' => 'priority', 
									'direction' => 'DESC'	)
										);
					}
					
					@$order = $sFilter['order'] != '' && $sFilter['direction'] != '' ? $sFilter['order'] . ' ' . $sFilter['direction'] : '';
					$order = $order == '' ? "`issueid` DESC" : $order;
					$filter[] = '`projectid`='.$projectid;
					
					@$pages->setValues((int)$_GET['page'], $issuesPerPage, $db->num_rows('bugtracker_issues', implode(' AND ', $filter)));
					
					$issues = $db->selectList('bugtracker_issues', "*", 
						implode(' AND ', $filter), $order, ($pages->currentValue()) . ", " . (int)$issuesPerPage);
					foreach ($issues as $i => $issue) {
						$issues[$i]['issueid_str'] = addZeros($issue['issueid']);
						$issues[$i]['url'] = makeURL($mod, array('issueid' => $issue['issueid']));
						$issues[$i]['category_str'] = $db->selectOne('bugtracker_categories', 'name', "`categoryid`=".$issue['categoryid']);
						$issues[$i]['comments'] = $comments->count($mod, $issue['issueid']);
					}
					$smarty->assign('issues', $issues);
					$smarty->assign('pages', $pages->get($mod, array('projectid' => $projectid)));
					
					break;
					
				case 'addIssue':
					
					if ($login->currentUser() === false)
						break;
					
					$breadcrumbs->addElement($lang->get('addIssue'), makeURL($mod, array('projectid' => $projectid, 'mode' => 'addIssue')));
					$menu->addSubElement($mod, $lang->get('addIssue'), 'addIssue', array('projectid' => $projectid));
					$smarty->assign('path', $template_dir."/addIssue.tpl");
					$smarty->assign('categories', $db->selectList('bugtracker_categories'));
					
					if (isset($_POST['submit'])) {
						$issue['categoryid'] = (int)$_POST['category'];
						$issue['reproducible'] = ($_POST['reproducible']);
						$issue['effect'] = ($_POST['effect']);
						$issue['priority'] = ($_POST['priority']);
						$issue['summary'] = ($_POST['summary']);
						$issue['description'] = ($_POST['description']);
						$issue['additional'] = ($_POST['additional']);
						
						$smarty->assign('issue', $issue);
						
						if (allFilled($_POST['category'], $_POST['summary'], $_POST['description'])) {
							$db->insert('bugtracker_issues',
								array('projectid', 'categoryid', 'reproducible', 'effect', 'priority', 'summary', 'description', 'additional', 'userid', 'timestamp'),
								array($projectid, $issue['categoryid'], "'".$issue['reproducible']."'", "'".$issue['effect']."'", "'".$issue['priority']."'",
										"'".$issue['summary']."'", "'".$issue['description']."'", "'".$issue['additional']."'", $login->currentUserID(),
										time()));
							$notify->add($lang->get('bugtracker'), $lang->get('addIssueDone'));
						}
						else {
							$notify->add($lang->get('bugtracker'), $lang->get('fill_all_fields'));
						}
					}
					
					break;
				
				case 'editProject':
					
					if (!$isallowed)
						break;
					
					$menu->addSubElement($mod, $lang->get('deleteProject'), 'deleteProject', array('projectid' => $projectid));
					
					$breadcrumbs->addElement($lang->get('editProject'), 
						makeURL($mod, array('mode' => 'editProject', 'projectid' => $projectid)));
					
					if (isset($_POST['save'])) {
						$db->update('bugtracker_projects', 
							"`name`='".secureMySQL($_POST['name'])."', 
							`description`='".secureMySQL($_POST['description'])."'",
							"`projectid`=".$project['projectid']);
						$project['name'] = $_POST['name'];
						$project['description'] = $_POST['description'];
						$notify->add($lang->get('bugtracker'), $lang->get('editProjectDone'));
					}
					
					$smarty->assign('path', $template_dir."/edit.tpl");
					$smarty->assign('project', $project);
					
					break;
					
				case 'deleteProject':
					
					if (!$isallowed)
						break;
					
					$smarty->assign('path', $template_dir."/delete.tpl");
					$smarty->assign('url_no', makeURL($mod, array('mode' => 'editProject', 'projectid' => $projectid)));
					
					if (isset($_POST['yes'])) {
						$db->delete('bugtracker_projects', "`projectid`=".$projectid);
						$db->delete('bugtracker_issues', "`projectid`=".$projectid);
						redirect(makeURL($mod));
					}
					
					break;
			}
		}
	}
?>