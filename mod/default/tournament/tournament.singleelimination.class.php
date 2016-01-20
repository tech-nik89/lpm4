<?php
	class SingleElimination extends tournament {
	
		// if true, hide the table structure 
		private $shuffle;
		private $thirdplayoff;
		
		function init($settings)
		{
			$this->shuffle = $settings[0];
			$this->thirdplayoff = $settings[1];
		}

		function createPossibleAliases($round)
		{
			$numberOfEncounters=pow(2,$this->roundCount()-$round);
			$possibleAliases=array();
			for($i=0; $i<$numberOfEncounters; $i++)
			{
				$possibleAliases[]=$i;
			}
			if ($this->shuffle) {
				shuffle($possibleAliases);
			}
			return $possibleAliases;
		}

		function createDataStructure()
		{
			global $db;
			global $notify;
			global $lang;
			
			// if there was a structure built before, delete it
			$db->delete("tournamentencounters",
					"tournamentid=" . $this->tournamentId);
			
			// only start game if more than 4 players participate
			if(count($this->participants) < 4) {
				return false;
			}
			
			// make a list containing of 2^n elements where n is the number of
			// rounds. also shuffle the players
			$size = pow(2, $this->roundCount());
						
			$playerList = array();
			foreach ($this->participants as $player) {
				$playerList[] = $player->getId();
			}
			shuffle($playerList);
						
			$firstRoundEncounters=array();
			$secondRoundPlayers=array();
			$aliases=$this->createPossibleAliases(1);
			
			for ($i = 0; $i < $size/2; $i++) {
				//Put a player into every game
				$firstRoundEncounters[$i][0]=$playerList[$i];
				$firstRoundEncounters[$i]['alias']=$aliases[$i];
			}
			for ($i = $size/2; $i < $size; $i++) {
				if ($i < count($playerList)) {
					$firstRoundEncounters[$i-($size/2)][1]=$playerList[$i];
				} else {
					$firstRoundEncounters[$i-($size/2)][1]=-1;
					$secondRoundPlayers[]=array($firstRoundEncounters[$i-($size/2)][0], $firstRoundEncounters[$i-($size/2)]['alias'], (int) $i-($size/2));
				}
			}
					
			//first Round
			foreach ($firstRoundEncounters as $encID => $encounter) {
				$curEnc = new EliminationEncounter($this->tournamentId, 0, $encID, true);
				$curEnc->setPlayers($encounter[0], $encounter[1]);
				
				if($encounter[0]==-1 || $encounter[1]==-1) {
					$curEnc->setPoints(0,0);
				}
				$curEnc->setAlias($encounter['alias']);
			}
			
			$aliases=$this->createPossibleAliases(2);
			//second Round
			$secondRoundEncounters=array();
			//Put the winning players in their encounter
			foreach ($secondRoundPlayers as $players) {
				$secondRoundEncounters[(int) $players[1]/2][(int) $players[1]%2]=$players[0];
			}
			
			
			// Create 2nd round matches
			for($i=0; $i < $size/4 ; $i++) {
				$curEnc = new EliminationEncounter($this->tournamentId, 1, $i, true);
				$curEnc->setPlayers(0,0);
				$curEnc->setAlias($aliases[$i]);
			}

			//Update second round encounters
			foreach($secondRoundEncounters as $encID => $encounter) {
				$curEnc = new EliminationEncounter($this->tournamentId, 1, $encID, false);
				$curEnc->setPlayers(
								(isset($encounter[0])?$encounter[0]:0),
								(isset($encounter[1])?$encounter[1]:0)
								);
				$curEnc->setAlias($aliases[$encID]);
			}
			
			//Create the remaining matches
			for($round = 2; $round < $this->roundCount(); $round++) {
				$aliases=$this->createPossibleAliases($round+1);
				for($encID = 0; $encID < pow(2 , $this->roundCount()-$round-1); $encID++) {
					$curEnc = new EliminationEncounter($this->tournamentId, $round, $encID, true);
					$curEnc->setPlayers(0,0);
					// prevent endgame and 3d playoff from shuffling
					if($round < $this->roundCount()-1) {
						$curEnc->setAlias($aliases[$encID]);
					}
				}
			}
			// /w 3rd playoff
			if($this->thirdplayoff) {
				$curEnc = new EliminationEncounter($this->tournamentId, $this->roundCount()-1, 1, true);
				if($this->roundCount() > 2 || count($this->participants)>3) {
					$curEnc->setPlayers(0,0);
				} else {
					$curEnc->setPlayers(0,-1);
				}

			}
			
			 $this->createMapCycle($this->roundCount());
			 $this->setEncTimes();
			 return true;
		}
		
		function submitResults($roundId, $encId) {
			global $db;
			global $rights;
			global $lang; 
			global $notify;
			global $login;
			
			$enc = new EliminationEncounter($this->tournamentId, $roundId, $encId);
			
			if ($this->checkSubmitResultsRights($enc, $_POST['player1p'], $_POST['player2p'])) {
				if ($_POST['player1p'] != "" && $_POST['player2p'] != "") {
					$this->setEncPoints($roundId, $encId,
							(int)$_POST['player1p'], (int)$_POST['player2p']);
					$notify->add($lang->get('submit_form'),
							$lang->get('ntfy_submit_success'));
				} else {
					$notify->add($lang->get('submit_form'),
							$lang->get('ntfy_submit_err_points'));
				}
			}
			
			$this->tournamentTable();
			
			// If all matches are finished close the tournament
			if($db->num_rows("tournamentencounters", "`tournamentid`=".$this->tournamentId." AND `state`=0")==0){
				$notify->add($lang->get('tournament'), $lang->get('ntfy_end_game'));
				return $db->update('tournamentlist', "`state`=3", "`tournamentid`=".$this->tournamentId);
			}
		}
		
		// sites of the tournament
		function tournamentTable() {
			global $smarty;
			global $template_dir;
			global $config;

			$size = pow(2, $this->roundCount() - 1);
			
			for ($i = 0; $i < $size; $i++) {
				for ($j = 0; $j < $this->roundCount(); $j++) {
					if ($i % (pow(2,$j)) == 0) {
						$encId = $i / (pow(2,$j));
						$curEnc = new EliminationEncounter($this->tournamentId, $j, $encId);
						
						if ($curEnc->getPlayer1id() == -1) {
							$player1 = newDummy();
						}
						if ($curEnc->getPlayer2id() == -1) {
							$player2 = newDummy();
						}
						if ($curEnc->getPlayer1id() == 0) {
							$player1 = newUnknown();
						}
						if ($curEnc->getPlayer2id() == 0) {
							$player2 = newUnknown();
						}
						
						foreach ($this->participants as $participant) {
							if ($participant->getId() == $curEnc->getPlayer1id()) {
								$player1 = $participant;
							}
							if ($participant->getId() == $curEnc->getPlayer2id()) {
								$player2 = $participant;
							}
						}

						$field[$i][$j]['round'] = $j;
						$field[$i][$j]['encNr'] = $i + 1;
						$field[$i][$j]['rowspan'] = pow(2, $j);
						if($curEnc->getPlayer1id() > 0  && $curEnc->getPlayer2id() > 0) {
							$field[$i][$j]['link'] = makeUrl('tournament', array(
									'tournamentid' => $this->tournamentId,
									'encid' => $encId,
									'roundid' => $j,
									'mode' => 'view'));
						}
						$field[$i][$j]['p1name'] = $player1->getName();
						$field[$i][$j]['p1url'] = $player1->getUrl();
						$field[$i][$j]['p2name'] = $player2->getName();
						$field[$i][$j]['p2url'] = $player2->getUrl();
						
						$field[$i][$j]['timestatus'] = $curEnc->getEncTimeState();
						
						if ($curEnc->isFinished()) {
							$field[$i][$j]['p1points'] = $curEnc->getPoints1();
							$field[$i][$j]['p2points'] = $curEnc->getPoints2();
							$field[$i][$j]['winner'] = $curEnc->winner();
						}
					}
				}
			}
			
			if($this->thirdplayoff){
				$thirdplayoff=array();
				$curEnc = new EliminationEncounter($this->tournamentId, $this->roundCount()-1, 1);
				
				if ($curEnc->getPlayer1id() == -1) {
					$player1 = newDummy();
				}
				if ($curEnc->getPlayer2id() == -1) {
					$player2 = newDummy();
				}
				if ($curEnc->getPlayer1id() == 0) {
					$player1 = newUnknown();
				}
				if ($curEnc->getPlayer2id() == 0) {
					$player2 = newUnknown();
				}
				
				foreach ($this->participants as $participant) {
					if ($participant->getId() == $curEnc->getPlayer1id()) {
						$player1 = $participant;
					}
					if ($participant->getId() == $curEnc->getPlayer2id()) {
						$player2 = $participant;
					}
				}
				
				$thirdplayoff['round'] = $this->roundCount()-1;
				$thirdplayoff['encNr'] = $curEnc->getId()+1;
				if($curEnc->getPlayer1id() > 0  && $curEnc->getPlayer2id() > 0) {
					$thirdplayoff['link'] = makeUrl('tournament', array(
							'tournamentid' => $this->tournamentId,
							'encid' => $curEnc->getId(),
							'roundid' => $this->roundCount()-1,
							'mode' => 'view'));
				}
				$thirdplayoff['p1name'] = $player1->getName();
				$thirdplayoff['p1url'] = $player1->getUrl();
				$thirdplayoff['p2name'] = $player2->getName();
				$thirdplayoff['p2url'] = $player2->getUrl();
				
				$thirdplayoff['timestatus'] = $curEnc->getEncTimeState();
				
				$thirdplayoff['tdnbsp'] = ($this->roundCount()-1)*2;
				
				if ($curEnc->isFinished()) {
					$thirdplayoff['p1points'] = $curEnc->getPoints1();
					$thirdplayoff['p2points'] = $curEnc->getPoints2();
					$thirdplayoff['winner'] = $curEnc->winner();
				}
				$smarty->assign('thirdplayoff' , $thirdplayoff);
			}
			
			$smarty->assign('roundsandmaps', $this->getRoundsAndMaps($this->roundCount()));
			$smarty->assign('path', $template_dir . '/singleelimination.tpl');
			$smarty->assign('field', $field);
			$smarty->assign('encounterWidth', $config->get('tournament', 'tree-encounter-width'));
			$smarty->assign('encTempl', $template_dir . '/eliminationencounter.tpl');
		}
		
		function ranking() {
			global $db, $user;
			$ranking = array();

			// Get first and second place
			$lastEnc = $db->selectOneRow('tournamentencounters', '*', '`tournamentid` = ' . $this->tournamentId." AND `encounterId`=0", '`roundid` DESC');
			$ranking[][0] = $lastEnc['points1'] > $lastEnc['points2'] ? $lastEnc['player1id'] : $lastEnc['player2id'];
			$ranking[][0] = $lastEnc['points1'] > $lastEnc['points2'] ? $lastEnc['player2id'] : $lastEnc['player1id'];
			$startround = $lastEnc['roundid']-1;
			if($this->thirdplayoff) {
				$thirdplayoff = $db->selectOneRow('tournamentencounters', '*', '`tournamentid` = ' . $this->tournamentId." AND `encounterId`=1", '`roundid` DESC');
				$ranking[][0] = $thirdplayoff['points1'] > $thirdplayoff['points2'] ? $thirdplayoff['player1id'] : $thirdplayoff['player2id'];
				$ranking[][0] = $thirdplayoff['points1'] > $thirdplayoff['points2'] ? $thirdplayoff['player2id'] : $thirdplayoff['player1id'];
				$startround--;
			}
		
			for ($i = $startround; $i >=0; $i--) {
				$encList = $db->selectList('tournamentencounters', '*', '`tournamentid` = ' . $this->tournamentId . ' AND `roundid` = ' . $i);
				$ranks = array();
				if (null != $encList && count($encList) > 0) {
					foreach ($encList as $enc) {
						$ranks[] = ($enc['player1id'] <= 0 OR $enc['player2id'] <= 0) ? -1 : ($enc['points1'] > $enc['points2'] ? $enc['player2id'] : $enc['player1id']);
					}
				}
				$ranking[] = $ranks;
			}
			
			$out = array();
			$playerPerTeam = $db->selectOne('tournamentlist', 'playerperteam', '`tournamentid`='.$this->tournamentId);
			
			$cRank = 0;
			if (count($ranking) > 0) {
				foreach ($ranking as $rank) {
					$r = array();
					$cRank++;
					foreach ($rank as $participant) {
						if ($participant > -1) {
							if ($playerPerTeam > 1) {
								$name = $db->selectOne('tournamentgroups', 'name', '`groupid`='.$participant.' AND `tournamentid`='.$this->tournamentId);
								$url = makeURL('tournament', array('mode' => 'viewgroup', 'tournamentid' => $this->tournamentId, 'groupid' => $participant));
							}
							else {
								$name = $db->selectOne('users', 'nickname', '`userid`='.$participant);
								$url = makeURL('profile', array('userid' => $participant));
							}
							$r[] = array(
								'name' => $name,
								'url' => $url
								);
						}
					}
					$out[] = array('participants' => $r, 'rank' => $cRank);
				}
			}
			return $out;
		}
		
		function encounter($roundId, $encId) {
			global $breadcrumbs, $template_dir, $smarty, $tournamentList, $notify, $lang, $config, $rights;
			$tournament = $tournamentList->getTournament($this->tournamentId);
			$smarty->assign('tournament', $tournament);
			$enc = new EliminationEncounter($this->tournamentId, $roundId, $encId);
			
			foreach ($this->participants as $participant) {
				if ($participant->getId() == $enc->getPlayer1id()) {
					$player1 = $participant;
				}
				if ($participant->getId() == $enc->getPlayer2id()) {
					$player2 = $participant;
				}
			}
			
			if (!isset($player1) || !isset($player2)) {
				$notify->add($lang->get('encounter'), $lang->get('ntfy_submit_err_player'));
				$this->tournamentTable();
				return false;
			} else {
				$breadcrumbs->addElement($player1->getName()." vs. ".$player2->getName(), 
					makeURL('tournament', array('tournamentid' => $_GET['tournamentid'], 'encid' => $_GET['encid'],
							'roundid' => $_GET['roundid'], 'mode' => 'view')));
				$smarty->assign('path', $template_dir."/eliminationencounter_detail.tpl");
				$smarty->assign('player1', array('name' => $player1->getName(), 'url' => $player1->getUrl()));
				$smarty->assign('player2', array('name' => $player2->getName(), 'url' => $player2->getUrl()));
				$enc_arr['points1'] = $enc->getPoints1();
				$enc_arr['points2'] = $enc->getPoints2();
				$enc_arr['finished'] = $enc->isFinished();
				$enc_arr['winner'] = $enc->getPoints1() > $enc->getPoints2() ? 1 : 2;
				$enc_arr['map'] = $this->getMap($roundId + 1);
				if($config->get('tournament', 'allow_undoing_encounter_points') && $rights->isAllowed('tournament', 'add_remove_edit') && $enc->isFinished() && $tournament['state']==2) {
					$enc_arr['undoLink'] = makeHtmlUrl($lang->get('undo_encounter'), makeUrl('tournament', array(
													'tournamentid' => $this->tournamentId,
													'encid' => $enc->getId(),
													'roundid' => $roundId,
													'mode' => 'view',
													'undo' => 1)));
				}
				if($enc->getStart() != 0) {
					$enc_arr['start'] = $enc->getStart()>time()?timeLeft($enc->getStart()):formatTime($enc->getStart());
					$tEnd = $enc->getStart()+$enc->getDuration();
					$enc_arr['end'] = $enc->getStart()<time() && $tEnd>time()?timeLeft($tEnd):formatTime($tEnd);
				}
				$smarty->assign('enc', $enc_arr);
				$smarty->assign('user_can_submit', $this->checkSubmitRights($enc, $player1, $player2));
				$smarty->assign('_GET', $_GET);
			}
		}
		
		function submitForm($roundId, $encId) {
			$enc = new EliminationEncounter($this->tournamentId, $roundId, $encId);
			
			if ($this->checkSubmitRights($enc, $p1, $p2)) {
				global $smarty;
				global $template_dir;
				$smarty->assign('path', $template_dir .
						"/submiteliminationencounter.tpl");
				$smarty->assign('player1name', $p1->getName());
				$smarty->assign('player2name', $p2->getName());
			}
		}
		
		
		// functions and vars used internally
		// NOTE: this vars should NOT be accessed by the public functions
		private $numberOfRounds;
		
		private function roundCount() {
			if (!isset($numberOfRounds)) {
				$numberOfRounds = ceil(log(count($this->participants), 2));
			}
			return $numberOfRounds;
		}
		
		private function setEncPoints($roundId, $encId, $p1points, $p2points) {
			$enc = new EliminationEncounter($this->tournamentId, $roundId, $encId);
			
			if ($roundId < $this->roundCount() - 1) {
				// not in last round
				$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, (int) $enc->getAlias() / 2);
				
				if($this->thirdplayoff && $roundId == $this->roundCount() - 2) { 
					$firstAtNext = ($enc->getId() % 2 == 0);
				} else {
					$firstAtNext = ($enc->getAlias() % 2 == 0);
				}
			}
			
			// check which player has won and forward this player
			$enc->setPoints($p1points, $p2points);
			if (isset($nextEnc)) {
				if ($enc->winner() == 1) {
					if ($firstAtNext) {
						$nextEnc->setPlayer1id($enc->getPlayer1id());
					} else {
						$nextEnc->setPlayer2id($enc->getPlayer1id());
					}
				} else {
					if ($firstAtNext) {
						$nextEnc->setPlayer1id($enc->getPlayer2id());
					} else {
						$nextEnc->setPlayer2id($enc->getPlayer2id());
					}
				}
			}
			
			//  /w 3rd playoff forward loosers as well
			if($this->thirdplayoff && $roundId == $this->roundCount()-2) {
				$thirdPlayoffEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, 1);
				if ($enc->winner() == 2) {
					if ($firstAtNext) {
						$thirdPlayoffEnc->setPlayer1id($enc->getPlayer1id());
					} else {
						$thirdPlayoffEnc->setPlayer2id($enc->getPlayer1id());
					}
				} else {
					if ($firstAtNext) {
						$thirdPlayoffEnc->setPlayer1id($enc->getPlayer2id());
					} else {
						$thirdPlayoffEnc->setPlayer2id($enc->getPlayer2id());
					}
				}
				// Check if there is a bye in the 3rd playoff
				if($thirdPlayoffEnc->getPlayer1id() == -1 OR $thirdPlayoffEnc->getPlayer2id() == -1) {
					$thirdPlayoffEnc->setPoints(0,0);
				}
			}
		}
		
		public function undoSetEncounter($roundId, $encId){
			$enc = new EliminationEncounter($this->tournamentId, $roundId, $encId, false);
			// do not allow encounters with byes to be reset
			if($enc->getPlayer1id()<0 || $enc->getPlayer2id()<0){
				return false;
			}		
			
			// do not create encounters if it is the final match
			if($roundId <= $this->roundCount()-2) {			
				$nextEnc = new EliminationEncounter($this->tournamentId, $roundId+1, (int) $enc->getAlias() / 2, false);
				if (!$this->isNextEncUndoable($nextEnc)) {
					return false;				
				}
			
			
				$resetThird = false;
				if($this->thirdplayoff && $roundId == $this->roundCount()-2) {
					$resetThird = true;
					$thirdPlayoff = new EliminationEncounter($this->tournamentId, $roundId+1, 1, false);
					if (!$this->isNextEncUndoable($thirdPlayoff)) {
						return false;				
					}
				}
				
				// reset encounters
				$firstAtNext = ($enc->getAlias() % 2 == 0);
				
				if($firstAtNext) {
					$nextEnc->setPlayer1id(0);
					if($resetThird) {
						$thirdPlayoff->setPlayer1id(0);
					}
				} else {
					$nextEnc->setPlayer2id(0);
					if($resetThird) {
						$thirdPlayoff->setPlayer2id(0);
					}
				}
			}
			$enc->unsetPoints();
	
			return true;
		}
		
		private function isNextEncUndoable($enc) {
			if($enc->isFinished()) {
				return false;
			} else {
				return true;
			}
		}
		
		private function setEncTimes() {
			global $db;
			global $config;
			
			$tournament = $db->selectOneRow("tournamentlist", "*", "tournamentid=".$this->tournamentId);

			if($tournament['duration'] == 0) {
				return false;
			}

			$tStart = $tournament['start'];
			$duration = $tournament['duration'];
			$break = $tournament['breaktime'];
			
			if($config->get('tournament', 'start_with_break')) {
				$tStart+=$break;
			}
			
			for($round = 0; $round < $this->roundCount(); $round++) {
				$sql = $db->update("tournamentencounters", "`start`=".$tStart.", `duration`=".$duration, "tournamentid=".$this->tournamentId." AND roundid=".$round);
				$tStart += $duration+$break;
			}
			return $sql;
		}
		
		private function resetEncTimes($from=0) {
			global $db;
			switch ($from) {
				case 0: // reset all open incl. playing
				
				case 1:	// reset all open excl. playing
				
				case 2: // reset all
				
			
			}
		}
	}
?>