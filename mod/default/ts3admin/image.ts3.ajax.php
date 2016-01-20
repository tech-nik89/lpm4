<?php

	//init
	require_once("libraries/ts3init.php");
	
	//get sid and init server_instance
	getSID();
	
	//get vsid and locate vserver_instance
	getVSID();
	
	$image=NULL;
	
	if($_GET["type"]=="channel") {
		$channels = $ts3server->channelList();	
		foreach($channels as $chn) {
			if($chn["cid"]==$_GET["cid"])
				$image = $chn->iconDownload();
		}
	}else if($_GET["type"]=="client") {
		$client = $ts3server->clientList();	
		foreach($client as $clt) {
			if($clt["cid"]==$_GET["cid"]){
				$image = $clt->avatarDownload();
			}
		}
	}
	
	
	if($image!=NULL){
		header("Content-type: ".$image['Type']);
		echo $image;
	} else {
		$image = imagecreatetruecolor(1, 1);
		imagecolortransparent ( $image , 0x0 );
		header("Content-type: ".$image['Type']);
		imagepng($image);
	}

?>