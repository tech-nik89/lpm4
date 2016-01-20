<?php
	
	function tournamentStateToString($state)
	{
		global $lang;
		
		/* 
		 * Tournament states
		 * 0 = Inactive
		 * 1 = Joining
		 * 2 = Running
		 * 3 = Finished
		 * 
		 */
		
		switch ($state)
		{
			case 0: return $lang->get('state_inactive');
			case 1: return $lang->get('state_joining');
			case 2: return $lang->get('state_running');
			case 3: return $lang->get('state_finished');
		}
	}
	
	function listEvents()
	{
		global $db;
		$result = $db->selectList(MYSQL_TABLE_PREFIX . 'events');
		return $result;
	}
	
	function xml_parse_into_assoc($file) 
	{
		$data = implode("", file($file));
		$p = xml_parser_create();
		 
		xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
		 
		xml_parse_into_struct($p, $data, $vals, $index);
		xml_parser_free($p);
		
		$levels = array(null);
		 
		foreach ($vals as $val) {
			if ($val['type'] == 'open' || $val['type'] == 'complete') {
				if (!array_key_exists($val['level'], $levels)) {
					$levels[$val['level']] = array();
				}
			}
		   
			$prevLevel =& $levels[$val['level'] - 1];
			$parent = $prevLevel[sizeof($prevLevel)-1];
		   
			if ($val['type'] == 'open') {
				$val['children'] = array();
				array_push($levels[$val['level']], $val);
				continue;
			}
			else if ($val['type'] == 'complete') {
			  @$parent['children'][$val['tag']] = $val['value'];
			}
			else if ($val['type'] == 'close') {
				$pop = array_pop($levels[$val['level']]);
				$tag = $pop['tag'];
				if ($parent) {
					if (!array_key_exists($tag, $parent['children'])) {
						$parent['children'][$tag] = $pop['children'];
					}
					else if (is_array($parent['children'][$tag])) {
						$parent['children'][$tag][] = $pop['children'];
					}
				}
				else {
					return(array($pop['tag'] => $pop['children']));
				}
			}
		   
			$prevLevel[sizeof($prevLevel)-1] = $parent;
		}
	}	
		
	function parseWwclGameIni()
	{
		$path = './media/wwcl/gameini.xml';
			
		$xml = xml_parse_into_assoc($path);
		
		$gameid[0] = 0;
		$gameini[0] = "Keine";
		
		$j = 0;
		foreach($xml['wwclgameini']['game'] as $i => $val)
		{
			$j++;
			if ((int)$val['id'] > 0 and $j > 6)
			{
				$gameid[$j-6] = (int)$val['id'];
				$gameini[$j-6] = $val['name'];
			}
		}
			
		$return['gameid'] = $gameid;
		$return['name'] = $gameini;
		
		return $return;
	}
	
	function listRules()
	{
		$list = scandir('./media/wwcl/rules/');
		foreach ($list as $i => $l)
		{
			if (substr($l, 0, 1) != '.')
				$rules[] = $l;
		}
		
		return $rules;
	}
	
	function wwclGameIdToString($wwclgameid)
	{
		$list = parseWWCLGameIni();
		
		foreach ($list['gameid'] as $i => $v)
		{	
			if ($v == $wwclgameid)
				return $list['name'][$i];
		}
	}
	
	function newDummy() {
		global $lang;
		return new singlePlayer(array('nickname' => $lang->get('bye'), 'userid' => -1), 1);
	}
	
	function newUnknown() {
		global $lang;
		return new singlePlayer(array('nickname' => $lang->get('unknown'), 'userid' => 0), -1);	
	}
	
	function spaceTabs($tabs) {
		$return = "";
		for ($i = 0; $i < $tabs; $i++) $return .= "\t";
		return $return;
	}
	
	function makeWWCLExport($eventid, $tournamentids, $eventinformation) {
	
		require_once("participant.interface.php");
		require_once("singlePlayer.class.php");
		require_once("group.class.php");
		require_once("register.class.php");
		$register = new Register();
		require_once("tournament.abstract.class.php");
		
		global $db;
		
		$userinfo=array(-1=>'F', 0=>'F');
		$br ="\n";
		
		$event_info = $db->selectOneRow(MYSQL_TABLE_PREFIX."events", "*", "eventid=".$eventid);
		
		$tourneys = array();
		$allusers = array('C' => array(), 'P' => array());
		$tmpplayer = array();

		$tmpid=1;	//The upcounting id
		foreach($tournamentids as $tournamentid) {

			$tournament = $db->selectOneRow(MYSQL_TABLE_PREFIX."tournamentlist", "*", "tournamentid=".$tournamentid);
			$playermode=($tournament['playerperteam']>1)?"C":"P";
						
			if($playermode=='P') {
				$tourneyusers = $db->selectList(MYSQL_TABLE_PREFIX."tournamentregister", "*", "tournamentid=".$tournamentid);
				foreach($tourneyusers as $tourneyuser) {
					if(!array_key_exists($tourneyuser['memberid'], $allusers[$playermode])){

						$info = $db->selectOneRow(MYSQL_TABLE_PREFIX."users", "nickname as name, email", "userid=".$tourneyuser['memberid']);
						
						// If a userfield has been selected for containing the wwcluserid
						if($eventinformation['wwcliduserfield']>=0) {
							$wwclfield = $db->selectOneRow(MYSQL_TABLE_PREFIX."personal_data", "*", "`userid`=".$tourneyuser['memberid']." AND `fieldid`=".$eventinformation['wwcliduserfield']);
							if(preg_match("/^[0-9]+/", $wwclfield['value']) > 0) {
								$curtmpid = $playermode.$wwclfield['value'];
							} else {
								$curtmpid = $playermode.'T'.$tmpid++;
							}
						} else {
							$curtmpid = $playermode.'T'.$tmpid++;
						}
						
						$allusers[$playermode][$tourneyuser['memberid']] = array('tmpid' => $curtmpid);
						$tmpplayer[] = array(
								'id'=>$tourneyuser['memberid'], 
								'tmpid'=>$curtmpid,
								'email'=>$info['email'],
								'name'=>$info['name']);
					}
				}
			} else {
				$tourneygroups = $db->selectList(MYSQL_TABLE_PREFIX."tournamentgroups", "*", "tournamentid=".$tournamentid);
				foreach($tourneygroups as $tourneyuser) {
					if(!array_key_exists($tourneyuser['groupid'], $allusers[$playermode])) {
						$clanemail = $db->selectOneRow(MYSQL_TABLE_PREFIX."users", "*", "userid=".$tourneyuser['founderid']);
						$setplayers[$playermode][] = $tourneyuser['groupid'];
						$allusers[$playermode][$tourneyuser['groupid']] = array('tmpid' => $playermode.'T'.$tmpid);
						$tmpplayer[] = array(
								'id'=>$tourneyuser['groupid'], 
								'tmpid'=>$playermode.'T'.$tmpid++,
								'email'=>$clanemail['email'],		
								'name'=>$tourneyuser['name']);
					}
				}
			}
		}

		foreach($tournamentids as $tourneyid){
			$dummy=$db->selectOneRow(MYSQL_TABLE_PREFIX."tournamentlist", "*", "tournamentid=".$tourneyid);
			$encounters=$db->selectList(MYSQL_TABLE_PREFIX."tournamentencounters", "roundid, player1id, player2id, points1, points2, encounterid", "tournamentid=".$tourneyid, "roundid ASC");

			$enc=array();
			
			$playermode=($dummy['playerperteam']>1)?"C":"P";
			switch ($dummy['mode']) { 
			
				case 1: // Single Elimination
					$dummy['mode']='S';
					$lastRound = $db->selectOneRow(MYSQL_TABLE_PREFIX."tournamentencounters", "roundid, encounterid", "tournamentid=".$tourneyid, "roundid DESC");

					foreach($encounters as $encounter) {
						if($lastRound['roundid'] == $encounter['roundid'] && $encounter['encounterid'] == 1) {
						
						} else {
							$winner=($encounter['player2id']<0 OR ($encounter['points1']>$encounter['points2']))?1:2;
							$winner_id=(($winner==1)?$encounter['player1id']:$encounter['player2id']);
							$winner_tmpid=($winner_id>0)?$allusers[$playermode][$winner_id]:'F';
							$looser_id=(($winner==1)?$encounter['player2id']:$encounter['player1id']);
							$looser_tmpid=($looser_id>0)?$allusers[$playermode][$looser_id]:'F';
												
							$enc['winner'.$encounter['roundid']][]=array('winner'=>$winner_tmpid,
															'looser'=>$looser_tmpid);
						}
					}
					// Adjust maxplayers
					$finalEnc = $db->selectOneRow('tournamentencounters',
						'*',
						"`tournamentid`=".$tourneyid,
						"`roundid` DESC ");
					$dummy['playerlimit']  = pow(2,($finalEnc['roundid']+1));
					
					break;
				case 2: // Double Elimination
					$dummy['mode']='D';
					$offset = $db->num_rows("tournamentencounters", "`tournamentid`=".$tourneyid." AND `roundid`=1");	

					foreach($encounters as $encounter) {
						if(!($encounter['player1id']<=0 && $encounter['player2id']<=0)) {
							$winner=($encounter['player2id']<0 OR ($encounter['points1']>$encounter['points2']))?1:2;
							$winner_id=(($winner==1)?$encounter['player1id']:$encounter['player2id']);
							$winner_tmpid=($winner_id>0)?$allusers[$playermode][$winner_id]:'F';
							$looser_id=(($winner==1)?$encounter['player2id']:$encounter['player1id']);
							$looser_tmpid=($looser_id>0)?$allusers[$playermode][$looser_id]:'F';
							
							if($encounter['encounterid']>=$offset) {
								$roundNr = $encounter['roundid'] - 1;
							} else {
								$roundNr = ($encounter['roundid']>1)?ceil($encounter['roundid'] / 2):0;
							}
							
							$bracket=($encounter['encounterid']>=$offset)?'looser':'winner';
							
							$enc[$bracket.$roundNr][]=array('winner'=>$winner_tmpid,
															'looser'=>$looser_tmpid);
						}
					}
					// Adjust maxplayers
					$finalEnc = $db->selectOneRow('tournamentencounters',
						'*',
						"`tournamentid`=".$tourneyid,
						"`roundid` DESC ");
					$dummy['playerlimit']  = pow(2,(int) ($finalEnc['roundid']/2));
			
					break;
					
				// Groups 
				// Points
				// Randomize
				// King of the Hill				
				default: 
					$tournament_obj = getTournamentObject($dummy);
					$ranking = $tournament_obj->ranking();
					$dummy['mode']='M';
					foreach($ranking as $players) {
						foreach($players['participants'] as $player){
							$enc[]=array('rank'=>$players['rank'],
										 'user'=>$allusers[$playermode][$player['userid']]);
						}
					}
					break;
			}
			$dummy['enc'] = $enc;
						
			$tourneys[]=$dummy;
		}
		$e='<wwcl>'.$br;
			$e.=spaceTabs(1).'<submit>'.$br;
				$e.=spaceTabs(2).'<tool>'.$br.spaceTabs(3).'LAN Party Manager IV'.$br.spaceTabs(2).'</tool>'.$br;
				$e.=spaceTabs(2).'<timestamp>'.$br.spaceTabs(3).time().$br.spaceTabs(2).'</timestamp>'.$br;
				$e.=spaceTabs(2).'<party_name>'.$br.spaceTabs(3).$eventinformation['eventname'].$br.spaceTabs(2).'</party_name>'.$br;
				$e.=spaceTabs(2).'<pid>'.$br.spaceTabs(3).$eventinformation['partyid'].$br.spaceTabs(2).'</pid>'.$br;
				$e.=spaceTabs(2).'<pvdid>'.$br.spaceTabs(3).$eventinformation['organizerid'].$br.spaceTabs(2).'</pvdid>'.$br;
				$e.=spaceTabs(2).'<stadt>'.$br.spaceTabs(3).$eventinformation['partycity'].$br.spaceTabs(2).'</stadt>'.$br;
			$e.=spaceTabs(1).'</submit>'.$br;
			$e.=spaceTabs(1).'<tmpplayer>'.$br;
				foreach($tmpplayer as $user) {
					$e.=spaceTabs(2).'<data>'.$br;
					$e.=spaceTabs(3).'<tmpid>'.$br.spaceTabs(4).$user['tmpid'].$br.spaceTabs(3).'</tmpid>'.$br;
					$e.=spaceTabs(3).'<name>'.$br.spaceTabs(4).$user['name'].$br.spaceTabs(3).'</name>'.$br;
					$e.=spaceTabs(3).'<email>'.$br.spaceTabs(4).$user['email'].$br.spaceTabs(3).'</email>'.$br;;
					$e.=spaceTabs(2).'</data>'.$br;
				}	
			$e.=spaceTabs(1).'</tmpplayer>'.$br;
			foreach($tourneys as $tourney) {
				$e.=spaceTabs(1).'<tourny>'.$br;
					$e.=spaceTabs(2).'<name>'.$br.spaceTabs(3).wwclGameIdToString($tourney['wwclgameid']).$br.spaceTabs(2).'</name>'.$br;
					$e.=spaceTabs(2).'<gid>'.$br.spaceTabs(3).$tourney['wwclgameid'].$br.spaceTabs(2).'</gid>'.$br;
					$e.=spaceTabs(2).'<maxplayer>'.$br.spaceTabs(3).$tourney['playerlimit'].$br.spaceTabs(2).'</maxplayer>'.$br;
					$e.=spaceTabs(2).'<mode>'.$br.spaceTabs(3).$tourney['mode'].$br.spaceTabs(2).'</mode>'.$br;
					
					switch($tourney['mode']) {
						case 'S':
						case 'D':
							foreach($tourney['enc'] as $roundname => $round) {
								$e.=spaceTabs(2).'<'.$roundname.'>'.$br;
								foreach($round as $encounter) {
									$e.=spaceTabs(3).'<match>'.$br;
									$e.=spaceTabs(4).'<win>'.$br.spaceTabs(5).$encounter['winner']['tmpid'].$br.spaceTabs(4).'</win>'.$br;
									$e.=spaceTabs(4).'<loose>'.$br.spaceTabs(5).$encounter['looser']['tmpid'].$br.spaceTabs(4).'</loose>'.$br;					
									$e.=spaceTabs(3).'</match>'.$br;
								}
								$e.=spaceTabs(2).'</'.$roundname.'>'.$br;
							}
							break;
						case 'M':
							foreach($tourney['enc'] as $data) {
								$e.=spaceTabs(2).'<data>'.$br;
									$e.=spaceTabs(3).'<rank>'.$br.spaceTabs(4).$data['rank'].$br.spaceTabs(3).'</rank>'.$br;
									$e.=spaceTabs(3).'<id>'.$br.spaceTabs(4).$data['user']['tmpid'].$br.spaceTabs(3).'</id>'.$br;
								$e.=spaceTabs(2).'</data>'.$br;
							}
							break;
					}
				$e.=spaceTabs(1).'</tourny>'.$br;
			}
		$e.='</wwcl>';
		return $e;
	}
	
	function getTournamentObject($tournament) {
		require_once("participant.interface.php");
		require_once("singlePlayer.class.php");
		require_once("group.class.php");
		require_once("register.class.php");
		$register = new Register();
		require_once("tournament.abstract.class.php");
		
		switch ($tournament['mode']) { 
			case 1: //Single Elimination
				require_once("tournament.singleelimination.class.php");
				$tournament_obj = new SingleElimination($tournament['tournamentid'], $register->getParticipants($tournament));
				break;
			case 2: // Double Elimination
				require_once("tournament.doubleelimination.class.php");
				$tournament_obj = new DoubleElimination($tournament['tournamentid'], $register->getParticipants($tournament));
				break;
			case 3: // Groups
				require_once("tournament.groups.class.php");
				$tournament_obj = new Groups($tournament['tournamentid'], $register->getParticipants($tournament));
				break;
			case 4: // Points
				require_once("tournament.points.class.php");
				$tournament_obj = new Points($tournament['tournamentid'], $register->getParticipants($tournament));
				break;
			case 5: // Randomize
				require_once("tournament.randomize.class.php");
				$tournament_obj = new Randomize($tournament['tournamentid'], $register->getParticipants($tournament));
				break;
			case 6: // King of the Hill
				require_once("tournament.koth.class.php");
				$tournament_obj = new KotH($tournament['tournamentid'], $register->getParticipants($tournament));
				break;
			case 7: // KDeathmatch
				require_once("tournament.deathmatch.class.php");
				$tournament_obj = new Deathmatch($tournament['tournamentid'], $register->getParticipants($tournament));
				break;
				
			default: 
				break;
		}
		return $tournament_obj;
	}
	
	function getTournaments($userid, $limit='') {
		global $login;
		global $db;
		
		if($limit<=1)
			$limit='';
		
		// Get my Single tournaments
		$mySingleTournaments = $db->selectList("tournamentregister",
										"*", 
										"`memberid`=".$userid);	
		// Get my Clan tournaments
		$myClanTournaments = $db->selectList("tournamentgroupregister",
										"*", 
										"`memberid`=".$userid);	
		
		// Put all tournaments in one array
		$allTourneyIds = array();
		foreach($mySingleTournaments as $singleTourney) {
			$allTourneyIds[] = $singleTourney['tournamentid'];	
		}
		foreach($myClanTournaments as $clanTourney) {
			$allTourneyIds[] = $clanTourney['tournamentid'];	
		}
		
		$allTourneyIds = "('".implode("', '", $allTourneyIds)."')";
		
		// Select the newest n (limit) tournaments
		$allTourneys = $db->selectList("tournamentlist",
										"*", 
										"`tournamentid` IN ".$allTourneyIds,
										"CASE `state`   WHEN 2 THEN 0
														WHEN 1 THEN 1 
														WHEN 3 THEN 2 
														WHEN 0 THEN 3
														END, 
										`start` DESC ",
										$limit
										);	

		return $allTourneys;
	}
	
	function getNextEncounter($tournament, $userid) {
		global $db;
		global $user;
		global $lang;
		
		require_once("./mod/default/tournament/participant.interface.php");
		require_once("./mod/default/tournament/singlePlayer.class.php");
		
		switch($tournament['mode']) {
			
			// Group
			case 3:
				// Clan tournament -> user groupid as userid
				if($tournament['playerperteam']>1) {
					$userid = $db->selectOneRow("tournamentgroupregister", 
										"*", 
										"`tournamentid` = ".$tournament['tournamentid']." AND `memberid`=".$userid);		
					$userid = $userid['groupid'];
				}
			
				$groupEnc = $db->selectOneRow("tournamentgroupenc", 
										"*", 
										"`tournamentid` = ".$tournament['tournamentid']." AND `participantid`=".$userid,
										"`round` DESC");			

				// Encounter is not yet finished
				if($groupEnc['rank']==0 && 0 == $db->num_rows("tournamentgroupenc", "`tournamentid` = ".$tournament['tournamentid']." AND `round`=".$groupEnc['round']." AND `group`=".$groupEnc['group']." AND `rank`>0")) {					
					$encounter = array('roundid'=>($groupEnc['round']),
										'state'=>0);
										
					$encounterUrl = makeHTMLUrl($lang->get('group')." ".chr(65+$groupEnc['group']), makeURL('tournament', array('mode'=>'table', 'tournamentid'=>$tournament['tournamentid'], 'action'=>'submit', 'group'=>$groupEnc['group'], 'round'=>$groupEnc['round'])));
				} else {
					$encSettings = $db->selectOneRow("tournamentsettings", 
										"*", 
										"`tournamentid` = ".$tournament['tournamentid']." AND `settingid`= 1");	
					
					$groupList = $db->selectList("tournamentgroupenc", 
									"*", 
									"`tournamentid` = ".$tournament['tournamentid']." AND `round`=".$groupEnc['round']." AND `group`=".$groupEnc['group'],
									"`rank` ASC",
									$encSettings['value']);
					
					// Check if the user is kickedout or just not yet forwarded
					$kickedout = true;
					$encounter['finished'] = false;
					
					foreach($groupList as $player) {
						if($player['participantid'] == $userid) {
							$kickedout = false;
							$encounter['finished'] = true;
							break;
						}
					}	
				}

				break;
		
			// Points
			case 4:
			// Randomize
			case 5:
				// Do nothing, as the tournament has no "rounds"
				$encounter = '';
				break;
		
			// Double Elimination
			// Single Elimination
			// King of the Hill
			default:
				// Clan tournament
				if($tournament['playerperteam']>1) {
					$group = $db->selectOneRow("tournamentgroupregister", 
										"*", 
										"`tournamentid` = ".$tournament['tournamentid']." AND `memberid`=".$userid);	
										
					$encounter = $db->selectOneRow("tournamentencounters", 
										"*", 
										"`tournamentid` = ".$tournament['tournamentid']." AND (`player1id`=".$group['groupid']." OR `player2id`=".$group['groupid'].")",
										"`roundid` DESC");	
					
					// If the game is lost don't show next game
					if(($encounter['player1id'] == $group['groupid'] && $encounter['points1'] < $encounter['points2']) 
					   || 
					   ($encounter['player2id'] == $group['groupid'] && $encounter['points2'] < $encounter['points1'])) {
						$kickedout = true;
					} else {		
						$player1 = makePlayerUrl($encounter['player1id'], false);
						$player2 = makePlayerUrl($encounter['player2id'], false);
						
						// If the encounter has two players, the "vs." becomes a link
						if($encounter['player1id'] > 0 && $encounter['player2id'] > 0) {
							$versus = makeHTMLUrl("vs.", makeURL('tournament', array('mode'=>'view', 
																	'tournamentid'=>$tournament['tournamentid'], 
																	'encid'=>$encounter['encounterid'],
																	'roundid'=>$encounter['roundid'])));	
						}
					}
					
				// Single tournament
				} else {
					$encounter = $db->selectOneRow("tournamentencounters", 
										"*", 
										"`tournamentid` = ".$tournament['tournamentid']." AND (`player1id`=".$userid." OR `player2id`=".$userid.")",
										"`roundid` DESC");			
					// If the game is lost don't show next game
					if(($encounter['player1id'] == $userid && $encounter['points1'] < $encounter['points2']) 
					   || 
					   ($encounter['player2id'] == $userid && $encounter['points2'] < $encounter['points1'])) {
						$kickedout = true;
					} else {									
	
						$player1 = makePlayerUrl($encounter['player1id']);
						$player2 = makePlayerUrl($encounter['player2id']);
						
						if($encounter['player1id'] > 0 && $encounter['player2id'] > 0) {
							$versus = makeHTMLUrl("vs.", makeURL('tournament', array('mode'=>'view', 
																	'tournamentid'=>$tournament['tournamentid'], 
																	'encid'=>$encounter['encounterid'],
																	'roundid'=>$encounter['roundid'])));	
						}
					}
				}
				if(isset($player1)) {
					$encounterUrl = $player1." ".(!isset($versus)?"vs.":$versus)." ".$player2;
				}
				break;
		}
			
		// If there is no next enc return nothing
		if(!$encounter) {
			$encounter='';
		} else {
			// If the user was defeated don't show next enc (as there is none)
			if(isset($kickedout) && $kickedout) {
				$encounter['kickedout'] = true;
			} else {
				// If the game is finished don't show next enc (as there is none)
				if(@$encounter['state']==1 || @$encounter['finished']) {
					$encounter['finished'] = true;
				} else {
					$maps = explode(";", $tournament['finalmappool']);
					$realround = ($tournament['mode'] == 2)?$encounter['roundid']-1:$encounter['roundid'];
					$encounter['mapname'] = (!isset($maps[$realround]))?'':$maps[$realround];
					$encounter['encounterUrl'] = $encounterUrl;
					
					if(isset($encounter['duration']) && $encounter['duration']!=0) {
						require_once("mod/default/tournament/tournament.eliminationencounter.class.php");
						$curEnc = new EliminationEncounter($encounter['tournamentid'], $encounter['roundid'], $encounter['encounterid']);
						$encounter['startTime'] = date("H:i", $encounter['start']);
						$encounter['startTimeLeft'] = timeLeft($encounter['start']);
						$encounter['endTime'] = date("H:i", $encounter['start']+$encounter['duration']);
						$encounter['timeState'] = $curEnc->getEncTimeState();
					}
					
					//Adjust DE rounds
					$encounter['roundid'] = makeRoundNr($encounter['roundid'], $tournament['mode']);
				}
			}
		}
		return $encounter;
	}
	
	function makeTournamentList($tournaments, $userid) {
		if(count($tournaments)<1) {
			return false;
		}

		$finalTournaments = array ();
		foreach($tournaments as $tournament) {
			$nextEnc='';
			$ranking='';
			switch ($tournament['state']) {
				// Inactive
				case 0:
					$url=$tournament['title'];
					break;
					
				// Joining
				case 1:
					$url = makeHTMLUrl($tournament['title'], makeUrl('tournament', array('tournamentid'=>$tournament['tournamentid'])));
					break;
					
				// Running 
				case 2:
					$url = makeHTMLUrl($tournament['title'], makeUrl('tournament', array('tournamentid'=>$tournament['tournamentid'], 'mode'=>'table')));
					$nextEnc = getNextEncounter($tournament, $userid);
					break;
					
				// Finished
				case 3:
					$url = makeHTMLUrl($tournament['title'], makeUrl('tournament', array('tournamentid'=>$tournament['tournamentid'], 'mode'=>'table')));
					$tournament_obj = getTournamentObject($tournament);
					$ranking = $tournament_obj->ranking();
					break;
			}
			
			$finalTournaments[] = array('url'=>$url,
							 'listcolor'=>makeListColor($tournament['state']),
							 'nextencounter'=>$nextEnc,
							 'state'=>$tournament['state'],
							 'statename'=>tournamentStateToString($tournament['state']),
							 'ranking'=>$ranking,
							 'tournamentid'=>$tournament['tournamentid'],
							 'title'=>$tournament['title']
							 );
	 
		}
		return $finalTournaments;
	}
	
	function makePlayerUrl($playerid, $single=true) {
		global $db;
		global $user;
		require_once("./mod/default/tournament/participant.interface.php");
		require_once("./mod/default/tournament/singlePlayer.class.php");
	
		if($single) {
			if($playerid <= 0){
				$p = newUnknown();
				$playerUrl = $p->getName();
			} else {
				$p = new singlePlayer($user->getUserById($playerid));	
				$playerUrl = makeHTMLUrl($p->getName(), $p->getUrl());
			}
		} else {
			if($playerid <= 0){
				$p = newUnknown();
				$playerUrl = $p->getName();
			} else {
				$p = $db->selectOneRow("tournamentgroups", "*", "`groupid`=".$playerid);	
				$playerUrl = makeHTMLUrl($p['name'], makeURL('tournament', array('mode'=>'viewgroup', 'tournamentid'=>$p['tournamentid'], 'groupid'=>$playerid)));
			}		
		}
		return $playerUrl;
	}
	
	function getAllNextEncounters() {
		global $db;
		global $lang;
		
		$tournaments = $db->selectList("tournamentlist", "*", "`state`=2");
		$allNextEncs=array();
		
		foreach($tournaments as $tourney) {
			switch ($tourney['mode']) {
				// Single Elimination
				case 1:
				// Double Elimination
				case 2:
				// KotH
				case 6:
					$allEnc = $db->selectList("tournamentencounters", "*", "`tournamentid`=".$tourney['tournamentid']." AND `state`=0 AND (`player1id`>0 OR `player2id`>0)");
					require_once("mod/default/tournament/tournament.eliminationencounter.class.php");
					foreach($allEnc as $enc) {
						$curEnc = new EliminationEncounter($enc['tournamentid'], $enc['roundid'], $enc['encounterid']);
						
						$allNextEncs[] = array('time'=>$curEnc->getEncTimeState(),
											'round'=>makeRoundNr($curEnc->getRoundId(), $tourney['mode']),
											'encounterid'=>$curEnc->getId(),
											'player1id'=>makePlayerUrl($curEnc->getPlayer1Id(), $tourney['playerperteam']==1),
											'player2id'=>makePlayerUrl($curEnc->getPlayer2Id(), $tourney['playerperteam']==1),
											'title'=>$tourney['title']
											);
					}
					break;
								
				// Groups
				case 3:
					$allEnc = $db->selectList("tournamentgroupenc", "*", "`tournamentid`=".$tourney['tournamentid']." GROUP BY `round`, `group` HAVING MIN(rank)=0");
					foreach($allEnc as $enc) {
						$participants = $db->selectList("tournamentgroupenc", "*", "`tournamentid`=".$enc['tournamentid']." AND `round`=".$enc['round']." AND `group`=".$enc['group']); 
						$allParticipants = array();
						foreach($participants as $participant){
							$allParticipants[] = makePlayerUrl($participant['participantid']);
						}
						$encounterUrl = makeHTMLUrl($lang->get('group')." ".chr(65+$enc['group']), makeURL('tournament', array('mode'=>'table', 'tournamentid'=>$enc['tournamentid'], 'action'=>'submit', 'group'=>$enc['group'], 'round'=>$enc['round'])));
						$allNextEncs[] = array('time'=>'',
												'round'=>makeRoundNr($enc['round']-1, $tourney['mode']),
												'encounterid'=>$enc['group'],
												'player1id'=>$encounterUrl,
												'player2id'=>'',
												'title'=>$tourney['title'],
												'participants'=>$allParticipants);
					}
					break;
					
				// Points
				case 4:
					break;
					
				// Randomize
				case 5:
					break;
			}	
		}
		usort($allNextEncs, "sortNextEncounters");
		return $allNextEncs;
	}
	
	function sortNextEncounters($a, $b) {
		if(isset($a['time']['start']) && isset($b['time']['start'])) {
			if($a['time']['start'] == $b['time']['start']) {
				return ($a['time']['mode'] < $b['time']['mode']) ? -1 : 1;
			}
			return ($a['time']['start'] < $b['time']['start']) ? -1 : 1;
		} else {
			return $a['title'] < $b['title'] ? -1 : 1;
		}
	}
	
	function makeListColor($state) {
		$statesandcolors = array(0=>'#000000',
								1=>'#ffff00',
								2=>'#00ff00',
								3=>'#ff0000');
		return $statesandcolors[$state];
	}
	
	function makeRoundNr($roundid, $mode=0) {
		if($mode != 2) {
			return $roundid+1;
		} else {
			if($roundid <= 1) {
				return $roundid;
			} else {
				return $roundid/2+1;
			}
		}
	}
?>