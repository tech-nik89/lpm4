<?php
	
	$lang->addModSpecificLocalization('stat');
	
	$tpl_file = $template_dir."/default.tpl";
	$list = $stat->listOnline();
	if (null != $list && count($list) > 0) {
		foreach ($list as $i => $l) {
			$list[$i]['url'] = makeURL('profile', array('userid' => $l['userid']));
		}
	}
	$smarty->assign('userlist', $list);
	
	$s['user_online'] = $stat->userOnline();
	$s['visitors_today'] = $stat->visitorsToday();
	
	$smarty->assign('stat', $s);
	$smarty->assign('stat_url', makeURL('stat'));
	$visible = true;
	
?>