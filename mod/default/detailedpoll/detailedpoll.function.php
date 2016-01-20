<?php
	function savePoll($title, $description, $user) {
		global $db;
		$db->insert('detailedpoll', 
					array('title', 'description', 'creator', 'date'),
					array("'".$title."'", "'".$description."'", $user, time()));
		return mysql_insert_id();
	}
	
	function updatePoll($pollid, $title, $description) {
		global $db;
		return $db->update('detailedpoll', 
					"`title`='".secureMySql($title)."', `description`='".secureMySql($description)."'",
					"detailedpollid = ".(int) $pollid);
	}
	
	function deletePoll($pollid) {
		global $db;
		return $db->delete('detailedpoll', "detailedpollid = ".(int) $pollid);
	}
	
	function getPollById($pollid) {
		global $db;
		return $db->selectOneRow('detailedpoll', '*', "detailedpollid = ".(int) $pollid);
	}
	
	function getPolls() {
		global $db, $login;
		$polls =  $db->selectList('detailedpoll', '*', '1', '`state` ASC, `date` DESC');
		$num = count($polls);
		for($i=0; $i<$num; $i++) {
			$polls[$i]['url'] = makeUrl('detailedpoll', array('pollid' => $polls[$i]['detailedpollid']));
			if($polls[$i]['state'] == 0) {
				$polls[$i]['url_edit'] = makeUrl('detailedpoll', array('mode' => 'create','pollid' => $polls[$i]['detailedpollid']));
			}
			$polls[$i]['url_delete'] = makeUrl('detailedpoll', array('mode' => 'delete','pollid' => $polls[$i]['detailedpollid']));
			
			$polls[$i]['answerCount'] = countAnswers($polls[$i]['detailedpollid']);
			$polls[$i]['hasVoted'] = hasVoted($polls[$i]['detailedpollid'], $login->currentUserId());
		}
		return $polls;
	}
	
	function savePollState($pollid, $state) {
		global $db;
		return $db->update('detailedpoll', 
					"`state`=".(int) $state,
					"`detailedpollid`=".(int) $pollid);
	}
	
	function saveQuestion($pollid, $title, $description, $rank, $parentid, $percent) {
		global $db;
		$db->insert('detailedpoll_questions', 
					array('detailedpollid', 'title', 'description', 'rank', 'parentid', 'percent'),
					array((int) $pollid, "'".$title."'", "'".$description."'", (int) $rank, (int) $parentid, (int) $percent));
		return mysql_insert_id();
	}
	
	function updateQuestion($questionid, $title, $description, $rank, $parentid, $percent) {
		global $db;
		return $db->update('detailedpoll_questions', 
					"`title`='".secureMySQL($title)."', `description`='".secureMySQL($description)."', `rank`=".(int)$rank.", `parentid`=".(int)$parentid.", `percent`=".(int)$percent,
					"`questionid`=".(int) $questionid);
	}
	
	function getQuestions($pollid, $parentid=0, $depth=0) {
		global $db;
		$parents = $db->selectList('detailedpoll_questions', '*', "`parentid`=".$parentid." AND `detailedpollid` = ".(int) $pollid, "`rank` ASC");
		if($parents) {
			$newparents = array();
			foreach($parents as $parent) {
				
				$childs = getQuestions($pollid, $parent['questionid'], $depth+1);
				$parent['depth'] = $depth;
				$parent['childs'] = ($childs)?count($childs):0;
				$newparents[]=$parent;
				if($childs) {
					$newparents = array_merge($newparents, $childs);
				}
			}
			return $newparents;
		} else {
			return false;
		}		
	}
	
	function deleteQuestion($questionid) {
		global $db;
		return $db->delete('detailedpoll_questions', "questionid = ".(int) $questionid);
	}
	
	function parentExists($pollid, $parentid) {
		global $db;
		if($parentid == 0) {
			return true;
		}
		return $db->num_rows('detailedpoll_questions',  "detailedpollid = ".(int) $pollid." AND questionid =". (int) $parentid)>0;
	}
	
	function hasVoted($pollid, $userid) {
		global $db;
		return $db->selectOneRow('detailedpoll_user_answers', 'useranswerid', "`userid` = ".(int) $userid." AND `detailedpollid` = ".(int) $pollid);
	}
	
	function saveAnswers($pollid, $userid, $values) {
		global $db;
		if(hasVoted($pollid, $userid)) {
			return false;
		}
		$db->insert('detailedpoll_user_answers', 
					array('userid', 'detailedpollid', 'date'),
					array((int) $userid, (int) $pollid, time()));
		$useranswerid = mysql_insert_id();
		foreach($values as $questionid => $value) {
			$db->insert('detailedpoll_answers', 
					array('useranswerid', 'questionid', 'value'),
					array((int) $useranswerid, (int) $questionid, (int) $value));
		}
		return $useranswerid;
	}
	
	function updateAnswers($pollid, $userid, $values) {
		global $db;
		if(!hasVoted($pollid, $userid)) {
			return false;
		}
		$useranswer = $db->selectOneRow('detailedpoll_user_answers', '`useranswerid`', '`userid`='.(int) $userid.' AND `detailedpollid`='.(int) $pollid);

		$db->update('detailedpoll_user_answers', 
					"`date`= ".time(),
					"`useranswerid`= ".$useranswer['useranswerid']);

		foreach($values as $questionid => $value) {
			$db->update('detailedpoll_answers', 
					"`value`=".(int) $value,
					"`questionid`=".(int) $questionid." AND `useranswerid`=".(int) $useranswer['useranswerid']);
		}
		return $useranswer['useranswerid'];
	}
	
	function getMyAnswers($pollid, $userid) {
		global $db;
		
		$useranswer = $db->selectOneRow('detailedpoll_user_answers', '`useranswerid`', '`userid`='.(int) $userid.' AND `detailedpollid`='.(int) $pollid);
		$answers = $db->selectList('detailedpoll_answers', '*', "`useranswerid`=".$useranswer['useranswerid']);
		
		$questions = array();
		foreach($answers as $answer) {
			$questions[$answer['questionid']] = $answer['value'];
		}
		
		return $questions;
	}
	
	function countAnswers($pollid) {
		global $db;
		
		return $db->num_rows('detailedpoll_user_answers', "`detailedpollid`= ".(int) $pollid);
	}
	
	function calculatePoll($pollid, $parentid=0, $depth=0, &$childsValue = array()) {
		global $db;

		$parents = $db->selectList('detailedpoll_questions', '*', "`parentid`=".$parentid." AND `detailedpollid` = ".(int) $pollid, "`rank` ASC");
		if($parents) {
			$newparents = array();
			$childsValue = array('value'=>0, 'percent'=>0);
			foreach($parents as $parent) {
				$childs = calculatePoll($pollid, $parent['questionid'], $depth+1, &$childsValue);
				$parent['depth'] = $depth;
				$parent['childs'] = ($childs)?count($childs):0;
				
				if($childs) {
					$parent['value'] = sprintf("%0.2f", $childsValue['value']/$childsValue['percent']);
					$childsValue['value'] = $parent['value']*$parent['percent'];
					$childsValue['percent'] = $parent['percent'];
					$newparents[] = $parent;
					$newparents = array_merge($newparents, $childs);
				} else { 
					$value = $db->selectOneRow('detailedpoll_answers', 'AVG(`value`) AS value', '`questionid`='.(int) $parent['questionid']);
					$parent['value'] = sprintf("%0.2f", $value['value']);
					$childsValue['value'] += $value['value']*$parent['percent'];
					$childsValue['percent'] += $parent['percent'];
					$newparents[] = $parent;
				}
			}
			return $newparents;
		} else {
			return false;
		}	
	}
	
	function getCalculatedPoll($questions) {
		global $db;

		$result=array('value'=>0, 'percent'=>0);
		foreach($questions as $question) {
			if($question['depth'] == 0) {
				$result['value'] += $question['value']*$question['percent'];
				$result['percent'] += $question['percent'];
			}
		}
		$finalresult = sprintf("%0.2f",$result['value']/$result['percent']);
	
		return $finalresult;
	}
?>