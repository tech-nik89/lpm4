<?php

require_once($mod_dir . "/detailedpoll.function.php");

$lang->addModSpecificLocalization($mod);
$mode = @$_GET['mode'];
$pollid = (int) @$_GET['pollid'];
$poll = getPollById($pollid);
	
$right['editor'] = $rights->isAllowed($mod, 'editor');
	
//$breadcrumbs->addElement($lang->get('clans'), makeURL($mod));
//$menu->addSubElement($mod, $lang->get('newclan'), 'newclan');

if($right['editor']) {
	$menu->addSubElement($mod, $lang->get('poll_create'), 'create');
}
$smarty->assign('rights', $right);
$breadcrumbs->addElement($lang->get('detailedpoll'), makeURL($mod));

switch($mode) {

	case 'create':
		if(!$right['editor'] || $poll['state'] != 0) {
			break;
		}
		
		$breadcrumbs->addElement($lang->get('poll_create'), makeURL($mod, array('mode'=>'create')));
	
		$polltitle = @trim($_POST['poll']['title']);
		$polldescription = @$_POST['poll']['description'];
		
		if(isset($_POST['send']) && $polltitle != '') {
			
			// Poll exists
			if($poll) {
				updatePoll($pollid, $polltitle, $polldescription);
			// Create new poll
			} else {
				$pollid = savePoll($polltitle, $polldescription, $login->currentUserId());
				redirect(makeUrl('detailedpoll', array('mode' => 'create', 'pollid' => $pollid)));
			}

			foreach($_POST['data'] as $key => $dataset) {
				if(isset($dataset['delete'])) {
					deleteQuestion($key);
				}
				$rank = $dataset['rank'];
				$parentid = $dataset['parentid'];
				$title = trim($dataset['title']);
				$description = $dataset['description'];
				$percent = $dataset['percent'];
				if(!parentExists($pollid, $parentid)) {
					$notify->add($lang->get('detailedpoll'), sprintf($lang->get('notify_parent_missing'), $parentid, $title));
				} else {
					if($title != '') {
						if($key == 'new') {
							saveQuestion($pollid, $title, $description, $rank, $parentid, $percent);
						} else {
							updateQuestion($key, $title, $description, $rank, $parentid, $percent);
						}
					}	
				}
			}
		}
		
		if($pollid>0) {
			$poll = getPollById($pollid);
			if($poll) {
				$smarty->assign('poll', $poll);
				$smarty->assign('questions', getQuestions($pollid));
			}
		} 
	
		$smarty->assign('singlequestion_path', $template_dir.'/questions_single.tpl');
		$smarty->assign('path', $template_dir.'/create.tpl');
		break;
	
	case 'delete':
		if(!$right['editor']) {
			break;
		}
		
		if(deletePoll($pollid)) {
			$notify->add($lang->get('detailedpoll'), $lang->get('notify_delete_successfull'));
		} else {
			$notify->add($lang->get('detailedpoll'), $lang->get('notify_delete_unsuccessfull'));
		}
		redirect(makeUrl('detailedpoll', array()));
		break;
		
	case 'state_change':
		if(!$right['editor']) {
			break;
		}
		
		$breadcrumbs->addElement($poll['title'], makeURL($mod, array('pollid'=>$poll['detailedpollid'])));
		$breadcrumbs->addElement($lang->get('poll_state_change'), makeURL($mod, array('pollid'=>$poll['detailedpollid'], 'mode'=>'state_change')));
	
		if(isset($_POST['send'])) {
			$state = (int) $_POST['state'];
			if($state >=0 && $state <= 3) {
				savePollState($pollid, $state);
				$poll['state'] = $state;
				$notify->add($lang->get('detailedpoll'), $lang->get('notify_state_change_succesfull'));
			}
		}
		
		$smarty->assign('poll', $poll);
		$smarty->assign('path', $template_dir.'/state_change.tpl');
		break;
		
	default:
		if($poll) {
			if($right['editor']) {
				$menu->addSubElement($mod, $lang->get('poll_state_change'), 'state_change', array('pollid' => $pollid));
			}
			
			$breadcrumbs->addElement($poll['title'], makeURL($mod, array('pollid'=>$poll['detailedpollid'])));
			
			if(isset($_POST['send'])) {
				$sendOk = true;
				foreach($_POST['values'] as $value) {
					if($value == '') {
						$sendOk = false;
						break;
					}
				}
				if($sendOk) {
					if(hasVoted($pollid, $login->currentUserId())) {
						$sendResult = updateAnswers($pollid, $login->currentUserId(), $_POST['values']);
					} else {
						$sendResult = saveAnswers($pollid, $login->currentUserId(), $_POST['values']);
					}
					if($sendResult>0) {
						$notify->add($lang->get('detailedpoll'), $lang->get('notify_send_successfull'));
						redirect(makeUrl('detailedpoll', array()));
					} else {
						$notify->add($lang->get('detailedpoll'), $lang->get('notify_send_unsuccessfull'));
					}
				} else {
					$notify->add($lang->get('detailedpoll'), $lang->get('notify_send_unsuccessfull_fields_missing'));
					$smarty->assign('values', $_POST['values']);
				}
			} 

			if(hasVoted($pollid, $login->currentUserId())) {
				$smarty->assign('values', getMyAnswers($pollid, $login->currentUserId()));
			}
			$smarty->assign('sendAvailable', ($poll['state'] == 1));
			$smarty->assign('resultAvailable', ($poll['state'] >= 2));
			
			$smarty->assign('poll', $poll);
			if($poll['state'] >= 2) {
				$questions = calculatePoll($pollid);
				$smarty->assign('result', getCalculatedPoll($questions));
			} else {
				$questions = getQuestions($pollid);
			}
			$smarty->assign('questions', $questions);
			
			$colors=array(0=>'#aaa',1=>'#bbb',2=>'#ccc',3=>'#ddd');
			$smarty->assign('color', $colors);
		
			$smarty->assign('path', $template_dir.'/poll.tpl');
			
		} else {
			$allpolls = getPolls();
		
			$smarty->assign('polls', $allpolls);
			$smarty->assign('path', $template_dir.'/polls.tpl');
		}
		break;
}

?>