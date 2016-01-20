<?php

	abstract class tournament
	{
		
		protected $tournamentId;
		protected $participants;
		
		/* 
		 * Tournament states
		 * 0 = Inactive
		 * 1 = Joining
		 * 2 = Running
		 * 3 = Finished
		 */
		 
		 function __construct($tournamentId, $participants) {
			global $db;
			$this->tournamentId = (int)$tournamentId;
			$this->participants = $participants;
			
			$settingsRaw = $db->selectList(MYSQL_TABLE_PREFIX .
					"tournamentsettings", "*", "tournamentid=" .
					$this->tournamentId);
			
			$settings = array();
			foreach($settingsRaw as $settingRaw) 
			{
				$settings[$settingRaw['settingid']] = $settingRaw['value'];
			}
			$this->init($settings);
		}
		
		function getState() {
			global $db;
			$db->selectOne(MYSQL_TABLE_PREFIX . "tournamentlist", "state",
					"`tournamentid`=" . $this->tournamentId);
		}
			
		function setState($state) {
			global $db;
			$db->update(MYSQL_TABLE_PREFIX . "tournamentlist", "`state`=" .
					(int)$state, "`tournamentid`=" . $this->tournamentId);
			if ($state == 2) {
				$this->createDataStructure();
			}
		}
		
		function setParticipants($participants){
			$this->participants = $participants;
		}
		
		function createMapCycle($rounds) {
			global $db;
			$finalmaps = array();
		
			$tournament = $db->selectOneRow(MYSQL_TABLE_PREFIX . "tournamentlist", "*", "`tournamentid`=" . $this->tournamentId);
			
			if($tournament['mappool'] == null) {
				return true;
			} else {
				$mappool = explode(";",$tournament['mappool']);
				for($i=0; $i<$rounds; $i++) {
					if($i % count($mappool) == 0) {
						shuffle($mappool);
					}
					$finalmaps[]=$mappool[$i % count($mappool)];
				}
			}
			return $db->update(MYSQL_TABLE_PREFIX . "tournamentlist", "`finalmappool`='" .implode(";", $finalmaps)."'", "`tournamentid`=" . $this->tournamentId);;
		}
		
		function getRoundsAndMaps($rounds) {
			global $db;
			
			$tournament = $db->selectOneRow(MYSQL_TABLE_PREFIX . "tournamentlist", "*", "`tournamentid`=" . $this->tournamentId);
			$roundsAndMaps = array();
			for($i=0; $i<$rounds; $i++) {
				if($tournament['finalmappool'] != null) {
					$mappool = explode(";", $tournament['finalmappool']);
					$roundsAndMaps[$i]['map'] = $mappool[$i];
				}
				
				if($tournament['mode']==2 && $i>0) {
					$roundsAndMaps[$i]['roundNr'] = ($i+1)/2+1;
				} else {
					$roundsAndMaps[$i]['roundNr'] = $i+1;
				}
				
				if($tournament['mode']==2) {
					$time = $db->selectOneRow('tournamentencounters', '*', "`tournamentid`=".$this->tournamentId." AND `roundid`=".($i+1), 'encounterid ASC');
					$roundsAndMaps[$i]['startTime'] = date("H:i", $time['start']);
				} else {
					$time = $db->selectOneRow('tournamentencounters', '*', "`tournamentid`=".$this->tournamentId." AND `roundid`=".$i, 'encounterid ASC');
					$roundsAndMaps[$i]['startTime'] = date("H:i", $time['start']);
				}
			}
			
			return $roundsAndMaps;
		}
		
		function getMap($round) {
			global $db;
			
			$tournament = $db->selectOneRow(MYSQL_TABLE_PREFIX . "tournamentlist", "*", "`tournamentid`=" . $this->tournamentId);
			$mappool = explode(";", $tournament['finalmappool']);
			
			return isset($mappool[$round-1])?$mappool[$round-1]:'';
		}
		
		function getParticipantArray($participantid) {
			foreach ($this->participants as $participant) {
				if ($participant->getId() == (int)$participantid) {
					$arr['url'] = $participant->getUrl();
					$arr['name'] = $participant->getName();
					$arr['userid'] = $participantid;
					return $arr;
				}
			}
			return false;
		}
		
		/*
		function getEncTimeState($enc) {
			if($enc->getDuration() == 0) {
				return array('color'=>"#dddddd",
							'mode'=>"no time",
							'start'=>'');
			}
		
			$timeColor = array(0=>"#888888",
							10=>"#ffff00", 
							11=>"#dddddd",
							20=>"#00ff00",
							21=>"#ff0000",
							30=>"#ff0000",
							31=>"#ff0000");
		
			$now = time();
			$start = $enc->getStart();
			$duration = $enc->getDuration();
			
			
			// Game is finished
			if($enc->isFinished()) {
				$state = 0;
				$mode = "finished";
			} else {
				// Not yet started
				if($now < $start) {
					// Has at least one unknown in it
					if($enc->getPlayer1id()==0 || $enc->getPlayer2id()==0) {
						$state = 11;
						$mode = "not started - waiting";
					// Only real players
					} else {
						$state = 10;
						$mode = "not started - ready";
					}
				// In playtime
				} elseif($now < $start+$duration) {
					// Has at least one unknown in it
					if($enc->getPlayer1id()==0 || $enc->getPlayer2id()==0) {
						$state = 21;
						$mode = "playing - waiting";
					// Only real players
					} else {
						$state = 20;
						$mode = "playing";
					}
				// Over playtime
				} else {
					// Has at least one unknown in it
					if($enc->getPlayer1id()==0 || $enc->getPlayer2id()==0) {
						$state = 31;
						$mode = "ended - waiting";
					// Only real players
					} else {
						$state = 30;
						$mode = "ended - overtime";
					}
				}
			}
				
			return array('mode'=>$mode,
						'color'=>$timeColor[$state],
						'start'=>date("H:i", $enc->getStart()));
		}
		*/
		
		abstract function init($settings);
		
		abstract function submitResults($roundId, $encId);
		
		abstract function createDataStructure();
		
		// sites of the tournament
		
		abstract function tournamentTable();
		
		abstract function ranking();
		
		abstract function encounter($roundId, $encId);
		
		abstract function submitForm($roundId, $encId);
		
		
		
		function checkSubmitResultsRights($enc, $p1points, $p2points, $draw = false) {
			global $login;
			global $rights;
			global $notify;
			global $lang;
			if(preg_match("/[^0-9]+/", $p1points) || preg_match("/[^0-9]+/", $p2points)){
				$notify->add($lang->get('submit_form'), $lang->get('ntfy_submit_err_points_no_number'));
				return false;
			}
			
			if(!$draw) {
				// Draw is denied
				if($p1points == $p2points) {
					$notify->add($lang->get('submit_form'), $lang->get('ntfy_submit_err_points_draw'));
					return false;
				}
			}
			
			// Only participants and admin can submit
			if($this->checkSubmitRights($enc, $p1, $p2)) {
				// Admin
				if($rights->isAllowed("tournament", "submit_results")) {
					return true;
				}
				// Only Looser is allowed to submit
				if($p1points>$p2points ? $p2->userCanSubmit($enc) : $p1->userCanSubmit($enc)) {
					return true;
				}
			} 
			$notify->add($lang->get('submit_form'), $lang->get('ntfy_submit_err_rights'));
			return false;
		} 
		
		function checkSubmitRights($enc, &$player1, &$player2) {
			global $notify;
			global $rights;
			global $lang;
			
			// get the two players of the encounter
			foreach ($this->participants as $participant) {
				if ($participant->getId() == $enc->getPlayer1id()) {
					$player1 = $participant;
				}
				if ($participant->getId() == $enc->getPlayer2id()) {
					$player2 = $participant;
				}
			}
			
			if (!isset($player1) || !isset($player2)) {
				$notify->add($lang->get('submit_form'),
						$lang->get('ntfy_submit_err_player'));
			} else if ($rights->isAllowed("tournament", "submit_results") ||
					(($player1->userCanSubmit($enc) ||	$player2->userCanSubmit($enc)) && !$enc->isFinished())) {
				return true;
			}
			return false;
		}
	}

?>