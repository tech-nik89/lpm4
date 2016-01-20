<?php
	
	class tournamentList
	{
		private $table;
		
		function __construct()
		{
			$this->table = 'tournamentlist';
		}
		
		function getList($eventid = 0)
		{
			global $db;
			global $register;
			
			if ((int)$eventid > 0)
				$list = $db->selectList($this->table, "*", "`eventid`=".(int)$eventid, "CASE `state`    WHEN 1 THEN 0 
																										WHEN 2 THEN 1 
																										WHEN 3 THEN 2 
																										WHEN 0 THEN 4 
																										END");
			else
				$list = $db->selectList($this->table, "*", "1", "CASE `state`   WHEN 1 THEN 0 
																				WHEN 2 THEN 1 
																				WHEN 3 THEN 2 
																				WHEN 0 THEN 4 
																				END");
			
			if (count($list)>0)
			foreach ($list as $i => $tournament)
			{
				$list[$i]['url'] = makeURL('tournament', array('tournamentid' => $tournament['tournamentid']));
				$list[$i]['state'] = tournamentStateToString($tournament['state']);
				$list[$i]['joinstate'] = $register->getRegCount($tournament['tournamentid']) . " / " . $tournament['playerlimit'];
			}
			
			return $list;
		}
		
		function addTournament($eventid, $title, $playerlimit, $game, $mappool, 
				$mode, $playerperteam, $picture, $credits, $wwclgameid, $rules, $start, $duration = 0, $breaktime = 0)
		{
			
			global $db;
			
			$db->insert($this->table, 
						array('eventid', 'title', 'playerlimit', 'game', 'mappool',
							  'mode', 'playerperteam', 'picture', 'credits', 'wwclgameid', 'rules', 
							  'start', 'duration', 'breaktime'),
						array((int)$eventid, "'".secureMySQL($title)."'", (int)$playerlimit, "'".secureMySQL($game)."'", "'".secureMySQL($mappool)."'",
							  (int)$mode, (int)$playerperteam, "'".secureMySQL($picture)."'", (int)$credits, (int)$wwclgameid, "'".secureMySQL($rules)."'", 
							  (int)$start, ((int)$duration * 60), ((int)$breaktime * 60))
						);
			return mysql_insert_id();
		}
		
		function editTournament($tournamentid, $eventid, $title, $playerlimit, $game, $mappool, 
				$mode, $playerperteam, $picture, $credits, $wwclgameid, $rules, $start, $duration = 0, $breaktime = 0)
		{
			global $db;
			
			// Delete participants if the gamemode or the playermode is changed
			$tourney = $db->selectOneRow($this->table, "playerperteam, mode", "`tournamentid`=".(int)$tournamentid);
			if($tourney['playerperteam'] != $playerperteam) {
				$db->delete("tournamentregister", "`tournamentid`=".$tournamentid);
				$db->delete("tournamentgroups", "`tournamentid`=".$tournamentid);
				$db->delete("tournamentgroupregister", "`tournamentid`=".$tournamentid);
			}
			
			$db->update($this->table, "`eventid`=".(int)$eventid.", `title`='".secureMySQL($title)."', `playerlimit`=".(int)$playerlimit.", `game`='".secureMySQL($game)."', `mappool`='".secureMySQL($mappool)."',
						`mode`=".(int)$mode.", `playerperteam`=".(int)$playerperteam.", `picture`='".secureMySQL($picture)."', `credits`=".(int)$credits.", 
						`wwclgameid`=".(int)$wwclgameid.", `rules`='".secureMySQL($rules)."', 
						`start`=".(int)$start.", `duration`=".((int)$duration * 60).", `breaktime`=".((int)$breaktime * 60), 
						"`tournamentid`=".(int)$tournamentid);
		}
		
		function setState($tournamentid, $state)
		{
			global $db;
			global $tournament_obj;
			global $tournament;
			global $lang;
			global $notify;
			global $register;
			
			if ($state == 2)
			{
				if($tournament['playerperteam']>1) {
					$tournament_obj->setParticipants($register->removeUnfilledGroups($tournament));
					$notify->add($lang->get('tournament'), $lang->get('ntfy_some_groups_had_not_enough_players'));
				}
			
				if(!$tournament_obj->createDataStructure()) {
					$notify->add($lang->get('tournament'), $lang->get('ntfy_game_start_not_enough_players'));
					return false;
				}
				if ($tournament['mode'] == 5)
					$state = 3;
			}
			
			$db->update($this->table, "`state`=".(int)$state, "`tournamentid`=".(int)$tournamentid);
			return true;
		}
	
		
		function removeTournament($tournamentid)
		{
			global $db;
			$db->delete($this->table, "`tournamentid`=".(int)$tournamentid);
			$db->delete('tournamentencounters', "`tournamentid`=".(int)$tournamentid);
			$db->delete('tournamentgroupregister', "`tournamentid`=".(int)$tournamentid);
			$db->delete('tournamentregister', "`tournamentid`=".(int)$tournamentid);
			$db->delete('tournamentsettings', "`tournamentid`=".(int)$tournamentid);
		}
		
		function getTournament($tournamentid)
		{
			global $db;
			$t = $db->selectOneRow($this->table, "*", "`tournamentid`=".(int)$tournamentid);
			$e = $db->selectOneRow('events', "*", "`eventid`=" . (int)$t['eventid']);
			$t['event'] = $e;
			$t['duration'] = ((int)$t['duration'] / 60);
			$t['breaktime'] = ((int)$t['breaktime'] / 60);
			return $t;
		}
		
		function addSetting($tournamentid, $settingid, $value)
		{
			global $db;
			$db->insert('tournamentsettings',
				array('tournamentid', 'settingid', 'value'),
				array((int)$tournamentid,(int)$settingid, "'".$value."'"));
		}
		
		function setSetting($tournamentid, $settingid, $value)
		{
			global $db;
			if(0 == $db->num_rows('tournamentsettings', "`tournamentid`=".(int)$tournamentid." AND `settingid`=".(int)$settingid)) {
				$this->addSetting($tournamentid, $settingid, $value);
			} else {
				$db->update('tournamentsettings', 
					"`value`='".secureMySQL($value)."'", "
					`tournamentid`=".(int)$tournamentid."
					AND `settingid`=".(int)$settingid);
			}
		}
		
		function getSetting($tournamentid, $settingid)
		{
			global $db;
			return $db->selectOne('tournamentsettings', 
					'value', "`tournamentid`=".(int)$tournamentid." 
					AND `settingid`=".(int)$settingid);
		}
	}
	
?>