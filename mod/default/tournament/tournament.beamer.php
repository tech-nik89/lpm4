<?php
	
	switch ($_GET['view']) {
		case 'overview':
			
			$tlist = $db->selectList('tournamentlist', '*', "`state`=1 OR `state`=2", "`state` ASC");
			if (count($tlist) > 0) {
				foreach ($tlist as $i => $t) {
					$tlist[$i]['state_str'] = tournamentStateToString($t['state']);
					$tlist[$i]['color'] = makeListColor($t['state']);
				}
			}
			$smarty->assign('tournamentList', $tlist);
			$smarty->assign('path', $template_dir."/beamer/overview.tpl");
			break;
			
		case 'nextencounters':
		
			require_once("./mod/default/tournament/tournament.function.php");
			
			
			$smarty->assign('encounterList', getAllNextEncounters());
			$smarty->assign('path', $template_dir."/beamer/nextencounters.tpl");
		
			break;
	}
	
?>