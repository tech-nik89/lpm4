<?php
	$tbl = MYSQL_TABLE_PREFIX . 'teamspeak';
	$isallowed = $rights->isallowed($mod, 'manage');
	$mode = $_GET['mode'];
	$tsimagepath = $mod_dir ."images/";

	$lang->addModSpecificLocalization($mod);
	$breadcrumbs->addElement($lang->get('ts'), makeURL($mod));
	if ($isallowed)
	{
		$menu->addSubElement($mod, $lang->get('teamspeak_manage'), 'manage');
	}

	include($mod_dir . "teamspeak.class.php");

	switch ($mode) {
		case 'manage':
			
			if ($isallowed)
			{
				// add a breadcrumb
				$breadcrumbs->addElement($lang->get('teamspeak_manage'), makeURL($mod, array('mode' => 'manage')));
				
				// include the template
				$smarty->assign('path', $template_dir . '/manage.tpl');
				
				// save procedure
				if (isset($_POST['save']))
				{
					// get the menu
					$c = $db->selectList($tbl, "*", "1", "`order`");
					
					// walk through the menu and update
					if (!empty($c)) {
					foreach ($c as $element)
					{
						
						// delete?
						if (@$_POST['delete_' . $element['ID']] == "1")
							#$menu->removeElement($element['menuid']);
							$db->delete($tbl, "ID = ".$element['ID']);
						else
						{
							#$menu->editElement($element['menuid'], 
									#$_POST['title_' . $element['menuid']],
									#$_POST['mod_' . $element['menuid']],
									#$_POST['requires_login_' . $element['menuid']]);
									
							#$menu->setElementOrder($element['menuid'], $_POST['order_' . $element['menuid']]);
							$db->update($tbl, "`order` = ".$_POST['order_' . $element['ID']].
									", `address` = '".$_POST['address_' . $element['ID']]."'".
									", `tcp` = ".$_POST['tcp_' . $element['ID']].
									", `udp` = ".$_POST['udp_' . $element['ID']].
									", `pw` = '".$_POST['pw_' . $element['ID']]."'", "ID = ".$element['ID']);
											
							
						}
					} }
					
					// add new element
					if ($_POST['address_new'] != '' && $_POST['udp_new'] != '') {
						#$menu->addElement($_POST['title_new'], $_POST['mod_new'], (int)$_POST['requires_login_new']);
						$max = $db->num_rows($tbl, "1");
						$order = $max+1;
						$tsfields = array( "ID",
								"order",
								"address",
								"tcp",
								"udp",
								"pw");
						$tsvalues = array ('', $order, "'".$_POST['address_new']."'", $_POST['tcp_new'], $_POST['udp_new'], "'".$_POST['pw_new']."'"); 
						if (empty($tsvalues[3])) $tsvalues[3] = '51234';
						foreach ($tsvalues as $i => $v) {
							if ($v=="") 
								$tsvalues[$i] = "NULL" ;} 
						$db->insert($tbl, $tsfields, $tsvalues);}
								
				}
				
				// get a list of available modules
				#$modlist = $this->listInstalled(); 
				#$smarty->assign('mlist', $modlist);
				
				// get menu
				$m = $db->selectList($tbl, "*", "1", "`order`");
				$smarty->assign('m', $m);
			}
			break;
		default:
			
			$server = $db->selectList($tbl, "*", "1", "`order`");
			foreach ($server as $i => $s) {
				$tss2info = new tss2info($i);
				$tss2info->serverAddress = $s['address'];
				$tss2info->serverQueryPort = $s['tcp'];
				$tss2info->serverUDPPort = $s['udp'];
				$tss2info->serverPasswort = $s['pw'];
				$server[$i] = $tss2info->getInfo();
				$server[$i]['id'] = $i;

				
				
			}
			if ($config->get('core', 'debug') == 1) {
				ob_start(); print_r($server); $serveroutput = ob_get_contents(); ob_end_clean();
				$serveroutput = "<pre>".$serveroutput."</pre>";
				$debug->add('TS2Server content', $serveroutput);
			}
			$cUser = $login->currentUser();
			if ($cUser) {$loggedin = true;} else {$loggedin=false;}
			$smarty->assign('loggedin', $loggedin);
			$smarty->assign('servericon', $tsimagepath."teamspeak.gif");
			$smarty->assign('tsimagepath', $tsimagepath);
			$smarty->assign('tsimageext', ".gif");
			$smarty->assign('gridpath', $tsimagepath."grid.gif");
			$smarty->assign('grid2icon', $tsimagepath."grid2.gif");
			$smarty->assign('channelicon', $tsimagepath."channel.gif");
			$smarty->assign('server', $server);
			$smarty->assign('path', $template_dir . "/teamspeak.tpl");	
	}

?>
