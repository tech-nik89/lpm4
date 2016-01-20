<?php

	class Deathmatch extends tournament {		
		function init($settings) {
		
		}
		
		function submitResults($roundId, $encId){
			global $rights;
			global $lang; 
			global $notify;
			global $db;
			
			$enc = new EliminationEncounter($this->tournamentId, $roundId, $encId);
	
			if ($this->checkSubmitResultsRights($enc, $_POST['player1p'], $_POST['player2p'], true)) {
				if ($_POST['player1p'] != "" && $_POST['player2p'] != "") {
					$enc->setPoints((int)$_POST['player1p'], (int)$_POST['player2p']);
					
					$notify->add($lang->get('submit_form'),
							$lang->get('ntfy_submit_success'));
					
				} else {
					$notify->add($lang->get('submit_form'),
							$lang->get('ntfy_submit_err_points'));
				}
			}
			
			// check if this was the last game
			$openenc = $db->num_rows('tournamentencounters', "`tournamentid`=".$this->tournamentId." AND `state`=0");
			if(isset($openenc) && $openenc == 0) {
				// Close the tournament
				global $notify;
				global $lang;
					
				$notify->add($lang->get('tournament'), $lang->get('ntfy_end_game'));
				$db->update('tournamentlist', "`state`=3", "`tournamentid`=".$this->tournamentId);
			}
			
			$this->tournamentTable();
		}
		
		function createDataStructure(){
			global $db;
			
			// delete old structure
			$db->delete("tournamentencounters",	"tournamentid=" . $this->tournamentId);
		
			// only start if there are 2 or more
			if(count($this->participants) < 2) {
				return false;
			}
		
			// make a list of all playerids
			$playerlist = array();
			foreach ($this->participants as $participant) {
				$playerlist[] = $participant->getId();
			}
			shuffle($playerlist);
		
			// calculate max, rounds and stuff
			$count = count($playerlist);
			$straightCount = (int) ceil($count / 2) * 2;
			$unevenCount = ($straightCount != $count);
			$roundsMax = $straightCount - 1;
			$matchesPerRound = $straightCount / 2 - 1;
			$matches = array();

			// set encounters
			for($round=0; $round<$roundsMax; $round++) {
				//$dummy = array();
				$p1 = $playerlist[$round];
				$p2 = $unevenCount?"-1":$playerlist[$roundsMax];
				$curEnc = new EliminationEncounter($this->tournamentId, $round, 0, true);
				$curEnc->setPlayers($p1, $p2);
				if($unevenCount) {
					$curEnc->setPoints(1,0);
				}
				//$dummy[0]['player1'] = $playerlist[$round];
				//$dummy[0]['player2'] = $unevenCount?"-1":$playerlist[$roundsMax];
				for($i=1; $i<=$matchesPerRound; $i++) {
					$p1 = $playerlist[($round + $i) % $roundsMax];
					$p2 = $playerlist[($round - $i + $roundsMax)% $roundsMax];
					$curEnc = new EliminationEncounter($this->tournamentId, $round, $i, true);
					$curEnc->setPlayers($p1, $p2);
				}
				//$matches[] = $dummy;
			}
			$this->createMapCycle($roundsMax);
			return true;
		}
		
		function tournamentTable(){
			global $smarty;
			global $template_dir;
			global $db;
			global $config;
			
			$encList = $db->selectList('tournamentencounters', '*', "`tournamentid`=".$this->tournamentId, "`roundid` ASC, `encounterid` ASC");
			foreach($encList as $encounter) {
				$y=$encounter['roundid'];
				$x=$encounter['encounterid'];
				
				$curEnc = new EliminationEncounter($this->tournamentId, $y, $x);
				
				$finalList[$x][$y]['round']=$x;
				$finalList[$x][$y]['encid']=$y;
							
				foreach ($this->participants as $participant) {
					if ($participant->getId() == $curEnc->getPlayer1id()) {
						$player1 = $participant;
					}
					if ($participant->getId() == $curEnc->getPlayer2id()) {
						$player2 = $participant;
					}
				}
	
				// only player 2 can be a dummy
				if ($curEnc->getPlayer2id() == -1) {
					$player2 = newDummy();
				}				
				
				if($curEnc->getPlayer1id() > 0  && $curEnc->getPlayer2id() > 0) {
					$finalList[$x][$y]['link'] = makeUrl('tournament', array(
												'tournamentid' => $this->tournamentId,
												'encid' => $x,
												'roundid' => $y,
												'mode' => 'view'));
				}
				
				$finalList[$x][$y]['p1name'] = $player1->getName();
				$finalList[$x][$y]['p1url'] = $player1->getUrl();
				$finalList[$x][$y]['p2name'] = $player2->getName();
				$finalList[$x][$y]['p2url'] = $player2->getUrl();

				$finalList[$x][$y]['timestatus'] = $curEnc->getEncTimeState();
				
				if ($curEnc->isFinished()) {
					$finalList[$x][$y]['p1points'] = $curEnc->getPoints1();
					$finalList[$x][$y]['p2points'] = $curEnc->getPoints2();
					$finalList[$x][$y]['winner'] = $curEnc->winner();
					$finalList[$x][$y]['draw'] = $curEnc->isDraw();
				}
			}
			$smarty->assign('roundsandmaps', $this->getRoundsAndMaps($y+1));
			$smarty->assign('table', $finalList);
			$smarty->assign('encounterWidth', $config->get('tournament', 'tree-encounter-width'));
			$smarty->assign('encTempl', $template_dir . '/deathmatchencounter.tpl');
			$smarty->assign('path', $template_dir . '/deathmatch.tpl');
		}
		
		function ranking(){
			global $db;
			
			$encList = $db->selectList('tournamentencounters', '*', "`tournamentid`=".$this->tournamentId, "`encounterid` ASC");
 
			$participants = array();
			foreach ($this->participants as $participant) {
				$participants[$participant->getId()] = 0;
			}
			
			foreach($encList as $enc) {
				if($enc['points1']  == $enc['points2']){
					$participants[$enc['player1id']] += 1;
					$participants[$enc['player2id']] += 1;
				} else {
					$winner = ($enc['points1']  > $enc['points2'])?1:2;
					$participants[$winner==1 ? $enc['player1id'] : $enc['player2id']] += 3;
				}
			}
			
			arsort($participants);
			
			$out = array();
			$playerPerTeam = $db->selectOne('tournamentlist', 'playerperteam', '`tournamentid`='.$this->tournamentId);
			
			$cRank = 1;
			if (count($participants) > 0) {
				foreach ($participants as $participant => $points) {
					$r = array();
					if ($participant > 0) {
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
							'points' => $points,
							'url' => $url,
							'userid' => $participant
							);
						$out[] = array('participants' => $r, 'rank' => $cRank++);
					}
				}
			}
			return $out;
		}
		
		function encounter($roundId, $encId){
			global $breadcrumbs, $template_dir, $smarty, $tournamentList, $notify, $lang;
			
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
			}
			else {
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
				$enc_arr['map'] = $this->getMap($_GET['roundid']+1);
				if($enc->getStart() != 0) {
					$enc_arr['start'] = $enc->getStart()>time()?timeLeft($enc->getStart()):formatTime($enc->getStart());
					$tEnd = $enc->getStart()+$enc->getDuration();
					$enc_arr['end'] = $enc->getStart()<time() && $tEnd>time()?timeLeft($tEnd):formatTime($tEnd);
					$enc_arr['duration'] = $enc->getDuration();
				}
				$smarty->assign('enc', $enc_arr);
				$smarty->assign('user_can_submit', $this->checkSubmitRights($enc, $player1, $player2));
			}
		
			
			$smarty->assign('path', $template_dir."/eliminationencounter_detail.tpl");
			$smarty->assign('_GET', $_GET);
		}
		
		function submitForm($roundId, $encId){
		
		}
	}

?>