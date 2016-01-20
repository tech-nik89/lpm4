<?php
	
	$yes = '<font color="#00AA00">'.$lang->get('yes').'</font>';
	$no = '<font color="#AA0000">'.$lang->get('no').'</font>';
	
	require_once('./mod/default/server/gameq/GameQ.php');
	$gq = new GameQ;
	
	$servers[$_GET['gameq']] = array($_GET['gameq'], $_GET['ipadress'], $_GET['port']);
	$gq->addServers($servers);
	
	@$data = $gq->requestData();
	if ($data[$_GET['gameq']]['gq_online']) {
		echo $yes;
	}
	else {
		echo $no;
	}
	
?>