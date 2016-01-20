<?php
	
	if ($_GET['gameq'] != '') {
	
		require_once('./mod/default/server/gameq/GameQ.php');
		$gq = new GameQ;
		
		$servers[$_GET['gameq']] = array($_GET['gameq'], $_GET['ipadress'], $_GET['port']);
		$gq->addServers($servers);
		
		@$data = $gq->requestData();
		
		/*
		echo '<pre>';
		var_dump($data);
		*/

		$smarty->assign('status', $data[$_GET['gameq']]);
		$smarty->display("../mod/default/server/status.tpl");
	}
?>