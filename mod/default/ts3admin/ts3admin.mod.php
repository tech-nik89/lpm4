<?php
	
	//init
	require_once("libraries/ts3init.php");
	
	$tsimagepath = $mod_dir ."images/";

	$manage_rights_allowed = $rights->isallowed($mod, 'manage_rights');
	$manage_servers_allowed = $rights->isallowed($mod, 'manage_servers');
	
	$breadcrumbs->addElement($lang->get('ts3'), makeURL($mod));
	if ($manage_servers_allowed)
	{
		$menu->addSubElement($mod, $lang->get('ts3admin_manage_servers'), 'manage_servers');
	}
	if ($manage_rights_allowed)
	{
		$menu->addSubElement($mod, $lang->get('ts3admin_manage_rights'), 'manage_rights');
	}
	
	
	$mode = $_GET['mode'];
	
	$action="";
	if(isset($_GET["action"]))
		$action = $_GET["action"];

	switch ($mode) {
		case 'manage_servers':
			if(!$manage_servers_allowed){
				$smarty->assign('path', $template_dir . "/notallowed.tpl");
			} else { 
				// add a breadcrumb
				$breadcrumbs->addElement($lang->get('ts3admin_manage_servers'), makeURL($mod, array('mode' => 'manage_servers')));
				
				switch($action) {
					case 'create':
						
						// save procedure
						if (isset($_POST['save']))
						{
							$name = secureMySQL($_POST["name"]);
							$address = secureMySQL($_POST["address"]);
							$query = intval(secureMySQL($_POST["query"]));
							$user = secureMySQL($_POST["user"]);
							$passwd = secureMySQL($_POST["passwd"]);
							
							if (empty($name) or empty($address) or empty($query) or empty($user) or empty($passwd)) {
								$notify->add($lang->get('newts3'), $lang->get('newts3_failed'));
							}else {
								$db->insert($tbl . "_servers", array("name", "address", "query", "usr", "pw"), array("'".$name."'", "'".$address."'", $query, "'".$user."'", "'".$passwd."'"));
								$notify->add($lang->get('newts3'), $lang->get('newts3_successfully'));
							}
							
							$smarty->assign('servername', $name);
							$smarty->assign('serveraddresse', $address);
							$smarty->assign('serverqueryport', $query);
							$smarty->assign('serveradminname', $user);
							$smarty->assign('serveradminpasswd', "******");
						} else {
							$smarty->assign('servername', "");
							$smarty->assign('serveraddresse', "");
							$smarty->assign('serverqueryport', "10011");
							$smarty->assign('serveradminname', "serveradmin");
							$smarty->assign('serveradminpasswd', "******");
						}
						
						$smarty->assign('serveralias', $lang->get('newts3'));
						$smarty->assign('path', $template_dir . "/editserver.tpl");
						break;
					case 'edit':
						if (isset($_GET["id"]))
							$smarty->assign('serverid', $_GET["id"]);
						else
							$smarty->assign('serverid', $_POST["id"]);
						
						// save procedure
						if (isset($_POST['save']))
						{
							$name = secureMySQL($_POST["name"]);
							$address = secureMySQL($_POST["address"]);
							$query = intval(secureMySQL($_POST["query"]));
							$user = secureMySQL($_POST["user"]);
							$passwd = secureMySQL($_POST["passwd"]);
							
							if (empty($name) or empty($address) or empty($query) or empty($user) or empty($passwd)) {
								$notify->add($lang->get('newts3'), $lang->get('newts3_change_failed'));
							}else {
								if ($passwd=="******") {
									$db->update($tbl . "_servers", "name='".$name."', address='".$address."', query=".$query.", usr='".$user."'", "ID=".$_POST["id"]);
								}else{
									$db->update($tbl . "_servers", "name='".$name."', address='".$address."', query=".$query.", usr='".$user."', pw='".$passwd."'", "ID=".$_POST["id"]);
								}
								$notify->add($lang->get('ts3'), $lang->get('newts3_change_successfully'));
							}
							
							$smarty->assign('servername', $name);
							$smarty->assign('serveraddresse', $address);
							$smarty->assign('serverqueryport', $query);
							$smarty->assign('serveradminname', $user);
							$smarty->assign('serveradminpasswd', "******");
							
							$smarty->assign('serveralias', $name." - ".$address);
						} else {
							$server = $db->selectOneRow($tbl . "_servers","name,address,query,usr","ID=".$_GET["id"]);
							
							$smarty->assign('servername', $server["name"]);
							$smarty->assign('serveraddresse', $server["address"]);
							$smarty->assign('serverqueryport', $server["query"]);
							$smarty->assign('serveradminname', $server["usr"]);
							$smarty->assign('serveradminpasswd', "******");
							
							$smarty->assign('serveralias', $server["name"]." - ".$server["address"]);
						}
						
						$smarty->assign('path', $template_dir . "/editserver.tpl");
						
						break;
					case 'delete':
						$db->query("DELETE FROM `".$tbl."_servers` WHERE `".$tbl."_servers`.`ID` = ".$_GET["id"]);
					default:
						$smarty->assign('newts3_linkurl', makeURL($mod, array('mode' => 'manage_servers', 'action' => 'create')));
						
						$server = $db->selectList($tbl . "_servers", "ID,name,address","true","ID");
						for($i=0; $i<count($server); $i++) {
							$server[$i]["editlink"]=makeURL($mod, array('mode' => 'manage_servers', 'action' => 'edit', 'id' => $server[$i]["ID"]));
							$server[$i]["deletelink"]=makeURL($mod, array('mode' => 'manage_servers', 'action' => 'delete', 'id' => $server[$i]["ID"]));
						}
						
						if (count($server)==0) 
							$smarty->assign('server', "none");	
						else
							$smarty->assign('server', $server);
						
						$smarty->assign('path', $template_dir . "/manageservers.tpl");
				}
			}
			break;
		case 'manage_rights':		
			if(!$manage_rights_allowed){
				$smarty->assign('path', $template_dir . "/notallowed.tpl");
			} else { 
				// add a breadcrumb
				$breadcrumbs->addElement($lang->get('ts3admin_manage_rights'), makeURL($mod, array('mode' => 'manage_rights')));
				
				$server = $db->selectList($tbl . "_servers","ID,name,address","true");
				$smarty->assign('server',$server);
				$sid = "";
				if(isset($_GET["sid"]))
					$sid=$_GET["sid"];
				if($sid=="") {
					$sid=$server[0]["ID"];	
				}
				$smarty->assign('sid', $sid);
				
				$server_data = $db->selectOneRow($tbl . "_servers","address,query,usr,pw","ID=".$sid);
				try {
					$ts3_ServerInstance = TeamSpeak3::factory("serverquery://".$server_data["usr"].":".$server_data["pw"]."@".$server_data["address"].":".$server_data["query"]."/?use_offline_as_virtual=1");
				}catch(Exception $e) {}
				
				if ($ts3_ServerInstance == NULL) {
					$notify->raiseError("",$lang->get("connection_failed"));
				} else {
					
					$servers = array();
					$serverindex = 0;
					
					foreach($ts3_ServerInstance as $ts3_VirtualServer)
					{
						try {
							$try=$ts3_VirtualServer["virtualserver_name"];
							$servers[$serverindex++] = array( "virtualserver_name" => $ts3_VirtualServer["virtualserver_name"], "id" => $ts3_VirtualServer["virtualserver_id"] );
						}catch(Exception $e) {}
					}
					$smarty->assign('vservers', $servers);
					
					$u = $db->selectList(MYSQL_TABLE_PREFIX."users","userid,nickname,0 as `set`","activated=1");
					$g = $db->selectList(MYSQL_TABLE_PREFIX."groups","groupid,name,0 as `set`","true");
					$userrightlist = array();
					$grouprightlist = array();
					$users = array();
					$groups = array();
					foreach($u as $usr) {$users[$usr["userid"]]=$usr;}
					foreach($g as $grp) {$groups[$grp["groupid"]]=$grp;}
					$vsid = -1;
					if(isset($_GET["vsid"]))
						$vsid=$_GET["vsid"];
					if((isset($_GET["osid"]) && $sid!=$_GET["osid"]) || $vsid == -1) {
						$vsid = -1;
						$fields = "`serverid`, `type`, `uid`, `r_view_server`, `r_edit_server`, `r_add_vservers`, `r_remove_vservers`";
						$userright = selectFromQuery("select ".$fields.", `nickname` from `".$tbl."_server_rights` join `".MYSQL_TABLE_PREFIX."users` on `".$tbl."_server_rights`.`uid`=`".MYSQL_TABLE_PREFIX.
										"users`.`userid` where `serverid`=".$sid." and `type`=1");
						$groupright = selectFromQuery("select ".$fields.", `name` from `".$tbl."_server_rights` join `".MYSQL_TABLE_PREFIX."groups` on `".$tbl."_server_rights`.`uid`=`".MYSQL_TABLE_PREFIX.
										"groups`.`groupid` where `serverid`=".$sid." and `type`=0");
					}else{
						$fields = "`serverid`, `type`, `uid`, `r_view_vserver`, `r_control_vserver`, `r_edit_vserver`, `r_view_grouprights`, `r_edit_grouprights`, `r_rename_group`, `r_add_group`, `r_remove_group`, ".
						"`r_view_clients`, `r_msg_client`, `r_kick_client`, `r_ban_client`, `r_change_servergroup`, `r_view_clientdetails`, `r_edit_clientdetails`, `r_view_bans`, ".
						"`r_remove_bans`, `r_view_complaints`, `r_remove_complaints`, `r_view_log`, `r_view`, `r_edit_channel`, `r_remove_channel`, `r_add_channel`, `r_move_client`, `r_change_channelgroup`";
						$userright = selectFromQuery("select ".$fields.", `nickname` from `".$tbl."_vserver_rights` join `".MYSQL_TABLE_PREFIX."users` on `".$tbl."_vserver_rights`.`uid`=`".MYSQL_TABLE_PREFIX.
										"users`.`userid` and `vserverid`=".$vsid." where `serverid`=".$sid." and `type`=1");
						$groupright = selectFromQuery("select ".$fields.", `name` from `".$tbl."_vserver_rights` join `".MYSQL_TABLE_PREFIX."groups` on `".$tbl."_vserver_rights`.`uid`=`".MYSQL_TABLE_PREFIX.
										"groups`.`groupid` and `vserverid`=".$vsid." where `serverid`=".$sid." and `type`=0");
					}
					
					foreach($userright as $ri) {
						$userrightlist[$ri["uid"]]=$ri;
						$users[$ri["uid"]]["set"]=1;
						$userrightlist[$ri["uid"]]["editurl"]  ="ajax_request.php?mod=ts3admin&file=RightOption.ajax&vsid=".$vsid."&sid=".$sid."&uid=".$ri["uid"]."&action=askedit&type=user";
						$userrightlist[$ri["uid"]]["removeurl"]="ajax_request.php?mod=ts3admin&file=RightOption.ajax&vsid=".$vsid."&sid=".$sid."&uid=".$ri["uid"]."&action=askremove&type=user";
						
					}
					foreach($groupright as $ri) {
						$grouprightlist[$ri["uid"]]=$ri;
						$groups[$ri["uid"]]["set"]=1;
						$grouprightlist[$ri["uid"]]["editurl"]  ="ajax_request.php?mod=ts3admin&file=RightOption.ajax&vsid=".$vsid."&sid=".$sid."&uid=".$ri["uid"]."&action=askedit&type=group";
						$grouprightlist[$ri["uid"]]["removeurl"]="ajax_request.php?mod=ts3admin&file=RightOption.ajax&vsid=".$vsid."&sid=".$sid."&uid=".$ri["uid"]."&action=askremove&type=group";
					}
					$smarty->assign('userrightlist', $userrightlist);
					$smarty->assign('grouprightlist', $grouprightlist);
					
					$smarty->assign('users', $users);
					$smarty->assign('groups', $groups);
					$smarty->assign('vsid', $vsid);
				}
				
				$smarty->assign('path', $template_dir . "/rights.tpl");
			}
			break;
		default:
			$server = $db->selectList($tbl . "_servers", "ID,name,address","true","ID");
			if (count($server)==0) 
				$smarty->assign('server', "none");	
			else {
				$serv = array();
				foreach($server as $s) {
					$r=getTS3Rights($s["ID"],-1);
					if($r["r_view_server"]==1)
						$serv[]=$s;
				}
				$smarty->assign('server', $serv);
			}
			$smarty->assign('path', $template_dir . "/ts3admin.tpl");	
	}
?>
