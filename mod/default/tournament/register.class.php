<?php

	class Register
	{
		
		private $table;
		private $table_g;
		private $table_gr;
		
		function __construct()
		{
			$this->table = MYSQL_TABLE_PREFIX . 'tournamentregister';
			$this->table_g = MYSQL_TABLE_PREFIX . 'tournamentgroups';
			$this->table_gr = MYSQL_TABLE_PREFIX . 'tournamentgroupregister';
		}
		
		function getRegCount($tournamentid)
		{
			global $db;
			
			$tournament = $db->selectOneRow(MYSQL_TABLE_PREFIX . 'tournamentlist', "*", "`tournamentid`=".(int)$tournamentid);
			if ($tournament['playerperteam'] == 1) {
				return $db->num_rows($this->table, "`tournamentid`=".(int)$tournamentid);
			}
			else {
				return $db->num_rows($this->table_g, "`tournamentid`=".(int)$tournamentid);
			}
		}
		
		function joinTournament($tournamentid)
		{
			global $db;
			global $login;
			global $tCredit;
			global $tournament;
			global $notify;
			global $lang;
			
			if ($tournament['state'] != 1)
				return;
				
			if ($login->currentUser() === false)
				return;
				
			if (!$this->alreadyJoined($tournamentid)) {
				if ($tCredit->getValue($login->currentUserId(), $tournament['eventid']) >= $tournament['credits']) {
					$db->insert($this->table, array("tournamentid", "memberid"), array($tournamentid, $login->currentUserID()));
					$tCredit->decrement($login->currentUserId(), $tournament['eventid'], $tournament['credits']);
				}
				else {
					$notify->add($lang->get('tournament'), $lang->get('out_of_credits'));
				}
			}
		}
		
		function leaveTournament($tournamentid)
		{
			global $db;
			global $login;
			global $tCredit;
			global $tournament;
			
			if ($tournament['state'] != 1)
				return;
			
			if ($login->currentUser() === false)
				return;
			
			$db->delete($this->table, "`tournamentid`=".(int)$tournamentid . " AND `memberid`=" . $login->currentUserID());
			$db->delete($this->table_gr, "`tournamentid`=".(int)$tournamentid." AND `memberid`=" . $login->currentUserID());
			$group = $db->selectOneRow($this->table_g, "*", "`tournamentid`=".$tournamentid." AND `founderid`=".$login->currentUserID());
			if ($group != null) {
				$db->delete($this->table_gr, "`tournamentid`=".(int)$tournamentid." AND `groupid`=".$group['groupid']);
				$db->delete($this->table_g, "`groupid`=".$group['groupid']);
			}
			
			$tCredit->increment($login->currentUserId(), $tournament['eventid'], $tournament['credits']);
		}
		
		function alreadyJoined($tournamentid)
		{
			global $db;
			global $login;
			
			if ($login->currentUser() === false)
				return;
			
			$tournament = $db->selectOneRow(MYSQL_TABLE_PREFIX . 'tournamentlist', "*", "`tournamentid`=".(int)$tournamentid);
			
			if ($tournament['playerperteam'] == 1)
			{
				if ($db->num_rows($this->table, "`tournamentid`=".(int)$tournamentid . " AND `memberid`=" . $login->currentUserID()) > 0)
					return true;
				else
					return false;
			} else {
			
				if ( $db->num_rows($this->table_gr, "`tournamentid` = ".(int)$tournamentid." AND `memberid` = ".$login->currentUserID()) > 0 )
					return true;
				else
					return false;
			}
		}
		
		function getPlayerList($tournament)
		{
			global $db;
			$tbl_reg = MYSQL_TABLE_PREFIX . "tournamentregister";
			$tbl_users = MYSQL_TABLE_PREFIX . "users";
			
			$list = array();
			
			if ($tournament['playerperteam'] == 1)
			{
				$result = $db->query("SELECT * FROM `".$tbl_reg."`, `".$tbl_users."`
						   WHERE `".$tbl_reg."`.`tournamentid`=".$tournament['tournamentid']." 
						   AND `".$tbl_users."`.`userid`=`".$tbl_reg."`.`memberid`");
				
				while ($row = mysql_fetch_assoc($result))
				{
					$row['url'] = makeURL('profile', array('userid' => $row['userid']));
					$list[] = $row;
				}
					
				return $list;
			}
			else
			{
				$groups = $db->selectList($this->table_g, "*", "`tournamentid`=".$tournament['tournamentid']);
				foreach ($groups as $i => $group) {
					$groups[$i]['members'] = $db->num_rows($this->table_gr, "`groupid`=".$group['groupid']);
					$groups[$i]['nickname'] = $group['name'];
					$groups[$i]['url'] = makeURL('tournament', array('tournamentid' => $tournament['tournamentid'], 'groupid' => $group['groupid'], 'mode' => 'viewgroup'));
				}
				
				return $groups;
			}
		}
		
		function getParticipants($tournament)
		{
			global $db;
			$tbl_reg = MYSQL_TABLE_PREFIX . "tournamentregister";
			$tbl_g = MYSQL_TABLE_PREFIX . 'tournamentgroups';
			$tbl_gr = MYSQL_TABLE_PREFIX . 'tournamentgroupregister';
			$tbl_users = MYSQL_TABLE_PREFIX . "users";
			
			if ($tournament['playerperteam'] == 1)
			{
				$result = $db->query("SELECT * FROM `".$tbl_reg."`, `".$tbl_users."`
						   WHERE `".$tbl_reg."`.`tournamentid`=".$tournament['tournamentid']." 
						   AND `".$tbl_users."`.`userid`=`".$tbl_reg."`.`memberid`");
				
				$list = array();
				while ($row = mysql_fetch_assoc($result))
				{
					$player = new singlePlayer($row);
					$list[] = $player;
				}
				
				return $list;
			} else {
				$raw = $db->selectList($tbl_g, "*", 
				"`tournamentid`=".$tournament['tournamentid']);
				
				$list = array();
				if (null != $raw && count($raw) > 0) {
					foreach ($raw as $l) {
						$player = new group($l, 0, $tournament['tournamentid']);
						$list[] = $player;
					}
				}
				
				return $list;
			}
		}
		
		function createGroup($name, $password, $tournamentid, $description = '')
		{
			global $db;
			global $login;
			global $tCredit;
			global $tournament;
			
			if ($tournament['state'] != 1)
				return;
			
			if ($name == '')
				return;
			
			if ($tCredit->getValue($login->currentUserId(), $tournament['eventid']) >= $tournament['credits']) {
			
				$db->insert($this->table_g, array('name', 'password', 'founderid', 'description', 'tournamentid'),
							array("'".$name."'", "'".md5($password)."'", $login->currentUserID(), "'".$description."'", $tournamentid));
							
				return mysql_insert_id();
				
			}
		}
		
		function joinGroup($tournament, $groupid, $password = '')
		{
			global $db;
			global $login;
			global $tCredit;
			global $tournament;
			global $notify;
			global $lang;
			
			if ($tournament['state'] != 1)
				return;
			
			if ($login->currentUser() === false)
				return;
			
			if (!$this->alreadyJoined($tournament['tournamentid']))
			{
				$group = $db->selectOneRow($this->table_g, "*" , "`groupid`=".(int)$groupid);
				
				if ($db->num_rows($this->table_gr, "`tournamentid`=".$tournament['tournamentid']." AND `groupid`=".(int)$groupid) < $tournament['playerperteam']) {				
					if (null != $group) {
						if (md5($password) == $group['password']) {
							if ($tCredit->getValue($login->currentUserId(), $tournament['eventid']) >= $tournament['credits']) {
								$db->insert($this->table_gr, array('groupid', 'tournamentid', 'memberid'),
									array((int)$groupid, $tournament['tournamentid'], (int)$login->currentUserID()));
								$tCredit->decrement($login->currentUserId(), $tournament['eventid'], $tournament['credits']);
							}
							else {
								$notify->add($lang->get('tournament'), $lang->get('out_of_credits'));
							}
						}
					}
				}
			}
		}
		
		function listAllGroups($tournamentid)
		{
			global $db;
			
			$registered = $db->selectList($this->table, "*", "`tournamentid`=".(int)$tournamentid);
			$groups = $db->selectList($this->table_g);
			
			if (count($groups) > 0)
			foreach ($groups as $group)
			{
				$found = false;
				if (count($registered) > 0)
				foreach ($registered as $reg)
				{
					if ($groups['groupid'] == $reg['groupid'])
					{
						$found = true;
						break;
					}
				}
				
				if (!$found)
					$notregistered[] = $group;
			}
			
			return $notregistered;
		}
		
		
		function removeUnfilledGroups($tournament) {
			global $db;
			
			$groups = $db->selectList($this->table_g, "*", "`tournamentid`=".$tournament['tournamentid']);
			if(count($groups)>0){
				foreach ($groups as $i => $group) {
					if($tournament['playerperteam'] > $db->num_rows($this->table_gr, "`groupid`=".$group['groupid'])) {
						$db->delete($this->table_g, "`groupid`=".$group['groupid']);
						$db->delete($this->table_gr, "`groupid`=".$group['groupid']);
					}
				}
			}
			
			return $this->getParticipants($tournament);			
		}	
	}

?>