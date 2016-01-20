<?php
	define('DOUBLEELIMINATION_UPPER', 0);
	define('DOUBLEELIMINATION_LOWER', 1);

	class DoubleElimination extends tournament {
		
		// if true, hide the table structure 
		private $shuffle;
		
		function init($settings) {
			$this->shuffle = $settings[0];
		}
		
		function submitResults($roundId, $encId) {
			global $rights;
			global $lang; 
			global $notify;
			
			$enc = new EliminationEncounter($this->tournamentId, $roundId, $encId);
			
			if ($this->checkSubmitResultsRights($enc, $_POST['player1p'], $_POST['player2p'])) {
				if ($_POST['player1p'] != "" && $_POST['player2p'] != "") {
					$notify->add($lang->get('submit_form'), $lang->get('ntfy_submit_success'));
					$this->setEncPoints($roundId, $encId, (int)$_POST['player1p'], (int)$_POST['player2p']);
				} else {
					$notify->add($lang->get('submit_form'), $lang->get('ntfy_submit_err_points'));
				}
			}
			$this->tournamentTable();
		}
		
		function createDataStructure() {
			global $db;
			
			// if there was a structure built before, delete it
			$db->delete(MYSQL_TABLE_PREFIX . "tournamentencounters", "tournamentid=" . $this->tournamentId);
					
			unset($this->firstRoundParts);
			unset($this->lowerTreeEncOffset);
			
			if(count($this->participants) < 4) {
				return false;
			}
			
			$size = $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER);
	
			$playerList = array();
			foreach ($this->participants as $player) {
				$playerList[] = $player->getId();
			}
			shuffle($playerList);
			
			// create round 1 - 3, fill with participants and dummies
			
			// get some stuff
			$firstRoundEncounters=array();
			$secondRoundUpper = array();
			$secondRoundLower = array();
			$thirdRoundLower = array();
			$aliases = $this->createPossibleAliases(1, DOUBLEELIMINATION_UPPER);
			$aliases2u = $this->createPossibleAliases(2, DOUBLEELIMINATION_UPPER);
			$aliases2l = $this->createPossibleAliases(2, DOUBLEELIMINATION_LOWER);
			$aliases3l = $this->createPossibleAliases(3, DOUBLEELIMINATION_LOWER);
			
			// initialize rounds 2 and 3 (1 will be initialized when filling)
			for ($i = 0; $i < $size / 2; $i++) {
				$secondRoundUpper[$i] = new EliminationEncounter($this->tournamentId, 2, $i, TRUE);
				$secondRoundUpper[$i]->setPlayer1Id(0);
				$secondRoundUpper[$i]->setPlayer2Id(0);
				$secondRoundUpper[$i]->setAlias($aliases2u[$i]);
				$secondRoundLower[$i] = new EliminationEncounter($this->tournamentId, 2, $i + $this->lowerEncOffset(), TRUE);
				$secondRoundLower[$i]->setPlayer1Id(0);
				$secondRoundLower[$i]->setPlayer2Id(0);
				$secondRoundLower[$i]->setAlias($aliases2l[$i]);
				$thirdRoundLower[$i] = new EliminationEncounter($this->tournamentId, 3, $i + $this->lowerEncOffset(), TRUE);
				$thirdRoundLower[$i]->setPlayer1Id(0);
				$thirdRoundLower[$i]->setPlayer2Id(0);
				$thirdRoundLower[$i]->setAlias($aliases3l[$i]);
			}
			
			// initialize and fill first players of round 1
			for ($i = 0; $i < $size; $i++) {
				$firstRoundEncounters[$i] = new EliminationEncounter($this->tournamentId, 1, $i, TRUE);
				$firstRoundEncounters[$i]->setPlayer1Id($playerList[$i]);
				unset($playerList[$i]);
			}
			
			// shuffle remaining participants and dummies
			for ($i = count($this->participants); $i < $this->firstRoundPartCount(); $i++) {
				$playerList[] = -1;
			}
			shuffle($playerList);
			
			// fill second players of round 1
			for ($i = 0; $i < $size; $i++) {
				$firstRoundEncounters[$i]->setPlayer2Id($playerList[$i]);
				
				// advance dummy players
				if ($playerList[$i] == - 1) {
					$firstRoundEncounters[$i]->setPoints(0,0);
					if ($i % 2 == 0) {
						$secondRoundUpper[$i / 2]->setPlayer1Id($firstRoundEncounters[$i]->getPlayer1Id());
						$secondRoundLower[$i / 2]->setPlayer1Id(-1);
						$addThird = ($secondRoundLower[$i / 2]->getPlayer2Id() == -1);
						if ($addThird) $secondRoundLower[$i / 2]->setPoints(0,0);
					} else {
						$secondRoundUpper[($i - 1) / 2]->setPlayer2Id($firstRoundEncounters[$i]->getPlayer1Id());
						$secondRoundLower[($i - 1) / 2]->setPlayer2Id(-1);
						$addThird = ($secondRoundLower[($i - 1) / 2]->getPlayer1Id() == -1);
						if ($addThird) $secondRoundLower[($i - 1) / 2]->setPoints(0,0);
					}
					if ($addThird) {
						$thirdRoundLower[floor($i / 2)]->setPlayer2Id(-1);
					}
				}
			}
			
			// create remaining empty encounters
			for ($i = 4; $i <= $this->lastRound(); $i++) {
				for ($j = 0; $j < $this->numberOfEncounters($i, DOUBLEELIMINATION_UPPER); $j++) {
					$enc = new EliminationEncounter($this->tournamentId, $i, $j, TRUE);
					$enc->setPlayers(0,0);
					$enc->setAlias($j);
				}
				for ($j = 0; $j < $this->numberOfEncounters($i, DOUBLEELIMINATION_LOWER); $j++) {
					$enc = new EliminationEncounter($this->tournamentId, $i, $j + $this->lowerEncOffset(), TRUE);
					$enc->setPlayers(0,0);
					$enc->setAlias($j);
				}
			}
			
			$this->createMapCycle($this->lastRound());
			$this->setEncTimes();
			return true;
		}
		// sites of the tournament
		
		private function addEncounter(&$field, $i, $j, $enc, $noLinkToArrow=false) {
			if ($enc->getPlayer1id() == -1) {
		    	$player1 = newDummy();
		    }
		    if ($enc->getPlayer2id() == -1) {
		    	$player2 = newDummy();
		    }
		    if ($enc->getPlayer1id() == 0) {
		    	$player1 = newUnknown();
		    }
		    if ($enc->getPlayer2id() == 0) {
		    	$player2 = newUnknown();
		    }
		    
		    foreach ($this->participants as $participant) {
		    	if ($participant->getId() == $enc->getPlayer1id()) {
		    		$player1 = $participant;
		    	}
		    	if ($participant->getId() == $enc->getPlayer2id()) {
		    		$player2 = $participant;
		    	}
		    }

		    $field[$i][$j]['round']= $j + 1;
		    $field[$i][$j]['encNr'] = $enc->getId() + 1;
		    if ($i < $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER)) {
		    	if ($j >= $this->lastRound() - 2) {
		    		$field[$i][$j]['rowspan'] = $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER) * 2;
		    	} else {
		    		$field[$i][$j]['rowspan'] = pow(2, floor(($j + 1) / 2));
		    	}
		    	if ($j == 0 || $j >= $this->lastRound() - 2) {
		    		$field[$i][$j]['colspan'] = 1;
		    	} else {
		    		$field[$i][$j]['colspan'] = 3;
		    	}
		    } else {
		    	$field[$i][$j]['colspan'] = 1;
		    	$field[$i][$j]['rowspan'] = pow(2, floor(($j + 1) / 2));
		    	if ($j % 2 == 1) {
		    		$field[$i][$j]['haslink'] = 2;
		    		$field[$i][$j]['linktext'] = 'L' . ($j + 1)
		    				. '.' . floor(($i - $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER)) / ($j + 1) + 1);
		    	} else {
		    		$field[$i][$j]['fromlink'] = TRUE;
		    	}
		    }
			
			$field[$i][$j]['timestatus'] = $enc->getEncTimeState();
		    
		    if ($player1->getId() > 0 && $player2->getId() > 0) {
		    $field[$i][$j]['link'] = makeUrl('tournament', array(
		    		'tournamentid' => $this->tournamentId,
		    		'encid' => $enc->getId(),
		    		'roundid' => $j + 1,
		    		'mode' => 'view'));
			} else {
		    	$field[$i][$j]['link'] = null;
		    }

		    $field[$i][$j]['p1name'] = $player1->getName();
		    $field[$i][$j]['p1url'] = $player1->getUrl();
		    $field[$i][$j]['p2name'] = $player2->getName();
		    $field[$i][$j]['p2url'] = $player2->getUrl();
			
			$field[$i][$j]['nolink'] =$noLinkToArrow;

		    if ($enc->isFinished()) {
		    	$field[$i][$j]['p1points'] = $enc->getPoints1();
		    	$field[$i][$j]['p2points'] = $enc->getPoints2();
		    	$field[$i][$j]['winner'] = $enc->winner();
			}
		}
		
		private function addLink(&$field, $i, $j, $text, $rowspan) {
			// only works in lower tree
			if ($i >= $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER)) {
				$field[$i][$j]['rowspan'] = $rowspan;
				$field[$i][$j]['colspan'] = 1;
				$field[$i][$j]['haslink'] = 1;
				$field[$i][$j]['linktext'] = $text;
				$field[$i][$j]['round'] = $j + 1;
			}
		}
		
		function tournamentTable() {
			global $smarty;
			global $template_dir;
			global $config;
			
			$size = $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER) * 2;
			$field = array();
			
			// upper tree
			for ($i = 0; $i < $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER); $i++) {
				for ($j = 0; $j < $this->lastRound() - 2; $j++) {
					unset($encId);
					
				    if ($j == 0) {
				    	$encId = $i;
				    } else if ($j % 2 == 1) {
				    	$div = pow(2, ($j - 1) / 2 + 1);
				    	if ($i % $div == 0) {
				    		$encId = $i / $div;
				    	}
					}
					
					if (isset($encId)) {
				    		
						$curEnc = new EliminationEncounter($this->tournamentId, $j + 1, $encId);
						
						$this->addEncounter($field, $i, $j, $curEnc);
					}
				}
			}
			
			// lower tree
			for ($i = $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER); $i < $size; $i++) {
				// start
				$this->addLink($field, $i, 0, 'L1.' . ($i + 1 - $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER)), 1);
				
				for ($j = 1; $j < $this->lastRound() - 2; $j++) {
					unset($encId);
					$div = pow(2, floor(($j + 1) / 2));
					if (($i - $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER)) % $div == 0) {
						$encId = ($i - $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER)) / $div + $this->numberOfEncounters(1, DOUBLEELIMINATION_UPPER);
						$curEnc = new EliminationEncounter($this->tournamentId, $j + 1, $encId);
						
						$this->addEncounter($field, $i, $j, $curEnc);
					}
				}
			}
			
			// last two encounters
			$lastRound = $this->lastRound()-1;
			$curEnc = new EliminationEncounter($this->tournamentId, $this->lastRound() - 1, 0);
			$this->addEncounter($field, 0, $this->lastRound() - 2, $curEnc);
			if ($curEnc->isFinished()) {
				if ($curEnc->winner() == 2) {
					$curEnc = new EliminationEncounter($this->tournamentId, $this->lastRound(), 0);
					$this->addEncounter($field, 0, $this->lastRound() - 1, $curEnc, true);
					$lastRound++;
				}
			}
			$field['halfsize'] = ceil(count($field[0]));
			
			$smarty->assign('rounds', $this->lastRound());
			$smarty->assign('roundsandmaps', $this->getRoundsAndMaps($lastRound));
			$smarty->assign('path', $template_dir . '/doubleelimination.tpl');
			$smarty->assign('field', $field);
			$smarty->assign('encounterWidth', $config->get('tournament', 'tree-encounter-width'));
			$smarty->assign('encTempl', $template_dir . '/eliminationencounter.tpl');
			$smarty->assign('linkTempl', $template_dir . '/eliminationencounterlink.tpl');
		}
		
		function ranking() {
			global $db, $user;
			$ranking = array();

			// Get first and second place
			$secondFinal = $db->selectOneRow('tournamentencounters', '*', "`tournamentid`=".$this->tournamentId." AND `encounterId`=0", '`roundid` DESC');
			$firstFinal = $db->selectOneRow('tournamentencounters', '*', "`tournamentid`=".$this->tournamentId." AND `encounterId`=0 AND `roundid`=".($secondFinal['roundid']-1));
			if($secondFinal['state'] == 1) {
				$lastEnc = $secondFinal;
				$startround = $lastEnc['roundid']-2;
			} else {
				$lastEnc = $firstFinal;
				$startround = $lastEnc['roundid']-1;
			}
			$ranking[][0] = $lastEnc['points1'] > $lastEnc['points2'] ? $lastEnc['player1id'] : $lastEnc['player2id'];
			$ranking[][0] = $lastEnc['points1'] > $lastEnc['points2'] ? $lastEnc['player2id'] : $lastEnc['player1id'];

			// Calculate offset
			$offset= $db->num_rows('tournamentencounters', '`tournamentid`='.$this->tournamentId." AND `roundid`=1");

			for ($i = $startround; $i > 0; $i--) {
				$encList = $db->selectList('tournamentencounters', '*', "`tournamentid`=".$this->tournamentId." AND `roundid` = ".$i." AND `encounterid` >= ".$offset);
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
							} else {
								$name = $db->selectOne('users', 'nickname', '`userid`='.$participant);
								$url = makeURL('profile', array('userid' => $participant));
							}
							$r[] = array(
								'name' => $name,
								'url' => $url
								);
						}
					}
					if(count($r) > 0) {
						$out[] = array('participants' => $r, 'rank' => $cRank);
					}
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
				$notify->add($lang->get('submit_form'), $lang->get('ntfy_submit_noplayer'));
				$this->tournamentTable();
				return;
			}
			
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
			$enc_arr['map'] = $this->getMap($roundId);
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
		
		private $firstRoundParts;
		
		private function firstRoundPartCount() {
			if (!isset($this->firstRoundParts)) {
				$this->firstRoundParts = pow(2, ceil(log(count($this->participants), 2)));
			}
			return $this->firstRoundParts;
		}
		
		private $lowerTreeEncOffset;
		
		private function lowerEncOffset() {
			if (!isset($this->lowerTreeEncOffset)) {
				$this->lowerTreeEncOffset = $this->firstRoundPartCount() / 2;
			}
			return $this->lowerTreeEncOffset;
		}
		
		private function lastRound() {
			// 3 =>
			//   1. first round (has no rounds in lower tree)
			//   2. first final round (merges both trees)
			//   3. second final round (if participant from lower tree has won first final round)
			return 3 + (log($this->firstRoundPartCount(), 2) - 1) * 2;
		}
		
		private function numberOfEncounters($round, $tree) {
			if ($tree == DOUBLEELIMINATION_UPPER) {
				if ($round == 1) {
					return $this->firstRoundPartCount() / 2;
				} else if ($round >= $this->lastRound() - 1) {
					return 1;
				} else if ($round % 2 == 0) {
					return $this->firstRoundPartcount() / pow(2, $round / 2 + 1);
				} else {
					return 0;
				}
			} else if ($round == 1 || $round > $this->lastRound() - 2) {
				return 0;
			} else {
				return $this->firstRoundPartCount() / pow(2, floor($round / 2) + 1);
			}
		}
		
		private function createPossibleAliases($round, $tree) {
			$numberOfEncounters = $this->numberOfEncounters($round, $tree);
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
		
		private function setEncPoints($roundId, $encId, $p1points, $p2points) {
			$enc = new EliminationEncounter($this->tournamentId, $roundId, $encId);
			if ($roundId < $this->lastRound() - 1) {
				// normal and looser tier not merged yet
				if ($enc->getId() >= $this->lowerEncOffset()) {
					// in looser tier
					if ($roundId == $this->lastRound() - 2) {
						// winner will get in first final match
						$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, 0);
						$firstAtNext = false;
					} else if ($roundId % 2 == 0) {
						$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, $enc->getId());
						$firstAtNext = false;
					} else {
						$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, (int) ($enc->getAlias()) / 2 + $this->lowerEncOffset());
						$firstAtNext = ($enc->getAlias() % 2 == 0);
					}
				} else {
					// in winner tier
					if ($roundId == 1) {
						$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, (int) $enc->getAlias() / 2);
						$firstAtNext = ($enc->getAlias() % 2 == 0);
						$loserNextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, (int) $enc->getAlias() / 2 + $this->lowerEncOffset());
						$loserFirstAtNext = ($enc->getAlias() % 2 == 0);
					} else {
						$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 2, (int) $enc->getAlias() / 2);
						$firstAtNext = ($enc->getAlias() % 2 == 0);
						$loserNextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, (int) $enc->getAlias() + $this->lowerEncOffset());
						$loserFirstAtNext = true;
					}
				}
			} else if ($p2points >= $p1points && $roundId == $this->lastRound() - 1) {
				// create second final match
				$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, 0, false);
				$loserNextEnc = $nextEnc;
				$firstAtNext = true;
				$loserFirstAtNext = false;
				
			} else {
				// final match
				global $db;
				global $notify;
				global $lang;
				
				$db->update('tournamentlist', "`state`=3", "`tournamentid`=".$this->tournamentId);
				$notify->add($lang->get('tournament'), $lang->get('ntfy_end_game'));
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
			if (isset($loserNextEnc)) {
				if ($enc->winner() == 1) {
					if ($loserFirstAtNext) {
						$loserNextEnc->setPlayer1id($enc->getPlayer2id());
					} else {
						$loserNextEnc->setPlayer2id($enc->getPlayer2id());
					}
				} else {
					if ($loserFirstAtNext) {
						$loserNextEnc->setPlayer1id($enc->getPlayer1id());
					} else {
						$loserNextEnc->setPlayer2id($enc->getPlayer1id());
					}
				}
			}
			
			// Forward looser if he has a bye
			if(isset($loserNextEnc)) {
				$p1id=$loserNextEnc->getPlayer1id();
				$p2id=$loserNextEnc->getPlayer2id();
				if(($p1id==-1 && $p2id>0) || ($p2id==-1 && $p1id>0) || ($p1id==-1 && $p2id==-1)) {
					$loserNextEnc->setPoints(0, 0);
					$this->setEncPoints($roundId+1, $loserNextEnc->getId(), 0, 0);
				}
			}
		}
		
		public function undoSetEncounter($roundId, $encId){
			$enc = new EliminationEncounter($this->tournamentId, $roundId, $encId, false);
			// do not allow encounters with byes to be reset
			if($enc->getPlayer1id()<0 || $enc->getPlayer2id()<0){
				return false;
			}		
			
			if ($roundId < $this->lastRound() - 1) {
				// normal and looser tier not merged yet
				if ($enc->getId() >= $this->lowerEncOffset()) {
					// in looser tier
					if ($roundId == $this->lastRound() - 2) {
						// winner will get in first final match
						$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, 0);
						$firstAtNext = false;
					} else if ($roundId % 2 == 0) {
						$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, $enc->getId());
						$firstAtNext = false;
					} else {
						$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, (int) ($enc->getAlias()) / 2 + $this->lowerEncOffset());
						$firstAtNext = ($enc->getAlias() % 2 == 0);
					}
				} else {
					// in winner tier
					if ($roundId == 1) {
						$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, (int) $enc->getAlias() / 2);
						$firstAtNext = ($enc->getAlias() % 2 == 0);
						$loserNextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, (int) $enc->getAlias() / 2 + $this->lowerEncOffset());
						$loserFirstAtNext = ($enc->getAlias() % 2 == 0);
					} else {
						$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 2, (int) $enc->getAlias() / 2);
						$firstAtNext = ($enc->getAlias() % 2 == 0);
						$loserNextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, (int) $enc->getAlias() + $this->lowerEncOffset());
						$loserFirstAtNext = true;
					}
				}
			} else if ($roundId == $this->lastRound() - 1) {
				// create second final match
				$nextEnc = new EliminationEncounter($this->tournamentId, $roundId + 1, 0, false);
				$loserNextEnc = $nextEnc;
				$firstAtNext = true;
				$loserFirstAtNext = false;
				
			} else {
				// final match
			}
			
			// Check if the encounters are undoable
			if (isset($nextEnc)) {
				if (!$this->isNextEncUndoable($nextEnc)) {
					return false;				
				}
			}
			if (isset($loserNextEnc)) {
				if (!$this->isNextEncUndoable($loserNextEnc)) {
					return false;				
				}
			}
			
			if (isset($nextEnc)) {
				if($firstAtNext) {
					$nextEnc->setPlayer1id(0);
				} else {
					$nextEnc->setPlayer2id(0);
				}
			}
		
			if (isset($loserNextEnc)) {
				if($loserFirstAtNext) {
					$loserNextEnc->setPlayer1id(0);
				} else {
					$loserNextEnc->setPlayer2id(0);
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
			};
			
			
			for($round = 1; $round < $this->lastRound()+1; $round++) {
				$sql = $db->update("tournamentencounters", "`start`=".$tStart.", `duration`=".$duration, "tournamentid=".$this->tournamentId." AND roundid=".$round);
				$tStart += $duration+$break;
			}
			return $sql;
		}
	}
?>