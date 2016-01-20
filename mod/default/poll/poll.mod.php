<?php
$pollsperpage=$config->get("poll", "polls-per-page");

// include language file
$lang->addModSpecificLocalization($mod);

// add breadcrumbs
$breadcrumbs->addElement($lang->get('mod_name'), makeURL($mod));

//Add Submenu
$menu->addSubElement($mod, $lang->get('archiv'), 'archiv');

//add class
include $mod_dir."poll.class.php";
$poll=new poll($db, $config->get("poll", "maxbarlength"), explode(",",$config->get("poll", "barcolor")));

// create tablenames
$tbl_poll = MYSQL_TABLE_PREFIX . 'poll';
$tbl_questions = MYSQL_TABLE_PREFIX . 'poll_questions';

if($rights->isAllowed($mod, 'editor'))
{
	$menu->addSubElement($mod, $lang->get('poll_create'), 'create');
	$right['editor'] = true;
	$smarty->assign('editor', 'true');
}

$smarty->assign('headline',$lang->get('mod_name'));

$smarty->assign('answers', $lang->get('answers'));
$smarty->assign('voters', $lang->get('voters'));


if($_GET['mod']=='poll')
{
	switch($_GET['mode'])
	{
		//
		//Edit
		//
		case 'edit':
			if($right['editor'])
			{
				$breadcrumbs->addElement($lang->get('edit'), makeURL($mod, array('mode' => 'edit')));
				$smarty->assign('path', $template_dir.'/poll.edit.tpl');

				$add_failed=true;
				
				if (isset($_POST['submit_reset'])) {
					$poll->resetPoll($_POST['pollID']);
				}
				else {
					//Poll has been saved
					if(isset($_POST['send']) && $_POST['send']==1)
					{
						
						if(trim($_POST['pollname']!=""))
						{
							$pollname=trim($_POST['pollname']);
							$add_failed=false;
						}
						else
						{
							$smarty->assign('value_pollname', $_POST['pollname']);
							$smarty->assign('value_questions', $_POST['question']);
							$smarty->assign('value_checkbox', $_POST['buttontype']);
							$add_failed=true;
						}

						if(!$add_failed)
						{
							//Edit the values
							$buttontype=(isset($_POST['buttontype']) && $_POST['buttontype']=="on")?"checkbox":"radio";
							$poll->updatePoll(convertLineBreaks($_POST['pollname']),$_POST['pollID'],$buttontype);

							if(isset($_POST['equestion']))
							{
								foreach($_POST['equestion'] as $questionID => $questionText)
								{
									if(trim($questionText)!="")
									{
										$poll->updateQuestion($questionText, $questionID);
									}
									else
									{
										$poll->deleteQuestionByID($questionID);
									}
								}
							}	
								
							if(isset($_POST['question']))
							{
								foreach($_POST['question'] as $question)
								{
									if(trim($question)!="")
									{
										$poll->addQuestion($question, $_POST['pollID']);
									}
								}
							
							} 
							$notify->add($lang->get('mod_name'),$lang->get('edit_successfull'));
							$smarty->assign('only_info',1);
						}
						else
						{
							$smarty->assign('info',$lang->get('edit_failed'));
						}
					}
					if($add_failed)
					{
						$pollID=(int) $_POST['edit'];
						if($poll->existsPoll($pollID))
						{
							$smarty->assign('headline', $lang->get('poll_edit'));

							$thispoll=$poll->getPollByID($pollID, $login->currentUserID());
							$smarty->assign('poll', $thispoll);
							$questions=$poll->getQuestionsByPollID($pollID);
							$counter=1;
							foreach($questions AS $question)
							{
								$question['title']=$lang->get('header_question')." ".$counter++;
								$questions_show[]=$question;
							}
								$smarty->assign('value_questions', $questions_show);

								$smarty->assign('count_start', $counter-1);
								$smarty->assign('count_questions', $config->get("poll", "maximum-questions"));
								$smarty->assign('header_question', $lang->get('header_question'));

								$smarty->assign('header_pollname', $lang->get('header_pollname'));
								$smarty->assign('submit_edit', $lang->get('submit_edit'));
								$smarty->assign('multiple_answers', $lang->get('multiple_answers'));
						}
					}
				}
			}
			break 1;
		//
		//Delete
		//
		case 'delete':
			if($right['editor'])
			{
				$breadcrumbs->addElement($lang->get('poll_delete'), makeURL($mod, array('mode' => 'delete')));
				$smarty->assign('headline', $lang->get('poll_delete'));

				if(isset($_POST['imsure']) && $_POST['imsure']=="1")
				{
					$poll->deletePoll($_POST['pollID']);
					$poll->deleteQuestionByPID($_POST['pollID']);
					$smarty->assign('deleted', 1);
					$smarty->assign('info', $lang->get('delete_successfull'));
				}
				else
				{
					$smarty->assign('confirm_delete', $lang->get('really_delete'));
					$smarty->assign('delete_yes', $lang->get('yes'));
					$smarty->assign('poll_ID', $_POST['delete']);
					$smarty->assign('delete_no', $lang->get('no'));
					$smarty->assign('goto_umfrage', makeURL($mod, array()));
				}
				$smarty->assign('path', $template_dir.'/poll.delete.tpl');
			}
			break 1;
		//
		//Create
		//
		case 'create':
			if($right['editor'])
			{
				$breadcrumbs->addElement($lang->get('poll_create'), makeURL($mod, array('mode' => 'create')));
				$smarty->assign('headline', $lang->get('poll_create'));

				$add_failed=true;
				//Poll has been saved
				if(isset($_POST['send']) && $_POST['send']==1)
				{
					if(trim($_POST['pollname']!="") && trim($_POST['question'][0])!="" && trim($_POST['question'][1])!="")
					{
						$pollname=trim($_POST['pollname']);
						$add_failed=false;
					}else{
						$smarty->assign('value_pollname', $_POST['pollname']);
						$smarty->assign('value_questions', $_POST['question']);
						$smarty->assign('value_checkbox', (isset($_POST['buttontype'])?$_POST['buttontype']:""));
						$add_failed=true;
					}

					if(!$add_failed){
						//Save the values
						if(isset($_POST['submit_add']))
						{
							$buttontype=(isset($_POST['buttontype']) && $_POST['buttontype']=="on")?"checkbox":"radio";
							$poll->addPoll($_POST['pollname'], $buttontype);
							$pollID=mysql_insert_id();
							foreach($_POST['question'] as $question)
							{
								if(trim($question)!="")
								{
									$poll->addQuestion($question, $pollID);
								}
							}
							$smarty->assign('info',$lang->get('add_successfull'));
							$smarty->assign('only_info',1);
						}
					}else{
						$smarty->assign('info',$lang->get('add_failed'));
					}
				}

				if($add_failed)	{
					$smarty->assign('count_questions', $config->get("poll", "maximum-questions"));
					$smarty->assign('header_question', $lang->get('header_question'));

					$smarty->assign('submit_add', $lang->get('submit_add'));
					$smarty->assign('header_pollname', $lang->get('header_pollname'));
					$smarty->assign('multiple_answers', $lang->get('multiple_answers'));
					
					$smarty->assign('path', $template_dir.'/poll.create.tpl');
					break 1;
				} else {
					$notify->add($lang->get('mod_name'), $lang->get('add_successfull'));
					$smarty->assign('path', $template_dir.'/poll.view.tpl');
					$_GET['mode']='default';
				}
			}

		//
		//Archiv
		//
		case 'archiv':
			$breadcrumbs->addElement($lang->get('archiv'), makeURL($mod, array('mode' => 'archiv')));

			$countpolls=$poll->countPolls();
			if($countpolls>0)
			{
				if(isset($_POST['inactive']))
				{
					if($poll->existsPoll($_POST['inactive']))
					{
						$inactive=$poll->switchActive($_POST['inactive']);
					}
				}

				$pageat=(isset($_GET['page']) && (int) $_GET['page']>0 && (int) $_GET['page']<=ceil($countpolls/$pollsperpage))?(int) $_GET['page']:1;
				$pages->setValues($pageat, $pollsperpage, $countpolls);
				$smarty->assign('pages', $pages->get("poll", array('mode'=>'archiv')));
				
				$smarty->assign('polls', $poll->getPolls(($pageat-1)*$pollsperpage, $pollsperpage));				
			}
			else
			{
				$smarty->assign('no_poll', $lang->get('no_poll'));
			}

			$smarty->assign('comments', $lang->get('comment_count'));
			
			$smarty->assign('number_of_voters', $lang->get('number_of_voters'));
			$smarty->assign('headline', $lang->get('archiv'));
			$smarty->assign('inactive', $lang->get('submit_inactive'));
			$smarty->assign('active', $lang->get('submit_active'));
			$smarty->assign('edit', makeURL('poll', array('mode'=>'edit')));
			$smarty->assign('submit_edit', $lang->get('edit'));
			$smarty->assign('delete', makeURL('poll', array('mode'=>'delete')));
			$smarty->assign('submit_delete', $lang->get('submit_delete'));
			
			$smarty->assign('main_layout', $config->get('poll', 'main-layout'));
			
			$smarty->assign('path', $template_dir.'/poll.view.tpl');
			break 1;

		//
		//Show the active polls
		//
		default:
			$smarty->assign('main_layout', $config->get('poll', 'main-layout'));
			
			if(isset($_POST['inactive']))
			{
				if($poll->existsPoll($_POST['inactive']))
				{
					$inactive=$poll->switchActive($_POST['inactive']);
				}
			}
		
			if(isset($_POST['send']) && $_POST['send']==1 && !$poll->hasAllreadyVoted($login->currentUserID(), $_POST['poll']))
			{  
				if( isset($_POST['question'.$_POST['poll']]) && is_array($_POST['question'.$_POST['poll']]) && $_POST['question'.$_POST['poll']]!=null)
				{
					$voted=$poll->vote($login->currentUserID(), $_POST['poll'], $_POST['question'.$_POST['poll']]);
				}
			}

			// show a single poll
			if(isset($_GET['pollid']))
			{
				if(isset($_POST['send_comment']))
				{
					$comments->add('poll', $login->currentUserId(), $_POST['message'], $_GET['pollid']);
				
				}
				
				$poll_comments=$comments->get('poll', $_GET['pollid']);
				$poll_final_comments=array();
				foreach($poll_comments as $poll_comment)
				{
					$poll_comment['nickname']=makeHtmlUrl($poll_comment['nickname'], makeUrl('profile', array('userid'=>$poll_comment['userid'])));
					$poll_comment['date']=date("d.m.Y H:i:s", $poll_comment['timestamp']);
					
					$poll_final_comments[]=$poll_comment;
				}
				
				$smarty->assign('all_comments', $poll_final_comments);
				
				$p = $poll->getPollByID($_GET['pollid'], $login->currentUserID());
				$smarty->assign('poll', $p);
				$breadcrumbs->addElement(cutString($p['name'], 20), makeURL($mod, array('pollid' => $_GET['pollid'])));
				
				$smarty->assign('path', $template_dir.'/poll.singleview.tpl');
			}
			//show mutliple polls
			else
			{
				$countpolls=$poll->countActivePolls();
				if($countpolls>0)
				{
					$pageat=(isset($_GET['page']) && (int) $_GET['page']>0 && (int) $_GET['page']<=ceil($countpolls/$pollsperpage))?(int) $_GET['page']:1;
					$pages->setValues($pageat, $pollsperpage, $countpolls);
					$smarty->assign('pages', $pages->get('poll', array('')));

					$smarty->assign('polls', $poll->getActivePolls($login->currentUserID(),($pageat-1)*$pollsperpage, $pollsperpage));
				}
				else
				{
					$smarty->assign('no_poll', $lang->get('no_poll'));
				}
				$smarty->assign('path', $template_dir.'/poll.view.tpl');
			}
			
			$smarty->assign('comments', $lang->get('comment_count'));
			
			$smarty->assign('submit_value', $lang->get('submit_vote'));
			$smarty->assign('number_of_voters', $lang->get('number_of_voters'));
			$smarty->assign('inactive', $lang->get('submit_inactive'));
			$smarty->assign('active', $lang->get('submit_active'));
			$smarty->assign('edit', makeURL('poll', array('mode'=>'edit')));
			$smarty->assign('submit_edit', $lang->get('edit'));
			$smarty->assign('delete', makeURL('poll', array('mode'=>'delete')));
			$smarty->assign('submit_delete', $lang->get('submit_delete'));
			
			break 1;
	}
}

?>