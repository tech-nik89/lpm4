<?php
	$lang->addModSpecificLocalization('poll');
	$tpl_file = $template_dir."/pollbox.tpl";
	
	include_once "./mod/default/poll/poll.class.php";
	$pollclass = new poll($db, 100, explode(",",$config->get("poll", "barcolor")));
	$poll = $pollclass->getRandomPoll();

	if ($poll['name']!='') {
		$poll['url'] = makeURL('poll', array('pollid' => $poll['ID']));
		
		$pollbox['poll'] = $poll;
		$smarty->assign('pollbox', $pollbox);

		$smarty->assign('tworower', $config->get('poll', 'box-show-bar-in-second-row'));
		$smarty->assign('layout', $config->get('poll', 'box-layout'));
	} else {
		$tpl_file = $template_dir."/nopoll.tpl";
	}
	$visible = true;
?>