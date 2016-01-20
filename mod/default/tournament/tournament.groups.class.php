<?php

	class Groups extends tournament {
		
		private $playerPerGroup;
		private $winnerPerGroup;
		
		function init($settings) {
			$this->playerPerGroup = $settings[0];
			
			// Only half of the group number count can be winners
			if($settings[1] > floor($settings[0]/2)) {
				$this->winnerPerGroup = floor($settings[0]/2);
			} else {
				$this->winnerPerGroup = $settings[1];
			}
		}
		
		function createDataStructure() {
			global $db;
			
			// clear old data if stored in db
			$db->delete('tournamentgroupenc', "`tournamentid`=".$this->tournamentId);
			
			if(count($this->participants) < 4) {
				return false;
			}
			
			$playerList = $this->getPlayerList(-1);
			
			$this->fillRound($playerList, 0);
			$this->createMapCycle(count($playerList));
			
			return true;
		}
		
		function getPlayerList($round) {
			global $db;
			if ($round == -1) {
				// get players and shuffle them
				$playerList = array();
				foreach ($this->participants as $player) {
					$playerList[] = $player->getId();
				}
			}
			else {
				$dbPlayerList = $db->selectList('tournamentgroupenc', "*", 
					"`round`=".(int) $round." AND `tournamentid`=".$this->tournamentId.
					" AND `rank`<=".(int)$this->winnerPerGroup);
				
				foreach ($dbPlayerList as $dbPlayer) {
					$playerList[] = $dbPlayer['participantid'];
				}
			}
			shuffle($playerList);
			return $playerList;
		}
		
		function submitResults($roundId, $encId) {		
		}
		
		function tournamentTable() {
			global $smarty;
			global $template_dir;
			global $db;
			global $tournament;
			global $right;
			global $breadcrumbs;
			global $lang;
			global $notify;
			
			if (@$_GET['action'] == 'submit' && $right['submit_results']) {
				$breadcrumbs->addElement($lang->get('submit_results'), makeURL('tournament', array('tournamentid' => $this->tournamentId, 'mode' => 'table', 'action' => 'submit', 'group' => $_GET['group'], 'round' => $_GET['round'])));
				
				$smarty->assign('path', $template_dir."/groups.submit.tpl");
				$pList = $db->selectList('tournamentgroupenc', "*", 
					"`tournamentid`=".$this->tournamentId." AND `round`=".(int)$_GET['round']." AND `group`=".(int)$_GET['group'],
					"`rank` ASC");
				
				if (isset($_POST['submit'])) {
					$allRanks = array();
					$allFinished = true;
					foreach($pList as $p) {
						$pRank = (int)$_POST['rank_'.$p['participantid']];
						$allRanks[$pRank] = $pRank;
						if($pRank == 0) {
							$allFinished = false;
						}
					}
					
					$switchRanks = array();
					if($allFinished) {
						sort($allRanks);
						foreach($allRanks as $newRank => $oldRank) {
							$switchRanks[$oldRank] = $newRank+1;
						}
					} else {
						$switchRanks = $allRanks;
					}
				}
					
				foreach ($pList as $i => $p) {
					if (isset($_POST['submit'])) {
						$db->update('tournamentgroupenc', "`rank`=".$switchRanks[(int)$_POST['rank_'.$p['participantid']]], 
							"`tournamentid`=".$this->tournamentId." AND `participantid`=".$p['participantid']." AND `group`=".(int)$_GET['group']." AND `round`=".(int)$_GET['round']);
						$pList[$i]['rank'] = $switchRanks[(int)$_POST['rank_'.$p['participantid']]];
					}
					foreach ($this->participants as $participant) {
						if ($participant->getId() == $p['participantid']) {
							if ($tournament['playerperteam'] > 1) {
								$pList[$i]['url'] = makeURL('tournament', array('tournamentid' => $this->tournamentId, 'groupid' => $p['participantid'], 'mode' => 'viewgroup'));
							}
							else {
								$pList[$i]['url'] = makeURL('profile', array('userid' => $p['participantid']));
							}
							$pList[$i]['name'] = $participant->getName();
						}
						
					}
				}
				
				$smarty->assign('group', array('group' => chr(65 + $_GET['group'])));
				$smarty->assign('plist', $pList);
				
				if (isset($_POST['submit'])) {
					$notify->add($lang->get('tournament'), $lang->get('ntfy_submit_success'));
					if ($this->roundComplete()) {
						$this->newRound();
					}
					unset($_GET['action']);
					$this->tournamentTable();
				}
			}
			else {

				$smarty->assign('path', $template_dir."/groups.tpl");
				$tbl = array();
				
				$maxRound = $db->selectOne('tournamentgroupenc', 'round', "`tournamentid`=".$this->tournamentId, "`round` DESC");
				
				for ($round = 0; $round <= $maxRound; $round++) {
					$maxGroup = $db->selectOne('tournamentgroupenc', 'group', "`tournamentid`=".$this->tournamentId." AND `round`=".$round, "`group` DESC");
					for ($group = 0; $group <= $maxGroup; $group++) {
						$pList = array();
						$pList = $db->selectList('tournamentgroupenc', '*', "`tournamentid`=".$this->tournamentId." AND `round`=".$round." AND `group`=".$group, "`rank` ASC");
						foreach ($pList as $i => $p) {
							foreach ($this->participants as $participant) {
								if ($participant->getId() == $p['participantid']) {
									if ($tournament['playerperteam'] > 1) {
										$pList[$i]['url'] = makeURL('tournament', array('tournamentid' => $this->tournamentId, 'groupid' => $p['participantid'], 'mode' => 'viewgroup'));
									}
									else {
										$pList[$i]['url'] = makeURL('profile', array('userid' => $p['participantid']));
									}
									$pList[$i]['name'] = $participant->getName();
									break;
								}
							}
						}
						$tbl[$round]['map'] = $this->getMap($round);
						$tbl[$round]['round'] = $round+1;
						$tbl[$round]['groups'][$group]['group'] = chr(65 + $group);
						$tbl[$round]['groups'][$group]['plist'] = $pList;
						if ($right['submit_results'])
							$tbl[$round]['groups'][$group]['url'] = makeURL('tournament', array('tournamentid' => $this->tournamentId, 
							'mode' => 'table', 'action' => 'submit', 'group' => $group, 'round' => $round));
					}
				}
			
				$smarty->assign('tbl', $tbl);
			
			}
		}
		
		function ranking() {
			global $db;
			
			// Get current round
			$cRound = $this->getCurrentRound();
			
			// Get results from last round
			$lastGroup = $db->selectList('tournamentgroupenc', "*",
				"`tournamentid`=".$this->tournamentId." AND `round`=".$cRound,
				"`rank` ASC");
				
			foreach ($lastGroup as $i => $player) {
				$rank[$i]['rank'] = $i + 1;
				$rank[$i]['participants'][0] = $this->getParticipantArray($player['participantid']);
			}
			$cLastGroup = count($lastGroup);
			
			// Get results of other groups
			$otherGroups = $db->selectList('tournamentgroupenc', "*",
				"`tournamentid`=".$this->tournamentId." AND `round`<".$cRound.
				" AND `rank`>".$this->winnerPerGroup);
			
			foreach ($otherGroups as $player) {
				$i = $cRound - $player['round'] + $cLastGroup - 1;
				$rank[$i]['rank'] = $i + 1;
				$rank[$i]['participants'][] = $this->getParticipantArray($player['participantid']);
			}
			
			return $rank;
		}
		
		function encounter($roundId, $encId) {
		}
		
		function submitForm($roundId, $encId) {
		}
		
		function roundComplete() {
			global $db;
			if ($db->num_rows('tournamentgroupenc', "`tournamentid`=".$this->tournamentId. " AND `rank`=0") == 0) {
				return true;
			}
			return false;
		}
		
		function getCurrentRound() {
			global $db;
			return (int)$db->selectOne('tournamentgroupenc', 'round', 
				"`tournamentid`=".$this->tournamentId, "`round` DESC");
		}
		
		function newRound() {
			global $db;
			global $notify, $lang;
			
			$currentRound = $this->getCurrentRound();
			$playerList = $this->getPlayerList($currentRound);
			if ($db->num_rows('tournamentgroupenc', "`tournamentid`=".$this->tournamentId." AND `round`=".$currentRound." GROUP BY `group`") > 1) {
				$this->fillRound($playerList, $currentRound + 1);
				$notify->add($lang->get('tournament'), $lang->get('ntfy_submit_new_round'));
			} else {
				$db->update('tournamentlist', "`state`=3", "`tournamentid`=".$this->tournamentId);
				$notify->add($lang->get('tournament'), $lang->get('ntfy_end_game'));
			}
		}
		
		function fillRound($playerList, $round) {
			global $db;
		
			$groupCount = ceil(count($playerList)/$this->playerPerGroup);
		
			for($i=0; $i<count($playerList); $i++) {
				$db->insert('tournamentgroupenc', 
					array('tournamentid', 'participantid', 'group', 'round'),
					array($this->tournamentId, $playerList[$i], $i%$groupCount, $round)
					);
			}
		}
	}

?>