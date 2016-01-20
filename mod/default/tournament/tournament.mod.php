<?php

	/* Tournament module main file */
	
	// Add lang file
	$lang->addModSpecificLocalization($mod);
	
	// Include common function
	require_once($mod_dir . "/tournament.function.php");
	
	require_once($mod_dir . "/register.class.php");
	global $register;
	$register = new Register();
	
	require_once($mod_dir . "/tournamentList.class.php");
	global $tournamentList;
	$tournamentList = new tournamentList();
		
	// Credits
	require_once($mod_dir . "/credit.class.php");
	global $tCredit;
	$tCredit = new TournamentCredit();
	
	if ($login->currentUser() !== false) {
		$menu->addSubElement($mod, $lang->get('my_tournaments'), 'mytournaments');
		$smarty->assign('logged_in', true);
	}
	
	// Lists
	//$playerperteamlist[0] = "All on All";
	for ($i = 1; $i <= 64; $i++)
		$playerperteamlist[$i] = $i . " on " . $i;
	
	$smarty->assign('playerperteamlist', $playerperteamlist);
	
	
	for ($i = 1; $i <= 32; $i++)
		$winners_per_group[$i] = $i;
	
	$smarty->assign('winners_per_group_list', $winners_per_group);
	
	
	for ($i = 2; $i <= 32; $i++)
		$playerpergrouplist[$i] = $i;
	
	$smarty->assign('playerpergrouplist', $playerpergrouplist);
	
	$modes = array(1 => $lang->get('singleelimination'), 2 => $lang->get('doubleelimination'), 3 => $lang->get('group'), 4 => $lang->get('points'), 5 => $lang->get('randomize'), 6 => $lang->get('koth'), 7 => $lang->get('deathmatch'));
	
	// Add breadcrumb
	$breadcrumbs->addElement($lang->get('tournaments'), makeURL($mod));
	
	// Tournament ID
	@$tournamentid = (int)$_GET['tournamentid'];
	
	// Event ID
	@$eventid = (int)$_GET['eventid'];
	
	// Mode
	@$mode = $_GET['mode'];
	
	// Rights
	global $right;
	$right['add_remove_edit'] = $rights->isAllowed($mod, 'add_remove_edit');
	$right['submit_results'] = $rights->isAllowed($mod, 'submit_results');
	
	if ($tournamentid > 0) {
		global $tournament;
		$tournament = $tournamentList->getTournament($tournamentid);
	}
	
	switch ($mode)
	{
		default:
			
			// Add "add" submenu
			if ($right['add_remove_edit'])
				$menu->addSubElement($mod, $lang->get('tournament_add'), 'addtournament');
				
			// Add export menu
			if ($right['add_remove_edit'])
				$menu->addSubElement($mod, $lang->get('wwcl_export'), 'wwclexport');
			
			
			
			if ($tournamentid == 0)
			{
			
				// List events
				$eventList = listEvents();
				
				// Add events to submenu
				if (count($eventList) > 0)
				foreach($eventList as $event)
					$menu->addSubElement($mod, $event['name'], '', array('eventid' => $event['eventid']));
				
				if ($eventid > 0)
				{
					$event['name'] = $db->selectOne(MYSQL_TABLE_PREFIX . 'events', 'name', "`eventid`=".$eventid);
					$breadcrumbs->addElement($event['name'], makeURL($mod, array('eventid' => $eventid)));
				}
				
				if ($tournamentid == 0)
				{
					// Show tournament list
					$smarty->assign('path', $template_dir . "/overview.tpl");
					$smarty->assign('tournamentList', $tournamentList->getList($eventid));
					$smarty->assign('modenames', $modes);
				}
			
			} else {
				
				// Show tournament overview
				$tournament['event']['url'] = makeURL('events', array('eventid' => $tournament['event']['eventid']));
				$tournament['playerperteam_str'] = $tournament['playerperteam'] . " (" . $tournament['playerperteam'] . " on " . $tournament['playerperteam'] . ")";
				$tournament['mode_str'] = $modes[$tournament['mode']];
				$tournament['state_str'] = tournamentStateToString($tournament['state']);
				$tournament['start_str'] = date("d.m.Y H:i", $tournament['start']) . ' ' . timeLeft($tournament['start']);
				$tournament['wwclgameid_str'] = wwclGameIdToString($tournament['wwclgameid']);
				$tournament['mappool_str'] = str_replace(";", ", ", $tournament['mappool']);
				
				// add tree submenu
				if (($tournament['state'] == 2 || $tournament['state'] == 3) && $tournament['mode'] != 5)
					$menu->addSubElement($mod, $lang->get('tournament_table'), 'table', array('tournamentid' => $tournamentid));
				
				@$breadcrumbs->addElement($tournament['event']['name'], makeURL($mod, array('eventid' => $tournament['event']['eventid'])));
				$breadcrumbs->addElement($tournament['title'], makeURL($mod, array('tournamentid' => $tournamentid)));
				
				// Create a new instance of tournament class
				require_once($mod_dir."/tournament.abstract.class.php");
				require_once($mod_dir."/participant.interface.php");
				require_once($mod_dir."/singlePlayer.class.php");
				require_once($mod_dir."/group.class.php");
				require_once($mod_dir."/tournament.eliminationencounter.class.php");
				
				global $tournament_obj;

				switch ($tournament['mode'])
				{
					case 1: // Single Elimination
						require_once($mod_dir."/tournament.singleelimination.class.php");
						$tournament_obj = new SingleElimination($tournamentid, $register->getParticipants($tournament));
						break;
					case 2: // Double Elimination
						require_once($mod_dir."/tournament.doubleelimination.class.php");
						$tournament_obj = new DoubleElimination($tournamentid, $register->getParticipants($tournament));
						break;
					case 3: // Groups
						require_once($mod_dir."/tournament.groups.class.php");
						$tournament_obj = new Groups($tournamentid, $register->getParticipants($tournament));
						break;
					case 4: // Points
						require_once($mod_dir."/tournament.points.class.php");
						$tournament_obj = new Points($tournamentid, $register->getParticipants($tournament));
						break;
					case 5: // Randomize
						require_once($mod_dir."/tournament.randomize.class.php");
						$tournament_obj = new Randomize($tournamentid, $register->getParticipants($tournament));
						break;
					case 6: // King of the Hill
						require_once($mod_dir."/tournament.koth.class.php");
						$tournament_obj = new KotH($tournamentid, $register->getParticipants($tournament));
						break;
					case 7: // Deathmatch
						require_once($mod_dir."/tournament.deathmatch.class.php");
						$tournament_obj = new Deathmatch($tournamentid, $register->getParticipants($tournament));
						break;
				}
				
				switch ($mode)
				{
				
					default:
						
						$smarty->assign('path', $template_dir . "/view.tpl");
						$tournament['regcount'] = $register->getRegCount($tournamentid);
						
						// Joining stuff .. 
						if ($tournament['playerperteam'] == 1)
						{
							if (isset($_POST['join']))
								$register->joinTournament($tournamentid);
								
							if (isset($_POST['unjoin']))
								$register->leaveTournament($tournamentid);
							
							$smarty->assign('already_joined', $register->alreadyJoined($tournamentid));
							$smarty->assign('playerlist', $register->getPlayerList($tournament));
							
						} else {
							
							if (isset($_POST['creategroup'])) {
								if ($tournament['regcount'] < $tournament['playerlimit']) {
									$groupid = $register->createGroup($_POST['creategroup_name'], $_POST['creategroup_password'], $tournamentid);
									$register->joinGroup($tournament, $groupid, $_POST['creategroup_password']);
								}
							}
							
							if (isset($_POST['unjoin'])) {
								$register->leaveTournament($tournamentid);
							}
							
							if (isset($_POST['joingroup'])) {
								$register->joinGroup($tournament, $_POST['joingroup_groupid'], $_POST['joingroup_password']);
							}
							
							$smarty->assign('already_joined', $register->alreadyJoined($tournamentid));
							$smarty->assign('grouplist', $register->getPlayerList($tournament));
							
						}
						
						if ($tournament['start'] > time())
							$tournament['start_str'] = timeLeft($tournament['start']);
						else
							$tournament['start_str'] = timeElapsed($tournament['start']);
						
						// Ranking of tournament
						if ($tournament['state'] == 2 || $tournament['state'] == 3) {
							$ranking = $tournament_obj->ranking();
							$smarty->assign('ranking', $ranking);
						}
						
						
						$tournament['regcount'] = $register->getRegCount($tournamentid);
						$smarty->assign('tournament', $tournament);
						
						break;
					
					case 'editstate':
			
						if (!$right['add_remove_edit'])
							break;
						
						if (isset($_POST['save']))
						{
							$tournamentList->setState($tournamentid, $_POST['state']);
							$tournament['state'] = (int)$_POST['state'];
						}
						
						$breadcrumbs->addElement($lang->get('tournament_edit_state'), makeURL($mod, array('tournamentid' => $tournamentid, 'mode' => 'editstate')));
						
						$smarty->assign('tournament', $tournament);
						$smarty->assign('path', $template_dir . "/editstate.tpl");
						
						break;
					
					case 'table':
						$breadcrumbs->addElement($lang->get('tournament_table'), makeURL($mod, array('tournamentid' => $tournamentid, 'mode' => 'table')));
						$tournament_obj->tournamentTable();
						break;
						
					case 'view':
						if(isset($_GET['undo']) && $_GET['undo'] == 1 && $config->get('tournament', 'allow_undoing_encounter_points') && $right['add_remove_edit'] && $tournament['state']==2) {
							if($tournament_obj->undoSetEncounter((int)$_GET['roundid'], (int)$_GET['encid'])) {
								$notify->add($lang->get('tournament'), $lang->get('ntfy_undo_set_points_successful'));
							} else {
								$notify->add($lang->get('tournament'), $lang->get('ntfy_undo_set_points_unsuccessful'));
							}
						}
					
						$breadcrumbs->addElement($lang->get('tournament_table'), makeURL($mod, array('tournamentid' => $tournamentid, 'mode' => 'table')));
						if (isset($_POST['submit']))
							$tournament_obj->submitResults((int)$_GET['roundid'], (int)$_GET['encid']);
						else
							$tournament_obj->encounter((int)$_GET['roundid'], (int)$_GET['encid']);
						break;
				}
			}
			
			break;

		case 'edittournament':
			
			if (!$right['add_remove_edit'])
				break;
			
			if (isset($_POST['save']))
			{
				if (trim($_POST['title']) == '' || (int)$_POST['playerlimit'] < 2 || trim($_POST['game']) == '')
					$notify->add($lang->get('tournament'), $lang->get('fill_all_fields'));
				else
				{
					$start = mktime($_POST['Time_Hour'], $_POST['Time_Minute'], 0, $_POST['Date_Month'], $_POST['Date_Day'], $_POST['Date_Year']);
					
					// Update tournament
					$tournamentList->editTournament($tournamentid, 
							$_POST['eventid'], $_POST['title'], $_POST['playerlimit'], $_POST['game'], $_POST['mappool'], 
							$_POST['mode'], $_POST['playerperteam'], $_POST['picture'], $_POST['credits'], $_POST['wwclgameid'], $_POST['rules'], 
							$start, $_POST['duration'], $_POST['breaktime']);
					
					// Update mode specific settings
					switch ( $_POST['mode'] ) {
						
						case '1':
							// Single Elimination
							@$mixed = $_POST['single_elimination_mixed_game'] == '1' ? 1 : 0;
							$tournamentList->setSetting($tournamentid, 0, $mixed);
							
							@$third = $_POST['single_elimination_third_place_game'] == '1' ? 1 : 0;
							$tournamentList->setSetting($tournamentid, 1, $third);
							
							break;
						
						case '2':
							// Double Elimination
							@$mixed = $_POST['double_elimination_mixed_game'] == '1' ? 1 : 0;
							$tournamentList->setSetting($tournamentid, 0, $mixed);
							
							break;
						
						case '3':
							// Groups
							@$ppg = (int)$_POST['groups_playerpergroup'];
							$tournamentList->setSetting($tournamentid, 0, $ppg);
							
							@$wpg = (int)$_POST['groups_winnerpergroup'];
							$tournamentList->setSetting($tournamentid, 1, $wpg);
						
							break;		

						case '6':
							// King of the Hill
							$tournamentList->setSetting($tournamentid, 0, $_POST['number_of_rounds']);
							
							break;						
					}				
					
					$notify->add($lang->get('tournament'), $lang->get('save_done'));
				}
			}
			
			$tournament = $tournamentList->getTournament($tournamentid);
			
			$breadcrumbs->addElement($tournament['event']['name'], makeURL($mod, array('eventid' => $tournament['event']['eventid'])));
			$breadcrumbs->addElement($tournament['title'], makeURL($mod, array('tournamentid' => $tournamentid)));
			$breadcrumbs->addElement($lang->get('tournament_edit'), makeURL($mod, array('tournamentid' => $tournamentid, 'mode' => 'edittournament')));
			
			$smarty->assign('tournament', $tournament);
			$smarty->assign('wwcl_game_ini', parseWwclGameIni());
			$smarty->assign('rules', listRules());
			$smarty->assign('path', $template_dir . "/addtournament.tpl");
			$smarty->assign('action', $lang->get('tournament_edit'));
			
			// mode specific settings
			switch ($tournament['mode']) {
				case 1:	// Single Elimination
					$smarty->assign('single_elimination_mixed_game', $tournamentList->getSetting($tournamentid, 0));
					$smarty->assign('single_elimination_third_place_game', $tournamentList->getSetting($tournamentid, 1));
					break;
				case 3: // Groups
					$smarty->assign('playerpergroup', $tournamentList->getSetting($tournamentid, 0));
					$smarty->assign('winnerpergroup', $tournamentList->getSetting($tournamentid, 1));
					break;
				case 6: // King of the Hill
					$smarty->assign('number_of_rounds', $tournamentList->getSetting($tournamentid, 0));
					break;
			}
			
			// List events
			$eventList = listEvents();
			if (count($eventList) > 0)
			foreach ($eventList as $event)
				$list[$event['eventid']] = $event['name'];
			
			$smarty->assign('eventlist', $list);
			
			break;
		
		case 'mytournaments':
			
			if ($login->currentUser() === false)
				break;
			
			$breadcrumbs->addElement($lang->get('my_tournaments'), makeURL($mod, array('mode' => 'mytournaments')));
			
			$smarty->assign('path', $template_dir."/mytournaments.tpl");
			$smarty->assign('credits', $tCredit->getValues($login->currentUserId()));
			
			$mytournaments = makeTournamentList(getTournaments($login->currentUserId()), $login->currentUserId());
			
			if($mytournaments) {
				$smarty->assign('tournaments', $mytournaments);
			// No Tournaments
			} else {
				$smarty->assign('no_tournaments', $lang->get("no_tournaments_registered"));
			}

			if ($mytournaments && count($mytournaments) > 0) {
				foreach ($mytournaments as $i => $tourn) {
					$mytournaments[$i]['url'] = makeURL($mod, array('tournamentid' => $tourn['tournamentid']));
					$mytournaments[$i]['color'] = makeListColor($tourn['state']);
				}
			}			
			
			$smarty->assign('mytournaments', $mytournaments);
				
			
			break;
		
		case 'removetournament':
			
			if (!$right['add_remove_edit'])
				break;
			
			if (isset($_POST['yes'])) {
				$tournamentList->removeTournament($tournamentid);
				$notify->add($lang->get('tournament'), $lang->get('tournament_remove_done'));
			}
			else {
				$smarty->assign('path', $template_dir."/removetournament.tpl");
				$smarty->assign('url_no', makeURL($mod, array('tournamentid' => $tournamentid)));
				//$tournament = $tournamentList->getTournament($tournamentid);
			
				$breadcrumbs->addElement($tournament['event']['name'], makeURL($mod, array('eventid' => $tournament['event']['eventid'])));
				$breadcrumbs->addElement($tournament['title'], makeURL($mod, array('tournamentid' => $tournamentid)));
				$breadcrumbs->addElement($lang->get('tournament_remove'), makeURL($mod, array('tournamentid' => $tournamentid, 'mode' => 'removetournament')));
			}
			
			break;
			
		case 'addtournament':
			
			if (!$right['add_remove_edit'])
				break;
			
			// List events
			$eventList = listEvents();
			if (count($eventList) > 0)
			foreach ($eventList as $event)
				$list[$event['eventid']] = $event['name'];
				
			$smarty->assign('eventlist', $list);
			
			$breadcrumbs->addElement($lang->get('tournament_add'), makeURL($mod, array('mode' => 'addtournament')));
				
			if (isset($_POST['save']))
			{
				if (trim($_POST['title']) == '' || (int)$_POST['playerlimit'] < 2 || trim($_POST['game']) == '')
					$notify->add($lang->get('tournament'), $lang->get('fill_all_fields'));
				else
				{
					$start = mktime($_POST['Time_Hour'], $_POST['Time_Minute'], 0, $_POST['Date_Month'], $_POST['Date_Day'], $_POST['Date_Year']);
					
					// create new tournament
					$tournamentid = $tournamentList->addTournament(
						$_POST['eventid'], $_POST['title'], $_POST['playerlimit'], $_POST['game'], $_POST['mappool'], 
						$_POST['mode'], $_POST['playerperteam'], $_POST['picture'], $_POST['credits'], $_POST['wwclgameid'], $_POST['rules'], 
						$start, $_POST['duration'], $_POST['breaktime']);
					
					
					// Update mode specific settings
					switch ( $_POST['mode'] ) {
						
						case '1':
							// Single Elimination
							@$mixed = $_POST['single_elimination_mixed_game'] == '1' ? 1 : 0;
							$tournamentList->addSetting($tournamentid, 0, $mixed);
							
							@$third = $_POST['single_elimination_third_place_game'] == '1' ? 1 : 0;
							$tournamentList->addSetting($tournamentid, 1, $third);
							
							break;
						
						case '2':
							// Double Elimination
							@$mixed = $_POST['double_elimination_mixed_game'] == '1' ? 1 : 0;
							$tournamentList->addSetting($tournamentid, 0, $mixed);
							
							break;
						
						case '3':
							// Groups
							@$ppg = (int)$_POST['groups_playerpergroup'];
							$tournamentList->addSetting($tournamentid, 0, $ppg);
							
							@$wpg = (int)$_POST['groups_winnerpergroup'];
							$tournamentList->addSetting($tournamentid, 1, $wpg);
						
							break;			

						case '6':
							// King of the hill
							$tournamentList->addSetting($tournamentid, 0, (int) $_POST['number_of_rounds']);
							
							break;							
					}
					
					redirect(makeURL($mod, array('tournamentid' => $tournamentid)));
					// $notify->add($lang->get('tournament'), $lang->get('save_done'));
				}
			}
			
			$smarty->assign('path', $template_dir . "/addtournament.tpl");
			$smarty->assign('action', $lang->get('tournament_add'));
			$smarty->assign('wwcl_game_ini', parseWwclGameIni());
			$smarty->assign('rules', listRules());
			
			break;
				
		case 'viewgroup':
			
			//$tournament = $tournamentList->getTournament($tournamentid);
			$tournament['url'] = makeURL($mod, array('tournamentid' => $tournamentid));
			
			$breadcrumbs->addElement($tournament['event']['name'], makeURL($mod, array('eventid' => $tournament['event']['eventid'])));
			$breadcrumbs->addElement($tournament['title'], makeURL($mod, array('tournamentid' => $tournamentid)));
			
			$group = $db->selectOneRow(MYSQL_TABLE_PREFIX . 'tournamentgroups', "*", "`groupid`=".(int)$_GET['groupid']);
			$breadcrumbs->addElement($group['name'], makeURL($mod, array('tournamentid' => $tournamentid, 'groupid' => $group['groupid'], 'mode' => 'viewgroup')));
			
			$group['founder'] = $user->getUserById($group['founderid']);
			$group['founder']['url'] = makeURL('profile', array('userid' => $group['founderid']));
			
			$tbl_gr = MYSQL_TABLE_PREFIX . 'tournamentgroupregister';
			$tbl_u = MYSQL_TABLE_PREFIX . 'users';
			
			$members = $db->selectList($tbl_gr . '`, `' . $tbl_u, "*",
				"`tournamentid`=".$tournamentid." AND `groupid`=".$group['groupid']."
				AND `".$tbl_gr."`.`memberid`=`".$tbl_u."`.`userid`");
			
			foreach ($members as $i => $member) {
				$members[$i]['url'] = makeURL('profile', array('userid' => $member['userid']));
			}
			
			$group['members'] = $members;
			
			$smarty->assign('tournament', $tournament);
			$smarty->assign('group', $group);
			$smarty->assign('path', $template_dir."/viewgroup.tpl");
			
			break;
			
		case 'wwclexport':
			if (!$right['add_remove_edit'])
				break;
				
			$menu->addSubElement($mod, $lang->get('wwcl_export'), 'wwclexport');
			$breadcrumbs->addElement($lang->get('wwcl_export'), makeURL($mod, array('mode' => 'wwclexport')));
			$smarty->assign('partys', $db->selectList(MYSQL_TABLE_PREFIX . 'events', "*"));
			
			if(isset($_POST['selectEvent']) && $_POST['eventid'] >= 0){
				$event = $db->selectOneRow(MYSQL_TABLE_PREFIX . 'events', "*", "`eventid`=".(int)$_POST['eventid']);
				$smarty->assign('eventname', $event['name'] );
				$smarty->assign('eventid', $_POST['eventid']);
				$userfields =  $db->selectList(MYSQL_TABLE_PREFIX . 'personal_fields', "*");
				$smarty->assign('userfields', $userfields);
				
				// Try guessing the wwcl field
				$bestFittingField = -1;
				$highestPercent = 50;
				foreach($userfields as $field) {
					similar_text("wwcl id", strtolower($field['value']), $curPercent);
					if($curPercent > $highestPercent) {
						$bestFittingField = $field['fieldid'];
						$highestPercent = $curPercent;
					}
				}
				// Assign preset values
				$smarty->assign('eventinformation', array('eventname' => $event['name'], 'wwcliduserfield'=>$bestFittingField));
				
				$smarty->assign('tournamentlist', $db->selectList(MYSQL_TABLE_PREFIX . 'tournamentlist', "*", "`state`=3 AND `eventid`=".(int) $_POST['eventid']));	//Only finished games can be exported
			}
			
			if (isset($_POST['doBackup'])) {
				$event = $db->selectOneRow(MYSQL_TABLE_PREFIX . 'events', "*", "`eventid`=".(int)$_POST['eventid']);
				$smarty->assign('eventname', $event['name']);
				$smarty->assign('eventid', (int) $_POST['eventid']);

				$eventinformation = array();
				$eventinformation['eventname'] = $_POST['eventname'];
				$eventinformation['partyid'] = $_POST['partyid'];
				$eventinformation['organizerid'] = $_POST['organizerid'];
				$eventinformation['partycity'] = $_POST['partycity'];
				$eventinformation['wwcliduserfield'] = $_POST['wwcluserid'];

				if(@count($_POST['tournaments']) != 0){
					$b = makeWWCLExport($eventid, $_POST['tournaments'], $eventinformation);
					if (isset($_POST['download'])) {
						$file = "backup-".date("dmY-Hi").".xml";
						header("Content-type: application/octet-stream");
						header('Content-disposition: attachment; filename='.$file);
						echo $b; 
						die();
					}
					else {
						$smarty->assign('exportdata', $b);
					}
				} 
				$smarty->assign('userfields', $db->selectList(MYSQL_TABLE_PREFIX . 'personal_fields', "*"));
				$smarty->assign('eventinformation', $eventinformation);
				$smarty->assign('tournamentlist', $db->selectList(MYSQL_TABLE_PREFIX . 'tournamentlist', "*", "`state`=3 AND `eventid`=".$_POST['eventid']));	//Only finished games can be exported
			}
			

			$smarty->assign('path', $template_dir."/wwclexport.tpl");
			
			break;
			
		case 'beamer':
			
			require_once($mod_dir."/tournament.beamer.php");
			
			break;
	}

	
	// Add edit menus
	if ($tournamentid > 0) {
		if ($right['add_remove_edit'])
		{
			$menu->addSubElement($mod, $lang->get('tournament_edit_state'), 'editstate', array('tournamentid' => $tournamentid));
			if (isset($tournament) && $tournament['state'] == 0) {
				$menu->addSubElement($mod, $lang->get('tournament_edit'), 'edittournament', array('tournamentid' => $tournamentid));
				$menu->addSubElement($mod, $lang->get('tournament_remove'), 'removetournament', array('tournamentid' => $tournamentid));
			}
		}
	}
?>