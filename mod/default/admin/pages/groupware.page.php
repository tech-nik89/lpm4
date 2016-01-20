<?php
	
	$menu->addSubElement($mod, $lang->get('newtask'), 'groupware', array('action' => 'newtask'));
			
	$tbl_groupware = MYSQL_TABLE_PREFIX . "groupware";
	$breadcrumbs->addElement($lang->get('groupware'), makeURL($mod, array('mode' => 'groupware')));
	
	$states = array(0 => $lang->get('state_0'), 1 => $lang->get('state_1'), 2 => $lang->get('state_2'), 
					3 => $lang->get('state_3'), 4 => $lang->get('state_4')  );
	
	$priority = array(0 => $lang->get('low'), 1 => $lang->get('normal'), 2 => $lang->get('high'));
	
	switch (@$_GET['action'])
	{
		case 'view':
			
			$smarty->assign('path', $template_dir . "/viewtask.tpl");
			$smarty->assign('state', $states);
			$smarty->assign('priority', $priority);
			
			$task = $db->selectOneRow($tbl_groupware, "*", "`groupwareid`=" . (int)$_GET['taskid']);
			$task['end'] = timeLeft($task['end']);
			$task['state'] = $states[$task['state']];
			$task['priority'] = $priority[$task['priority']];
			$task['description'] = $bbcode->parse($task['description']);
			
			$smarty->assign('task', $task);
			
			$smarty->assign('url_reference', makeURL($mod, array('mode' => 'contact', 'contactid' => $task['contactid'])));
			
			$smarty->assign('url_edit', makeURL($mod, array('mode' => 'groupware', 'action' => 'edittask', 'taskid' => $task['groupwareid'])));
			$smarty->assign('url_remove', makeURL($mod, array('mode' => 'groupware', 'action' => 'removetask', 'taskid' => $task['groupwareid'])));
			$breadcrumbs->addElement($task['title'], makeURL($mod, array('mode' => 'groupware', 'action' => 'view', 'taskid' => $task['groupwareid'])));
			
			break;
		
		case 'edittask':
			
			if (isset($_POST['save']) && trim($_POST['title']) != '')
			{
				
				$end = mktime($_POST['Time_Hour'], $_POST['Time_Minute'], 0, $_POST['Date_Month'], $_POST['Date_Day'], $_POST['Date_Year']);
				$db->update($tbl_groupware, "`title`='" . secureMySQL($_POST['title']) . "', `description`='" . secureMySQL($_POST['description']) . "', 
							`end`=" . $end . ", `state`=" . (int)$_POST['state'] . ", `priority`=" . (int)$_POST['priority'] . ", `userid`=" . (int)$_POST['private'], "`groupwareid`=" . (int)$_GET['taskid']);
				
				$notify->add($lang->get('groupware'), $lang->get('changes_saved'));
				
			}
			
			$smarty->assign('path', $template_dir . "/edittask.tpl");
			$task = $db->selectOneRow($tbl_groupware, "*", "`groupwareid`=" . (int)$_GET['taskid']);
			$smarty->assign('task', $task);
			
			$breadcrumbs->addElement($task['title'], makeURL($mod, array('mode' => 'groupware', 'action' => 'view', 'taskid' => $task['groupwareid'])));
			$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('mode' => 'groupware', 'action' => 'edittask', 'taskid' => $task['groupwareid'])));
			$smarty->assign('state', $states);
			$smarty->assign('priority', $priority);
			$smarty->assign('userid', $login->currentUserID());
			
			
			break;
		
		case 'newtask':
			
			if (isset($_POST['save']) && trim($_POST['title']) != '')
			{
				$end = mktime($_POST['Time_Hour'], $_POST['Time_Minute'], 0, $_POST['Date_Month'], $_POST['Date_Day'], $_POST['Date_Year']);
				$db->insert($tbl_groupware, array('title', 'description', 'state', 'end', 'priority', 'userid'),
						array("'".$_POST['title']."'", "'".$_POST['description']."'", (int)$_POST['state'], $end, (int)$_POST['priority'], (int)$_POST['private']));
				
				$notify->add($lang->get('groupware'), $lang->get('newtask_added'));
			}
			
			$breadcrumbs->addElement($lang->get('newtask'), makeURL($mod, array('mode' => 'groupware', 'action' => 'newtask')));
			
			$smarty->assign('path', $template_dir . "/newtask.tpl");
			$smarty->assign('state', $states);
			$smarty->assign('priority', $priority);
			$smarty->assign('userid', $login->currentUserID());
			
			break;
		
		case 'removetask':
			
			if (isset($_POST['yes']))
			{						
				$db->delete($tbl_groupware, "`groupwareid`=" . (int)$_GET['taskid']);
				$notify->add($lang->get('groupware'), $lang->get('task_removed'));
			}
			else
			{
				$task = $db->selectOneRow($tbl_groupware, "*", "`groupwareid`=" . (int)$_GET['taskid']);
				
				$breadcrumbs->addElement($task['title'], makeURL($mod, array('mode' => 'groupware', 'action' => 'view', 'taskid' => $task['groupwareid'])));
				$breadcrumbs->addElement($lang->get('remove'), makeURL($mod, array('mode' => 'groupware', 'action' => 'removetask', 'taskid' => $task['groupwareid'])));
				
				$smarty->assign('path', $template_dir . "/removetask.tpl");
				break;
			}
		
		default:
		
			$smarty->assign('url_newtask', makeURL($mod, array('mode' => 'groupware', 'action' => 'newtask')));
			$smarty->assign('path', $template_dir . "/groupware.tpl");
			
			$order = isset($_GET['order']) ? $_GET['order'] : '';
			if ($order == '')
				$order = 'end';
				
			$dir = isset($_GET['dir']) ? $_GET['dir'] : '';
			if ($dir == '')
				$dir = 'ASC';
				
			if ($dir == 'ASC')
				$other_dir = 'DESC';
			else
				$other_dir = 'ASC';
			
			$show_done = isset($_GET['show_done']) ? (int)$_GET['show_done'] : 0;
			if ($show_done == 0)
			{
				$show_done_not = 1;
				$where = "`state` != 2 AND (`userid`=0 OR `userid`=".$login->currentUserID().")";
			}
			else
			{
				$show_done_not = 0;
				$where = "(`userid`=0 OR `userid`=".$login->currentUserID().")";
			}
			
			$smarty->assign('show_done', $show_done);
			
			$smarty->assign('url_order_title', makeURL($mod, array('mode' => 'groupware', 'order' => 'title', 'dir' => $other_dir, 'show_done' => $show_done)));
			$smarty->assign('url_order_end', makeURL($mod, array('mode' => 'groupware', 'order' => 'end', 'dir' => $other_dir, 'show_done' => $show_done)));
			$smarty->assign('url_order_priority', makeURL($mod, array('mode' => 'groupware', 'order' => 'priority', 'dir' => $other_dir, 'show_done' => $show_done)));
			$smarty->assign('url_order_state', makeURL($mod, array('mode' => 'groupware', 'order' => 'state', 'dir' => $other_dir, 'show_done' => $show_done)));
			$smarty->assign('url_show_done', makeURL($mod, array('mode' => 'groupware', 'order' => $order, 'dir' => $dir, 'show_done' => ($show_done_not))));
			
			$tasks = $db->selectList($tbl_groupware, "*", $where, "`".$order . "` " . $dir);
			
			if (count($tasks) > 0)
				foreach ($tasks as $i => $task)
				{
					$tasks[$i]['end'] = timeLeft($task['end']);
					$tasks[$i]['priority'] = $priority[$task['priority']];
					$tasks[$i]['state'] = $states[$task['state']];
					$tasks[$i]['url'] = makeURL($mod, array('mode' => 'groupware', 'action' => 'view', 'taskid' => $task['groupwareid']));
				}
			
			$smarty->assign('tasks', $tasks);
	}
	
?>