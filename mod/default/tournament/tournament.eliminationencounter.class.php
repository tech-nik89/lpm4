<?php
	class EliminationEncounter {
		private $player1id;
		private $player2id;
		private $points1;
		private $points2;
		private $positionAlias;
		private $finished;
		private $roundId;
		private $encId;
		private $tourId;
		private $start;
		private $duration;
		
		function __construct($tourId, $roundId, $encId, $newEnc = false) {
			global $db;
			
			$this->roundId = (int)$roundId;
			$this->encId = (int)$encId;
			$this->tourId = (int)$tourId;
			
			if ($newEnc) {
				$this->player1id = -1;
				$this->player2id = -1;
				$this->points1 = 0;
				$this->points2 = 0;
				$this->finished = false;
				$this->positionAlias = $encId;
				$this->start = 0;
				$this->duration = 0;
				// delete existing data
				$db->delete(MYSQL_TABLE_PREFIX . "tournamentencounters",
						"tournamentid=" . $this->tourId . " AND roundid=" .
						$this->roundId . " AND encounterid=" . $this->encId);
				$this->storeData();
			} else {
				$data = $db->selectOneRow(MYSQL_TABLE_PREFIX .
						"tournamentencounters", "*", "tournamentid=" .
						$this->tourId . " AND roundid=" . $this->roundId .
						" AND " . "encounterid=" . $this->encId);
				$this->player1id = (int)$data['player1id'];
				$this->player2id = (int)$data['player2id'];
				$this->points1 = (int)$data['points1'];
				$this->points2 = (int)$data['points2'];
				$this->finished = $data['state'] == 1;
				$this->positionAlias = (int)$data['alias'];
				$this->start = (int)$data['start'];
				$this->duration = (int)$data['duration'];
			}
		}
		
		function setPoints($points1, $points2) {
			$this->points1 = $points1;
			$this->points2 = $points2;
			$this->finished = true;
			$this->storeData();
		}
		
		function unsetPoints() {
			$this->points1 = 0;
			$this->points2 = 0;
			$this->finished = false;
			$this->storeData();
		}
		
		function setAlias($alias) {
			$this->positionAlias = $alias;
			$this->storeData();
		}
		
		function setPlayer1id($player1id) {
			$this->player1id = $player1id;
			$this->storeData();
		}
		
		function setPlayer2id($player2id) {
			$this->player2id = $player2id;
			$this->storeData();
		}
		
		function setPlayers($player1id, $player2id) {
			$this->player1id = $player1id;
			$this->player2id = $player2id;
			$this->storeData();
		}
		
		function setStart($start) {
			$this->start = $start;
			$this->storeData();
		}
		
		function setDuration($duration) {
			$this->duration = $duration;
			$this->storeData();
		}
		
		function setTime($start, $duration) {
			$this->start = $start;
			$this->duration = $duration;
			$this->storeData();
		}
		
		function getPlayer1id() {return $this->player1id;}
		function getPlayer2id() {return $this->player2id;}
		function getPoints1() {return $this->points1;}
		function getPoints2() {return $this->points2;}
		function getAlias() {return $this->positionAlias;}
		function getId() {return $this->encId;}
		function getStart() {return $this->start;}
		function getDuration() {return $this->duration;}
		function getRoundId() {return $this->roundId;}
		function isFinished() {return $this->finished;}
		function winner() {
			return $this->player1id == -1 ? 2 :
					($this->player2id == -1 ? 1 :
					($this->points1 > $this->points2 ? 1 :
					($this->points1 == $this->points2 ? 0 : 2)));
		}
		
		function isDraw() {
			return ($this->points1 > 0 && ($this->points1 == $this->points2))?1:0;
		}
		
		function getEncTimeState() {
			if($this->getDuration() == 0) {
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
			$start = $this->getStart();
			$duration = $this->getDuration();
			
			
			// Game is finished
			if($this->isFinished()) {
				$state = 0;
				$mode = "finished";
			} else {
				// Not yet started
				if($now < $start) {
					// Has at least one unknown in it
					if($this->getPlayer1id()==0 || $this->getPlayer2id()==0) {
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
					if($this->getPlayer1id()==0 || $this->getPlayer2id()==0) {
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
					if($this->getPlayer1id()==0 || $this->getPlayer2id()==0) {
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
						'start'=>date("H:i", $this->getStart()),
						'end'=>date("H:i", $this->getStart()+$this->getDuration()),
						'startFull'=>date("d.m.Y H:i", $this->getStart()),
						'endFull'=>date("d.m.Y H:i", $this->getStart()+$this->getDuration()),
						'duration'=>(int) $this->getDuration()/60);
		}
		
		
		
		// internal functions
		
		private function storeData() {
			global $db;
			$exists = $db->num_rows(MYSQL_TABLE_PREFIX . "tournamentencounters",
					"tournamentid=" . $this->tourId . " AND roundid=" .
					$this->roundId . " AND encounterid=" . $this->encId);
			
			$state = $this->finished ? 1 : 0;
			
			if ($exists > 0) {
				$db->update(MYSQL_TABLE_PREFIX . "tournamentencounters",
						"player1id=" . $this->player1id . ", player2id=" .
						$this->player2id . ", points1=" . $this->points1 .
						", points2=" . $this->points2 . ", state=$state, alias="
						. $this->positionAlias, "tournamentid=" . $this->tourId
						. " AND roundid=" . $this->roundId .
						" AND encounterid=" . $this->encId);
			} else {
				$db->insert(MYSQL_TABLE_PREFIX . "tournamentencounters",
						array("tournamentid", "player1id", "player2id",
						"points1", "points2", "state", "alias", "roundid",
						"encounterid"), array ( $this->tourId, $this->player1id,
						$this->player2id, $this->points1, $this->points2,
						$state, $this->positionAlias, $this->roundId,
						$this->encId));
			}
		}
	}
?>