<?php

	class KotH extends tournament
	{		
	
		private $numberOfRounds;
	
		function init($settings) {
			$this->numberOfRounds = $settings[0];
		}
		
		function submitResults($roundId, $encId){
			global $rights;
			global $lang; 
			global $notify;
			global $db;
			
			$enc = new EliminationEncounter($this->tournamentId, $roundId, $encId);
			
			if ($this->checkSubmitResultsRights($enc, $_POST['player1p'], $_POST['player2p'])) {
				if ($_POST['player1p'] != "" && $_POST['player2p'] != "") {
					$enc->setPoints((int)$_POST['player1p'], (int)$_POST['player2p']);
					
					$notify->add($lang->get('submit_form'),
							$lang->get('ntfy_submit_success'));
					
					$this->forwardPlayers($enc, $roundId, $encId);
				} else {
					$notify->add($lang->get('submit_form'),
							$lang->get('ntfy_submit_err_points'));
				}
			}
			$this->tournamentTable();
		}
		
		function forwardPlayers($enc, $roundId, $encId) {
			global $db;
			
			if($roundId+1 >= $this->numberOfRounds ) {
				// Check if everygame is finished
				$openenc = $db->num_rows('tournamentencounters', "`tournamentid`=".$this->tournamentId." AND `roundid`=".($this->numberOfRounds-1)." AND `state`=0");
				if(isset($openenc) && $openenc == 0) {
					// Close the tournament
					global $notify;
					global $lang;
					
					$notify->add($lang->get('tournament'), $lang->get('ntfy_end_game'));
					return $db->update('tournamentlist', "`state`=3", "`tournamentid`=".$this->tournamentId);
				}
				return false;
			}
			$encounters = $db->selectList('tournamentencounters', '*', "`tournamentid`=".$this->tournamentId." AND `roundid`=".($roundId+1), "`encounterid` ASC");
			$encountercount = $db->num_rows('tournamentencounters', "`tournamentid`=".$this->tournamentId." AND `roundid`=".$roundId);

			// First Winner - make next round encounters
			if(count($encounters)==null){
				for($i=0; $i<$encountercount; $i++){
					$curEnc = new EliminationEncounter($this->tournamentId, $roundId+1, $i, true);
					$curEnc->setPlayers(0,0);		
				}
				$this->setEncTimes($curEnc->getRoundId());
			}
			
			$winnerid=($enc->winner()==1)?$enc->getPlayer1id():$enc->getPlayer2id();
			$looserid=($enc->winner()==1)?$enc->getPlayer2id():$enc->getPlayer1id();
			
			// Top Game - winner stays(top left) looser goes one lower left
			if($encId==0) {
				$winEnc = new EliminationEncounter($this->tournamentId, $roundId+1, 0);
				$winEnc->setPlayer1id($winnerid);
				
				$losEnc = new EliminationEncounter($this->tournamentId, $roundId+1, 1);
				$losEnc->setPlayer1id($looserid);
			}
			// Bottom Game - looser stays(lower right) winner goes one upper right
			else if($encId==$encountercount-1) {
				$losEnc = new EliminationEncounter($this->tournamentId, $roundId+1, $encountercount-1);
				$losEnc->setPlayer2id($looserid);
				
				$winEnc = new EliminationEncounter($this->tournamentId, $roundId+1, $encountercount-2);
				$winEnc->setPlayer2id($winnerid);
			} 
			// Game in the middle - winner goes one upper right looser goes one lower left
			else {
				$winEnc = new EliminationEncounter($this->tournamentId, $roundId+1, $enc->getId()-1);
				$winEnc->setPlayer2id($winnerid);
				
				$losEnc = new EliminationEncounter($this->tournamentId, $roundId+1, $enc->getId()+1);
				$losEnc->setPlayer1id($looserid);
			}
			// Check if the bottom game has a bye
			$bottomgame=new EliminationEncounter($this->tournamentId, $roundId+1, $encountercount-1);
			if(@$bottomgame->getPlayer2id()==-1 && $bottomgame->getPlayer1id()>0){
				$bottomgame->setPoints(0,0);
				$this->forwardPlayers($bottomgame, $roundId+1, $bottomgame->getId());
			}
		}

		function createDataStructure(){
			global $db;

			$db->delete("tournamentencounters",	"tournamentid=" . $this->tournamentId);
			
			if(count($this->participants) < 4) {
				return false;
			}
			
			$playerList = array();
			foreach ($this->participants as $player) {
				$playerList[] = $player->getId();
			}
			shuffle($playerList);
			
			$size=ceil(count($playerList)/2)*2;
			$gotafree=$size!=count($playerList);
			
			for($playernr=0; $playernr<$size; $playernr+=2) {
				$curEnc = new EliminationEncounter($this->tournamentId, 0, $playernr/2, true);
				$curEnc->setPlayers($playerList[$playernr],
									(isset($playerList[$playernr+1])?$playerList[$playernr+1]:-1)
									);			
			}
			if($gotafree){
				$curEnc->setPoints(0,0);
				$this->forwardPlayers($curEnc, 0, $curEnc->getId());
				$this->setEncTimes(1);
			}
			
			 $this->createMapCycle($this->numberOfRounds);
			 $this->setEncTimes(0);
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
				// Only player 2 (in the last enc) could be a dummy		
				if ($curEnc->getPlayer2id() == -1) {
					$player2 = newDummy();
				}				
				if ($curEnc->getPlayer1id() == 0) {
					$player1 = newUnknown();
				}
				if ($curEnc->getPlayer2id() == 0) {
					$player2 = newUnknown();
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
				}
			}
			$smarty->assign('roundsandmaps', $this->getRoundsAndMaps($y+1));
			$smarty->assign('table', $finalList);
			$smarty->assign('encounterWidth', $config->get('tournament', 'tree-encounter-width'));
			$smarty->assign('encTempl', $template_dir . '/kothencounter.tpl');
			$smarty->assign('path', $template_dir . '/koth.tpl');
		}
		
		function ranking(){
			global $db;
			
			$lastround = $db->selectOneRow('tournamentencounters', 'roundid', "`tournamentid`=".$this->tournamentId, "`roundid` DESC");
			$lastround = $lastround['roundid'];

			$encList = $db->selectList('tournamentencounters', '*', "`tournamentid`=".$this->tournamentId." AND `roundid`=".$lastround, "`encounterid` ASC");
			
			$ranking = array();
			foreach($encList as $enc) {
				$winner = ($enc['points1']  >$enc['points2'])?1:2;
				$ranking[] =  $winner==1 ? $enc['player1id'] : $enc['player2id'];
				$ranking[] =  $winner==1 ? $enc['player2id'] : $enc['player1id'];
			}
			
			$out = array();
			$playerPerTeam = $db->selectOne('tournamentlist', 'playerperteam', '`tournamentid`='.$this->tournamentId);
			
			$cRank = 1;
			if (count($ranking) > 0) {
				foreach ($ranking as $participant) {
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
		
		function submitForm($roundId, $encId) {

		}
		
		private function setEncTimes($round) {
			global $db;
			global $config;
			
			$round = (int)$round;
			
			$tournament = $db->selectOneRow("tournamentlist", "*", "tournamentid=".$this->tournamentId);

			if($tournament['duration'] == 0) {
				return false;
			}

			$tStart = $tournament['start'];
			$duration = $tournament['duration'];
			$break = $tournament['breaktime'];
			
			$tStart += $round*($duration+$break);
			if($config->get('tournament', 'start_with_break')) {
				$tStart += $break;
			} 

			$sql = $db->update("tournamentencounters", "`start`=".$tStart.", `duration`=".$duration, "tournamentid=".$this->tournamentId." AND roundid=".$round);

			return $sql;
		}
	}

?>