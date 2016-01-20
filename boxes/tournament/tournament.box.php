<?php
	$lang->addModSpecificLocalization('tournament');

	
	require_once("./mod/default/tournament/tournament.function.php");
	require_once("./mod/default/tournament/participant.interface.php");
	require_once("./mod/default/tournament/singlePlayer.class.php");

	// Check if a user is logged in
	if($login->currentUser()!==false) {
		$finalTourneys = makeTournamentList(getTournaments($login->currentUserId(), $config->get('tournament', 'box_number_of_results')), $login->currentUserId());
		
		if($finalTourneys) {
			$smarty->assign('tournaments', $finalTourneys);
			$tpl_file = $template_dir."/tournament.tpl";
		} else {
			$smarty->assign('info', $lang->get("no_tournaments_registered")."<br />".makeHTMLUrl($lang->get('tournaments'), makeUrl('tournament')));
			$tpl_file = $template_dir."/none.tpl";
		}
	//
	} else {					 
		$smarty->assign('info', $lang->get("please_log_in"));
		$tpl_file = $template_dir."/none.tpl";
	}

	$visible = true;

?>