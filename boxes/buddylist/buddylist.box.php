<?php
	
	$lang->addModSpecificLocalization('buddylist');
	
	if ($login->currentUser() !== false) {
	
		$dur = (int)$config->get('core', 'stat-duration');
	
		$tbl_b = MYSQL_TABLE_PREFIX.'buddylist';
		$tbl_u = MYSQL_TABLE_PREFIX.'users';
		
		// list existing buddies
		$buddies = $db->selectList($tbl_b . "`, `" . $tbl_u, "*", 
					"((
						`".$tbl_b."`.`userid`=".$login->currentUserID() . " 
						AND `".$tbl_b."`.`buddyid` = `".$tbl_u."`.`userid`
					)
					OR (
						`".$tbl_b."`.`buddyid`=".$login->currentUserID() . " 
						AND `".$tbl_b."`.`userid` = `".$tbl_u."`.`userid`
					)
					) AND `".$tbl_b."`.`accepted`=1");
		
		if (null != $buddies && count($buddies) > 0)
			foreach ($buddies as $index => $buddy) {
				if ($buddy['lastaction'] + $dur > time())
					$buddies[$index]['online'] = true;
				else
					$buddies[$index]['online'] = false;
				$buddies[$index]['url'] = makeURL('profile', array('userid' => $buddy['userid']));
			}
		
		$smarty->assign('box_buddies', $buddies);
		
		$tpl_file = $template_dir."/default.tpl";
		
	} else {
		$tpl_file = $template_dir."/login.tpl";
	}
	
	$visible = true;
	
?>