<?php

	class Points extends tournament {
		
		function init($settings) {}
		
		function createDataStructure() {
			global $db;
			
			$db->delete('tournamentpoints', "`tournamentid`=".$this->tournamentId);
			
			foreach ($this->participants as $participant) {
				$db->insert('tournamentpoints', 
					array('tournamentid', 'participantid', 'points'),
					array($this->tournamentId, $participant->getId(), 0)
					);
			}
			
			return true;
		}
		
		function submitResults($roundId, $encId) {}
		
		function tournamentTable() {
			global $template_dir, $db, $smarty, $right;
			$smarty->assign('path', $template_dir."/points.tpl");
			$results = $db->selectList('tournamentpoints', "*", "`tournamentid`=".$this->tournamentId,
				"`points` DESC");
			foreach ($results as $i => $result) {
				foreach ($this->participants as $participant) {
					if ($participant->getId() == $result['participantid']) {
						if (isset($_POST['save']) && $right['submit_results']) {
							$points = (int)$_POST['points_'.$result['participantid']];
							$db->update('tournamentpoints', 
								"`points`=".$points,
								"`tournamentid`=".$this->tournamentId."
								AND `participantid`=".$result['participantid']);
							$results[$i]['points'] = $points;
						}
						$results[$i]['name'] = $participant->getName();
						$results[$i]['url'] = $participant->getUrl();
					}
				}
			}
			$smarty->assign('results', $results);
			$smarty->assign('right', $right);
		}
		
		function ranking() {
			global $db;
			$results = $db->selectList('tournamentpoints', "*", "`tournamentid`=".$this->tournamentId,
				"`points` DESC");
				
			$rank = array();
			for ($i = 0; $i < count($results);) {
				$points = $results[$i]['points'];
				
				$participants = array();
				while ($points == @$results[$i]['points']) {
					$participants[] = $this->getParticipantArray($results[$i]['participantid']);
					$i++;
				}
				
				$rank[] = array(
					'rank' => count($rank) + 1,
					'participants' => $participants
				);
			}
			
			return $rank;
		}
		
		function encounter($roundId, $encId) {}
		
		function submitForm($roundId, $encId) {}
		
	}

?>