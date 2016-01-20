<?php
	
	/**
	* Project: Higher For Hire
	* File: room.mod.php
	*/
	
	require_once($mod_dir."/room.function.php");
	$lang->addModSpecificLocalization($mod);
	$roomid = isset($_GET['roomid']) ? (int)$_GET['roomid'] : 0;
	$mode = $_GET['mode'];
	$tbl_r = MYSQL_TABLE_PREFIX.'room';
	$tbl_i = MYSQL_TABLE_PREFIX.'room_item';
	$tbl_e = MYSQL_TABLE_PREFIX.'events';
	
	
	$breadcrumbs->addElement($lang->get('room'), makeURL($mod));
	$isallowed = $rights->isAllowed($mod, 'manage');
	$isallowedtoseatppl = $rights->isAllowed($mod, 'move-users');
		
	switch ($mode)
	{
		case 'add':
			
			if (!$isallowed)
				break;
			
			$breadcrumbs->addElement($lang->get('room_add'), makeURL($mod, array('mode' => 'add')));
			
			if (isset($_POST['do']))
			{
				if ($_POST['title'] != '' && (int)$_POST['height'] > 0 && (int)$_POST['width'] > 0)
				{
					$db->insert($tbl_r, array('title', 'description', 'height', 'width', 'eventid'),
										array("'".$_POST['title']."'", "'".$_POST['description']."'", (int)$_POST['height'], (int)$_POST['width'], (int) $_POST['event']));
				}	$notify->add($lang->get('room'), $lang->get('add_done'));
			}

			$smarty->assign('events', $db->selectList($tbl_e));
			
			$smarty->assign('path',  $template_dir."/add_edit.tpl");
			$smarty->assign('action', $lang->get('room_add'));
			
			break;
		default:
			$room = $db->selectOneRow($tbl_r, "*", "`roomid`=".$roomid);
			if ($roomid == 0 || $room == null)
			{
				if($roomid > 0) {
					$notify->add($lang->get('room'), $lang->get('ntfy_room_does_not_exist'));
				}
				$eventList = $db->selectList('events', "*");
				foreach ($eventList as $event) {
					$menu->addSubElement($mod, $event['name'], makeURL($mod, array('eventid' => $event['eventid'])));
				}
				
				// Show room overview
				$smarty->assign('path', $template_dir."/overview.tpl");
				if (@(int)$_GET['eventid'] > 0) {
					$roomList = $db->selectList($tbl_r, '*', "`eventid`=".(int)$_GET['eventid'], 'title ASC');
				}
				else {
					$roomList = $db->selectList($tbl_r, '*', 1, 'eventid ASC, title ASC');
				}
				
				if (count($roomList) > 0){
					foreach ($roomList as $i => $room){
						$roomList[$i]['url'] = makeURL($mod, array('roomid' => $room['roomid']));
						$roomList[$i]['event'] = $db->selectOneRow($tbl_e,'*','eventid='.$room['eventid']);
						$roomList[$i]['free_tables'] = ($roomList[$i]['event']>0)?countThisRoomsFreeSeats($room['roomid']):'&nbsp;';
					}
				}
				
				$smarty->assign('roomList', $roomList);
				
				// Room add menu subitem
				if ($isallowed)
					$menu->addSubElement($mod, $lang->get('room_add'), 'add');
			}
			else
			{
				$smarty->assign('show_coordinates', $config->get("room", "show-coordinates"));
				$smarty->assign('rowindex', makeIndex($room['height'], $config->get("room", "y-axis-format")));
				$smarty->assign('columnindex', makeIndex($room['width'], $config->get("room", "x-axis-format")));
				switch ($mode)
				{				
					case 'items':
						if (!$isallowed)
							break;
						$smarty->assign('path', $template_dir."/edit_items.tpl");
						
						$smarty->assign('mod_dir', $mod_dir);

						$breadcrumbs->addElement($room['title'], makeURL($mod, array('roomid' => $roomid)));
						$breadcrumbs->addElement($lang->get('edit_items'), makeURL($mod, array('roomid' => $roomid, 'mode' => 'items')));
						$smarty->assign('room', $room);
						
						if (isset($_POST['save']))
						{
							saveItems($room);
						}
						
						$items = $db->selectList($tbl_i, "*", "`roomid`=".$roomid);
						$smarty->assign('items', $items);
						
						$matrix = makeItemMatrix($items, $room['height'], $room['width']);
						$smarty->assign('matrix', $matrix);
						$smarty->assign('typematrix', getTypeMatrix());
						
						$roomlist = $db->selectList($tbl_r, "*", 'eventid='.$room['eventid']);
						$smarty->assign('roomlist', $roomlist);
						
						$smarty->assign('image_white',itemTypeToImage(0));
						break;
					
					case 'edit':
						
						if (!$isallowed)
							break;
						
						$menu->addSubElement($mod, $lang->get('room_remove'), 'remove', array('roomid' => $roomid));
						
						if (isset($_POST['do']))
						{
							if ($_POST['title'] != '' && (int)$_POST['height'] > 0 && (int)$_POST['width'] > 0)
							{
								$db->update($tbl_r, 
									"`title`='".secureMySQL($_POST['title'])."',
									`description`='".secureMySQL($_POST['description'])."',
									`height`=".(int)$_POST['height'].",
									`width`=".(int)$_POST['width'].",
									`eventid`=".(int)$_POST['event'],
									"`roomid`=".$roomid);
							}
						}
						
						$smarty->assign('path', $template_dir."/add_edit.tpl");
						$smarty->assign('action', $lang->get('room_edit'));
	
						$room = $db->selectOneRow($tbl_r, "*", "`roomid`=".$roomid);
	
						$smarty->assign('room', $room);
						$smarty->assign('events', $db->selectList($tbl_e));
						
						$breadcrumbs->addElement($room['title'], makeURL($mod, array('roomid' => $roomid)));
						$breadcrumbs->addElement($lang->get('room_edit'), makeURL($mod, array('roomid' => $roomid, 'mode' => 'edit')));
						
						break;
					
					case 'remove':
						if (!$isallowed)
							break;
						
						if (isset($_POST['yes']))
						{
							$db->delete($tbl_r, "`roomid`=".$roomid);
							$notify->add($lang->get('room'), $lang->get('room_removed'));
						} else {
							$smarty->assign('path', $template_dir."/remove.tpl");
							$breadcrumbs->addElement($room['title'], makeURL($mod, array('roomid' => $roomid)));
							$breadcrumbs->addElement($lang->get('room_remove'), makeURL($mod, array('roomid' => $roomid, 'mode' => 'remove')));
							$smarty->assign('url_no', makeURL($mod, array('roomid' => $roomid, 'mode' => 'edit')));
						}
						break;
					
					default:
						$userid = $login->currentUserId();
						$event = $db->selectOneRow(MYSQL_TABLE_PREFIX.'events', "*", "eventid=".$room['eventid']);
					
						$dummy = $db->selectOneRow(MYSQL_TABLE_PREFIX.'register', '*', "userid=".$userid." AND eventid=".$room['eventid']);
						$isallowedtoreserve = (($event['free'] == 1 && $login->currentUser() !== false) || $dummy['payed'] > 0);
						$isallowedtositdown = $dummy['appeared'] != 0;
				
						
						if(isset($_POST['reserve']))	
						{
							if(isSittingAlready($room['eventid'], 13, $userid))
							{
								undoReservation($userid, $room['eventid']);
							}
							makeReservation($userid, $_GET['roomid'], $_POST['x'], $_POST['y']);			
						}
						
						if(isset($_POST['unreserve']))	
						{
							undoReservation($userid, $room['eventid']);
						}
						
						if(isset($_POST['sitdown']))	
						{
							if(isSittingAlready($room['eventid'], 11, $userid))
							{
								standUp($userid, $room['eventid']);
							}
							if(isSittingAlready($room['eventid'], 13, $userid))
							{
								undoReservation($userid, $room['eventid']);
							}
							sitDown($userid, $_GET['roomid'], $_POST['x'], $_POST['y']);			
						}
						
						if(isset($_POST['standup']))	
						{
							standUp($userid, $room['eventid']);
						}
						
						//Remove and seat other ppl
						if($isallowedtoseatppl) {
							if(isset($_POST['remove'])){
								removeUser($_GET['roomid'], $_POST['x'], $_POST['y']);
							}
						
							if(isset($_POST['placeUser'])){
								$couldseat=seatUser($_GET['roomid'], $room['eventid'], $_POST['x'], $_POST['y'], $_POST['userid']);
								$notify->add($lang->get('room'), $lang->get($couldseat));
							}
						}
						$smarty->assign("remove_and_add_users", ($room['eventid']<=0)?false:$isallowedtoseatppl);
							
						// show room with id = roomid
						if ($isallowed)
						{
							$menu->addSubElement($mod, $lang->get('edit_items'), 'items', array('roomid' => $roomid));
							$menu->addSubElement($mod, $lang->get('room_edit'), 'edit', array('roomid' => $roomid));
						}
						$smarty->assign('path', $template_dir."/room.tpl");
						$breadcrumbs->addElement($room['title'], makeURL($mod, array('roomid' => $roomid)));
						$smarty->assign('room', $room);
						$items = $db->selectList($tbl_i, "*", "`roomid`=".$roomid);
						$smarty->assign('items', $items);
						
						$allready_reserved = isSittingAlready($room['eventid'], 13, $userid);						
						$allready_sitting = isSittingAlready($room['eventid'], 11, $userid);
						
						$smarty->assign('only_reserving', ($isallowedtoreserve && !$isallowedtositdown));
						$smarty->assign('allow_sitting', $isallowedtositdown);
						$smarty->assign('sitting_allready', $allready_sitting);
						$smarty->assign('reserved_allready', $allready_reserved);
						
						$matrix = makeItemMatrix($items, $room['height'], $room['width']);
						$smarty->assign('matrix', $matrix);
						
						//check if there should be a grid
						$smarty->assign('grid_border', $config->get('room', 'show-grid'));
						
						//count seats
						if($room['eventid'] != -1) {
							$smarty->assign('show_information', $config->get('room', 'show-information'));
							$smarty->assign('table_information', countSeats($room['eventid'], $room['roomid']));
						}
						
				}
			}
	}
	
?>