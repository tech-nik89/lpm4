<?php
	
	global $type;
	$type[0] = 'white.gif';
	$type[1] = 'entry.png'; // Info
	$type[2] = 'info.gif'; // Info
	$type[3] = 'network.gif'; // Info
	$type[4] = 'emergency_exit.gif'; // Info
	$type[5] = 'stair.gif';
	$type[6] = 'bar.gif'; // Info
	$type[7] = 'wc.png';
	$type[8] = 'wc_m.gif';
	$type[9] = 'wc_f.gif';
	$type[10] = 'table_free.gif'; // Free Table
	$type[11] = 's_bes.gif'; // Full Table
	$type[12] = 's_self_bes.gif'; // Sitting here
	$type[13] = 's_res.gif'; // reserved Table
	$type[14] = 's_self_res.gif'; // self reserved Table
	$type[15] = 'staff.gif';
	$type[20] = 'w_h.gif';
	$type[21] = 'w_v.gif';
	$type[22] = 'w_c_lt.gif';
	$type[23] = 'w_c_lb.gif';
	$type[24] = 'w_c_rt.gif';
	$type[25] = 'w_c_rb.gif';
	$type[26] = 'w_l.gif';
	$type[27] = 'w_o.gif';
	$type[28] = 'w_u.gif';
	$type[29] = 'w_r.gif';
	$type[30] = 'w_x.gif';
	$type[31] = 'w_slo.gif';
	$type[32] = 'w_slu.gif';
	$type[33] = 'w_sru.gif';
	$type[34] = 'w_sro.gif';
	$type[35] = 'w_p.gif';
	$type[40] = 'door_h.gif';
	$type[41] = 'door_v.gif';
	$type[50] = 'w_b_h.gif';
	$type[51] = 'w_b_v.gif';
	$type[60] = 'w_f_h.gif';
	$type[61] = 'w_f_v.gif';
	
	global $ignore_type;
	$ignore_type=array();
	$ignore_type[0]=11;
	$ignore_type[1]=12;
	$ignore_type[2]=13;
	$ignore_type[3]=14;
	
	function itemTypeToImage($itemType)
	{
		global $type;
		global $mod_dir;
		
		return $mod_dir."images/".$type[$itemType];
	}
	
	function getItem($item)
	{
		global $user;
		global $db;
		global $login;
		
		$i['img'] = itemTypeToImage((int)$item['type']);
		$i['type'] = $item['type'];
		if ((isset($item['value']) && $item['value'] > 0) OR $item['type']==10)
		{
			$i['real_value']=$item['value'];
			switch((int) $item['type'])
			{
				case 10: // empty table
					$i['clickable']=true;
					$i['showemptytable']=true;
					$i['userid'] = $login->currentUserId();
					$i['url'] = makeURL('room', array('roomid'=>$_GET['roomid']));
					break;
				case 11: // full table
					if($login->currentUserId() == $item['value'])
					{
						$i['img'] = itemTypeToImage(12);
						$i['imsittinghere'] = true;
						$i['standup'] = 'true';
						$i['url'] = makeURL('room', array('roomid'=>$_GET['roomid']));
					}
					else
					{
						$i['remove_user']=true;
						$i['onlyshowurl']=true;
						$i['url'] = makeURL('profile', array('userid' => $item['value']));
					}
					$i['clickable']=true;
					$user_dummy = $user->getUserById($item['value']);
					$i['value'] = $user_dummy['nickname'];
					break;
				case 13: // reserved table
					if($login->currentUserId() == $item['value'])
					{
						$i['img'] = itemTypeToImage(14);
						$i['ihavereservedhere'] = true;
						$i['undoreservation'] = 'true';
						$i['url'] = makeURL('room', array('roomid'=>$_GET['roomid']));
					}
					else
					{
						$i['remove_user']=true;
						$i['onlyshowurl']=true;
						$i['url'] = makeURL('profile', array('userid' => $item['value']));
					}
					$i['clickable']=true;
					$user_dummy = $user->getUserById($item['value']);
					$i['value'] = $user_dummy['nickname'];
					break;
				case 5: // Stairs
				case 40: case 41: // Doors
					$i['clickable']=true;
					$i['onlyshowurl']=true;
					$room_dummy = $db->selectOneRow(MYSQL_TABLE_PREFIX.'room', "*", "`roomid`=".$item['value']);
					$i['value'] = $room_dummy['title'];
					$i['url'] = makeURL('room', array('roomid' => $item['value']));
					break;

				default:
					break;
			}
		}
		return $i;
	}
	
	function makeItemMatrix($items, $height, $width)
	{
		$white['type'] = 0;
				
		for ($y = 0; $y < $height; $y++)
		{
			for ($x = 0; $x < $width; $x++)
			{
				$matrix[$y][$x]['img'] = itemTypeToImage((int) $white['type']);
				$matrix[$y][$x]['type'] = (int)$white['type'];
				$matrix[$y][$x]['x'] = $x;
				$matrix[$y][$x]['y'] = $y;
			}
		}
		
		if (count($items) > 0)
			foreach ($items as $item)
			{
				$matrix[$item['y']][$item['x']] = getItem($item);
				$matrix[$item['y']][$item['x']]['x'] = $item['x'];
				$matrix[$item['y']][$item['x']]['y'] = $item['y'];
			}
		return $matrix;
	}
	
	function getTypeMatrix()
	{
		global $type;
		global $ignore_type;
		
		$x = 0; $y = 0;
		foreach ($type as $i => $t)
		{
			if(!in_array($i, $ignore_type))
			{
				$out[$y][$x]['img'] = itemTypeToImage($i);
				$out[$y][$x]['type'] = $i;
				
				$x++;
				if ($x > 11)
				{
					$x = 0;
					$y++;
				}
			}
		}
		return $out;
	}
	
	function saveItems($room)
	{
		global $db;
		global $debug;
		$tbl_i = MYSQL_TABLE_PREFIX . 'room_item';
		$db->delete($tbl_i, "`roomid`=".$room['roomid']);
		$debug->add('height:', $room['height']);
		$debug->add('width:', $room['width']);
		for ($y = 0; $y < $room['height']; $y++)
		{
			for ($x = 0; $x < $room['width']; $x++)
			{
				$type = (int)$_POST['field_'.$y.'_'.$x];
				if ($type != 0)
				{
					$value = (int)$_POST['field_'.$y.'_'.$x.'_value'];
					$debug->add('x:'.$x.' y:'.$y, $type);
					$db->insert($tbl_i, array('roomid', 'x', 'y', 'type', 'value'),
										array($room['roomid'], $x, $y, $type, $value));
				}
			}
		}
	}

	function isSittingAlready($eventid, $type, $userid)
	{
		global $db;
		$sql="SELECT * FROM ".MYSQL_TABLE_PREFIX . "room_item AS I 
										INNER JOIN ".MYSQL_TABLE_PREFIX . "room AS R
										ON I.roomid=R.roomid
										WHERE type='".$type."' AND value='".(int)$userid."' AND eventid='".(int)$eventid."'";
		$dummy = mysql_fetch_assoc($db->query($sql));
		return isset($dummy) && $dummy!=null;
	}
	
	function sitDown($userid, $roomid, $x, $y)
	{
		global $db;
		$db->update(MYSQL_TABLE_PREFIX . 'room_item', 'type=11, value='.(int)$userid , 'roomid='.(int)$roomid.' AND x='.(int)$x.' AND y='.(int)$y);
	}
	
	function makeReservation($userid, $roomid, $x, $y)
	{
		global $db;
		
		$db->update(MYSQL_TABLE_PREFIX . 'room_item', 'type=13, value='.(int)$userid , 'roomid='.(int)$roomid.' AND x='.(int)$x.' AND y='.(int)$y);
	}
	
	function standUp($userid, $eventid)
	{
		global $db;
		$rooms = $db->selectList(MYSQL_TABLE_PREFIX . 'room', '*', 'eventid='.$eventid);
		if($rooms) {
			foreach($rooms as $room) {
				$db->update(MYSQL_TABLE_PREFIX . 'room_item', 'type=10, value='.(int)$userid , 'type=11 AND value='.(int)$userid.' AND roomid='.(int)$room['roomid']);
			}
		}
	}
	
	function undoReservation($userid, $eventid)
	{
		global $db;
		$rooms = $db->selectList(MYSQL_TABLE_PREFIX . 'room', '*', 'eventid='.(int)$eventid);
		if($rooms) {
			foreach($rooms as $room) {
				$db->update(MYSQL_TABLE_PREFIX . 'room_item', 'type=10, value='.(int)$userid , 'type=13 AND value='.(int)$userid.' AND roomid='.(int)$room['roomid']);
			}
		}
	}
	
	function removeUser($roomid, $x, $y)
	{
		global $db;
		$db->update(MYSQL_TABLE_PREFIX . 'room_item', 'type=10, value=0' , 'x='.(int)$x.' AND y='.(int)$y);
	}
		
	function seatUser($roomid, $eventid, $x, $y, $userid) {
		global $db;
		
		$userid=($userid<=0)?-1:$userid;
		$exists = $db->selectOneRow(MYSQL_TABLE_PREFIX.'users', "*", "userid=".secureMySQL((int)$userid));	

		if(!$exists){
			return "seating_nosuchuser";
		}
		
		if(isSittingAlready($eventid, 11, $userid)){
			standUp($userid, $eventid);
		}
		if(isSittingAlready($eventid, 13, $userid)){
			undoReservation($userid, $eventid);
		}
		$event = $db->selectOneRow(MYSQL_TABLE_PREFIX.'events', "*", "eventid=".(int)$eventid);		
		$dummy = $db->selectOneRow(MYSQL_TABLE_PREFIX.'register', '*', "userid=".(int)$userid." AND eventid=".(int)$eventid);
		
		$isallowedtoreserve=($event['free']==1 OR $dummy['payed']>0);
		$isallowedtositdown=$dummy['appeared']!=0;

		if($isallowedtositdown){
			sitDown($userid, $roomid, $x, $y);
			return "seating_seated";
		} elseif ($isallowedtoreserve) {
			makeReservation($userid, $roomid, $x, $y);
			return "seating_reserved";
		}
		return "seating_notpayed";
	}
	
	function countThisRoomsFreeSeats($roomid) {
		global $db;
		$tables = $db->selectOneRow(MYSQL_TABLE_PREFIX."room_item", "count(*) as free", "roomid=".(int)$roomid." AND type=10");
		return $tables['free'];
	}
		
	function countSeats($eventid, $roomid) {
		global $db;
		$event = $db->selectOneRow(MYSQL_TABLE_PREFIX."events", '*', 'eventid='.(int)$eventid);

		if($event) {
			$tables['overall'] = $event['seats'];
			$tables['all_rooms']['free'] = 0;
			$tables['all_rooms']['staff'] = 0;
			$tables['all_rooms']['full'] = 0;
			$event_rooms = $db->selectList(MYSQL_TABLE_PREFIX."room", '*', 'eventid='.(int)$eventid);
			foreach($event_rooms as $event_room) {
				$tables_free = $db->selectOneRow(MYSQL_TABLE_PREFIX."room_item", "count(*) as `tables`", "`roomid`=".$event_room['roomid']." AND `type`=10");
				$tables_staff = $db->selectOneRow(MYSQL_TABLE_PREFIX."room_item", "count(*) as `tables`", "`roomid`=".$event_room['roomid']." AND `type`=15");
				$tables_full = $db->selectOneRow(MYSQL_TABLE_PREFIX."room_item", "count(*) as `tables`", "`roomid`=".$event_room['roomid']." AND (`type`=11 OR `type`=13)");

				$tables['all_rooms']['free'] += $tables_free['tables'];
				$tables['all_rooms']['staff'] += $tables_staff['tables'];
				$tables['all_rooms']['full'] += $tables_full['tables'];
				
				if($roomid == $event_room['roomid']) {
					$tables['this_room']['free'] = $tables_free['tables'];
					$tables['this_room']['staff'] = $tables_staff['tables'];
					$tables['this_room']['full'] = $tables_full['tables'];
				}
			}		
		}
		return $tables;
	}

?>