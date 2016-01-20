<?php
	
	$mod = "admin";
	
	$lang->addModSpecificLocalization($mod);
	$smarty->assign('lang', 
		array('options_deposit' => $lang->get('options_deposit'),
				'options_edit' => $lang->get('options_edit'),
				'options_memberships' => $lang->get('options_memberships'),
				'options_delete' => $lang->get('options_delete'),
				'nickname' => $lang->get('nickname'),
				'email' => $lang->get('email'),
				'lastname' => $lang->get('lastname'),
				'prename' => $lang->get('prename'),
				'name' => $lang->get('name'),
				'options' => $lang->get('options')));
	
	if (strlen($_GET['search']) >= 1)
	{
		$userlist = $db->selectList(MYSQL_TABLE_PREFIX . 'users', "*", 
			"INSTR(`nickname`, '" . secureMySQL($_GET['search']) . "') > 0
			 OR INSTR(`prename`, '" . secureMySQL($_GET['search']) . "') > 0
			 OR INSTR(`lastname`, '" . secureMySQL($_GET['search']) . "') > 0
			 OR INSTR(`email`, '" . secureMySQL($_GET['search']) . "') > 0", "`nickname` ASC", "10");
	}
	else
	{
		$userlist = array();
	}
	
	foreach ($userlist as $key => $val)
	{
		$userlist[$key]['url_show_profile'] = makeURL('profile', array('userid' => $val['userid']));
		$userlist[$key]['url_edit'] = makeURL($mod, array('mode' => 'users', 'action' => 'edit', 'userid' => $val['userid']));
		$userlist[$key]['url_delete'] = makeURL($mod, array('mode' => 'users', 'action' => 'delete', 'userid' => $val['userid']));
		$userlist[$key]['url_memberships'] = makeURL($mod, array('mode' => 'users', 'action' => 'memberships', 'userid' => $val['userid']));
		$userlist[$key]['url_deposit'] = makeURL($mod, array('mode' => 'users', 'action' => 'deposit', 'userid' => $val['userid']));
	}
	
	$smarty->assign('users', $userlist);
	$smarty->display('../mod/default/admin/userlist.tpl');
?>