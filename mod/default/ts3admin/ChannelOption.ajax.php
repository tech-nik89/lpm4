<?php
	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	checkStdRightIssues();
	
	if(isset($_GET["askcreate"]) or isset($_GET["create"]) or isset($_GET["askcreatesub"]) or isset($_GET["createsub"])){
		if($ts3vserver_rights["r_add_channel"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
	}else if(isset($_GET["askdelete"]) or isset($_GET["delete"])) {
		if($ts3vserver_rights["r_remove_channel"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
	}else if(isset($_GET["askmove"]) or isset($_GET["move"]) or isset($_GET["askedit"]) or isset($_GET["edit"])) {
		if($ts3vserver_rights["r_edit_channel"]!=1) {
			$smarty->display('../mod/default/ts3admin/notallowed.tpl');
			die();
		}
	}
	
	$channels = $ts3server->channelList();
	
	if(isset($_GET["askcreate"])) {	
		$smarty->assign("create",true);
		$smarty->assign('channel_codec',2);
		$smarty->assign('channel_quality',7);
		$smarty->assign('channel_delay',0);
		$smarty->assign('channel_maxclients',-1);
		$smarty->assign('channel_maxclientsintree',-1);
		$smarty->assign('channel_unencryptvoice',true);
		$smarty->assign('channel_fpassword',false);
		$smarty->assign('channel_mcunlimited',true);
		$smarty->assign('mcinctinherit',true);
		$smarty->assign('channel_perm',true);
		$lid=0;
		$orderchannels = array();
		$orderchannels[] = array("cid"=>0, "name"=>$ts3server["virtualserver_name"]);	
		foreach($channels as $chn) {
			if($chn["pid"]==0) {
				$orderchannels[] = array("cid"=>$chn["cid"], "name"=>$chn["channel_name"]);	
				$lid=$chn["cid"];
			}
		}
		$smarty->assign('orderchannels',$orderchannels);
		$smarty->assign('channel_order',$lid);
		$smarty->display('../mod/default/ts3admin/channeldialog.tpl');
		die();
	}else if(isset($_GET["create"])) {	
		$smarty->assign("create",true);
		$cname = "Channel".rand();
		$modi = array('channel_codec'=>2, 'channel_codec_quality'=>7, 'channel_codec_latency_factor'=> 0);
		if(isset($_GET["n"]))$cname=$_GET["n"];
		if(isset($_GET["pw"]))$modi["channel_password"]=$_GET["pw"];
		if(isset($_GET["t"]))$modi["channel_topic"]=$_GET["t"];
		if(isset($_GET["d"]))$modi["channel_description"]=$_GET["d"];
		if(isset($_GET["ph"]))$modi["channel_name_phonetic"]=$_GET["ph"];
		if(isset($_GET["c"]))$modi["channel_codec"]=$_GET["c"];
		if(isset($_GET["p"]))$modi["channel_codec_quality"]=$_GET["p"];
		if(isset($_GET["dl"]))$modi["channel_codec_latency_factor"]=$_GET["dl"];
		if(isset($_GET["uc"]))$modi["channel_codec_is_unencrypted"]=$_GET["uc"];
		if(isset($_GET["tp"]))$modi["channel_needed_talk_power"]=$_GET["tp"];
		if(isset($_GET["o"]))$modi["channel_order"]=$_GET["o"];
		if(isset($_GET["df"]))$modi["channel_flag_default"]=$_GET["df"];
		else {		
			if(isset($_GET["s"]))$modi["channel_flag_semi_permanent"]=$_GET["s"];
			if(isset($_GET["p"]))$modi["channel_flag_permanent"]=$_GET["p"];
			if(isset($_GET["mcu"]))$modi["channel_flag_maxclients_unlimited"]=$_GET["mcu"];
			if(isset($_GET["mc"]))$modi["channel_maxclients"]=$_GET["mc"];
			if(isset($_GET["mctu"]))$modi["channel_flag_maxfamilyclients_unlimited"]=$_GET["mctu"];
			if(isset($_GET["mcti"]))$modi["channel_flag_maxfamilyclients_inherited"]=$_GET["mcti"];
			if(isset($_GET["mct"]))$modi["channel_maxfamilyclients"]=$_GET["mct"];
		}
		$c = $ts3server->channelCreate(array("channel_name"=>$cname, "channel_flag_permanent"=>true));
		$ts3server->channelGetById($c)->modify($modi);
		
		$smarty->display('../mod/default/ts3admin/ChannelOption.tpl');
		die();
	}
	
	
	
	
	// Get channel object
	$cid = $_GET["cid"];
	$smarty->assign('cid',$cid);
	$channel = NULL;
	foreach($channels as $chn) {
		if($chn["cid"]==$cid) {
			$channel=$chn;
			break;
		}
	}
	
	if($channel==NULL) {
		$notify->raiseError("",$lang->get("cantfindchannel"));	
		$smarty->display('../mod/default/ts3admin/chooseVServer.tpl');
		die();	
	}
	
	
	if(isset($_GET["askdelete"])) {
		$smarty->assign("askdelete",true);
		$smarty->display('../mod/default/ts3admin/ChannelOption.tpl');
	}else if(isset($_GET["askmove"])) {
		$smarty->assign("askmove",true);
		foreach ($channels as $chn) {
			$channels[$chn["cid"]]=array();
			$channels[$chn["cid"]]["cid"] = $chn["cid"];
			$channels[$chn["cid"]]["pid"] = $chn["pid"];
			$channels[$chn["cid"]]["channel_name"] = $chn["channel_name"];
		}
		$smarty->assign('channels',$channels);
		$smarty->assign('channelcount',count($channels));
		$smarty->assign('channelid', $channel["pid"]);
		$smarty->assign('virtualserver_name', $ts3server["virtualserver_name"]);
		$smarty->display('../mod/default/ts3admin/ChannelOption.tpl');
	}else if(isset($_GET["askedit"])) {
		$smarty->assign("edit",true);
		$smarty->assign('channel_name',$channel["channel_name"]);
		$smarty->assign('channel_fpassword',$channel["channel_flag_password"]);
		$smarty->assign('channel_password',$channel["channel_password"]);
		$smarty->assign('channel_topic',$channel["channel_topic"]);
		$smarty->assign('channel_description',$channel["channel_description"]);
		$smarty->assign('channel_phonetic',$channel["channel_name_phonetic"]);
		$smarty->assign('channel_codec',$channel["channel_codec"]);
		$smarty->assign('channel_quality',$channel["channel_codec_quality"]);
		$smarty->assign('channel_delay',$channel["channel_codec_latency_factor"]);
		$smarty->assign('channel_semi',$channel["channel_flag_semi_permanent"]);
		$smarty->assign('channel_perm',$channel["channel_flag_permanent"]);
		$smarty->assign('channel_mcunlimited',$channel["channel_flag_maxclients_unlimited"]);
		$smarty->assign('channel_maxclients',$channel["channel_maxclients"]);
		$smarty->assign('channel_default',$channel["channel_flag_default"]);
		$smarty->assign('channel_unencryptvoice',$channel["channel_codec_is_unencrypted"]);
		$smarty->assign('channel_talkpower',$channel["channel_needed_talk_power"]);
		$smarty->assign('mcintcunlimited',$channel["channel_flag_maxfamilyclients_unlimited"]);
		$smarty->assign('mcinctinherit',$channel["channel_flag_maxfamilyclients_inherited"]);
		$smarty->assign('channel_maxclientsintree',$channel["channel_maxfamilyclients"]);
		$smarty->assign('channel_order',$channel["channel_order"]);
		$smarty->assign('channel_icon',$channel["channel_icon_id"]);
		
		
		$orderchannels = array();
		if($channel["pid"]==0) {
			$orderchannels[] = array("cid"=>0, "name"=>$ts3server["virtualserver_name"]);		
		} else {
			$orderchannels[] = array("cid"=>0, "name"=>$channels[$chn["pid"]]["channel_name"]);		
		}
		foreach($channels as $chn) {
			if($channel["pid"]==$chn["pid"] && $chn["cid"]!=$channel["cid"]) {
				$orderchannels[] = array("cid"=>$chn["cid"], "name"=>$chn["channel_name"]);	
			}
		}
		$smarty->assign('orderchannels',$orderchannels);
		
		$smarty->display('../mod/default/ts3admin/channeldialog.tpl');
	}else if(isset($_GET["askcreatesub"])) {
		$smarty->assign("createsub",true);
		$smarty->assign('channel_codec',2);
		$smarty->assign('channel_quality',7);
		$smarty->assign('channel_delay',0);
		$smarty->assign('channel_maxclients',-1);
		$smarty->assign('channel_maxclientsintree',-1);
		$smarty->assign('channel_unencryptvoice',true);
		$smarty->assign('channel_fpassword',false);
		$smarty->assign('channel_mcunlimited',true);
		$smarty->assign('mcinctinherit',true);
		$smarty->assign('channel_perm',true);
		$lid=0;
		$orderchannels = array();
		$orderchannels[] = array("cid"=>0, "name"=>$channel["channel_name"]);	
		foreach($channels as $chn) {
			if($chn["pid"]==$channel["cid"]) {
				$orderchannels[] = array("cid"=>$chn["cid"], "name"=>$chn["channel_name"]);	
				$lid=$chn["cid"];
			}
		}
		$smarty->assign('orderchannels',$orderchannels);
		$smarty->assign('channel_order',$lid);
		$smarty->display('../mod/default/ts3admin/channeldialog.tpl');
	
	
	
	
	
	}else if(isset($_GET["delete"])) {
		$smarty->assign("delete",true);
		$channel->delete(true);
		$smarty->display('../mod/default/ts3admin/ChannelOption.tpl');
	}else if(isset($_GET["move"])) {
		$smarty->assign("move",true);
		$channel->move($_GET["chn"]);
		$smarty->display('../mod/default/ts3admin/ChannelOption.tpl');
		
	}else if(isset($_GET["edit"])) {	
		$smarty->assign("edit",true);
		
		$modi = array();
		if(isset($_GET["n"]))$modi["channel_name"]=$_GET["n"];
		if(isset($_GET["pw"]))$modi["channel_password"]=$_GET["pw"];
		if(isset($_GET["t"]))$modi["channel_topic"]=$_GET["t"];
		if(isset($_GET["d"]))$modi["channel_description"]=$_GET["d"];
		if(isset($_GET["ph"]))$modi["channel_name_phonetic"]=$_GET["ph"];
		if(isset($_GET["c"]))$modi["channel_codec"]=$_GET["c"];
		if(isset($_GET["p"]))$modi["channel_codec_quality"]=$_GET["p"];
		if(isset($_GET["dl"]))$modi["channel_codec_latency_factor"]=$_GET["dl"];
		if(isset($_GET["uc"]))$modi["channel_codec_is_unencrypted"]=$_GET["uc"];
		if(isset($_GET["tp"]))$modi["channel_needed_talk_power"]=$_GET["tp"];
		if(isset($_GET["o"]))$modi["channel_order"]=$_GET["o"];
		if(isset($_GET["df"]))$modi["channel_flag_default"]=$_GET["df"];
		else {		
			if(isset($_GET["s"]))$modi["channel_flag_semi_permanent"]=$_GET["s"];
			if(isset($_GET["p"]))$modi["channel_flag_permanent"]=$_GET["p"];
			if(isset($_GET["mcu"]))$modi["channel_flag_maxclients_unlimited"]=$_GET["mcu"];
			if(isset($_GET["mc"]))$modi["channel_maxclients"]=$_GET["mc"];
			if(isset($_GET["mctu"]))$modi["channel_flag_maxfamilyclients_unlimited"]=$_GET["mctu"];
			if(isset($_GET["mcti"]))$modi["channel_flag_maxfamilyclients_inherited"]=$_GET["mcti"];
			if(isset($_GET["mct"]))$modi["channel_maxfamilyclients"]=$_GET["mct"];
		}
		$channel->modify($modi);
		$smarty->display('../mod/default/ts3admin/ChannelOption.tpl');
	}else if(isset($_GET["createsub"])) {	
		$smarty->assign("create",true);
		$cname = "Channel".rand();
		$modi = array('channel_codec'=>2, 'channel_codec_quality'=>7, 'channel_codec_latency_factor'=> 0);
		if(isset($_GET["n"]))$cname=$_GET["n"];
		if(isset($_GET["pw"]))$modi["channel_password"]=$_GET["pw"];
		if(isset($_GET["t"]))$modi["channel_topic"]=$_GET["t"];
		if(isset($_GET["d"]))$modi["channel_description"]=$_GET["d"];
		if(isset($_GET["ph"]))$modi["channel_name_phonetic"]=$_GET["ph"];
		if(isset($_GET["c"]))$modi["channel_codec"]=$_GET["c"];
		if(isset($_GET["p"]))$modi["channel_codec_quality"]=$_GET["p"];
		if(isset($_GET["dl"]))$modi["channel_codec_latency_factor"]=$_GET["dl"];
		if(isset($_GET["uc"]))$modi["channel_codec_is_unencrypted"]=$_GET["uc"];
		if(isset($_GET["tp"]))$modi["channel_needed_talk_power"]=$_GET["tp"];
		if(isset($_GET["o"]))$modi["channel_order"]=$_GET["o"];
		if(isset($_GET["df"]))$modi["channel_flag_default"]=$_GET["df"];
		else {		
			if(isset($_GET["s"]))$modi["channel_flag_semi_permanent"]=$_GET["s"];
			if(isset($_GET["p"]))$modi["channel_flag_permanent"]=$_GET["p"];
			if(isset($_GET["mcu"]))$modi["channel_flag_maxclients_unlimited"]=$_GET["mcu"];
			if(isset($_GET["mc"]))$modi["channel_maxclients"]=$_GET["mc"];
			if(isset($_GET["mctu"]))$modi["channel_flag_maxfamilyclients_unlimited"]=$_GET["mctu"];
			if(isset($_GET["mcti"]))$modi["channel_flag_maxfamilyclients_inherited"]=$_GET["mcti"];
			if(isset($_GET["mct"]))$modi["channel_maxfamilyclients"]=$_GET["mct"];
		}
		$c = $ts3server->channelCreate(array("cpid"=>$channel["cid"], "channel_name"=>$cname, "channel_flag_permanent"=>true));
		$ts3server->channelGetById($c)->modify($modi);		
		
		$smarty->display('../mod/default/ts3admin/ChannelOption.tpl');
	}
	
	
	
	
	
	
	
	
	
	
?>