<?php
class tss2info {




	var $serverAddress = ""; // Server IP
	var $serverQueryPort = ""; // TCP Port
	var $serverUDPPort = ""; // UDP Port
	var $serverPasswort = ""; // Serverpasswort

	var $SubChannel=array();
	var $JSneeded=false;
	var $nr;

	var $refreshtime = 30;
	var $tabellenbreite = 300;

	//internal
	var $socket;
	var $userdata;

	// external
	var $serverStatus = "offline";
	var $playerList = array();
	var $channelList = array();
	var $serverInfo = array();


	// strips the quotes around a string
	function stripQuotes($evalString) {
	  if(strpos($evalString, '"') == 0) $evalString = substr($evalString, 1, strlen($evalString) - 1);
	  if(strrpos($evalString, '"') == strlen($evalString) - 1) $evalString = substr($evalString, 0, strlen($evalString) - 1);

	  return $evalString;
	}// end function stripQuotes($evalString)

	// returns the codec name
	function getVerboseCodec($codec) {
	  if($codec == 0) {
	    $codec = "CELP 5.1 Kbit";
	  } elseif($codec == 1) {
	    $codec = "CELP 6.3 Kbit";
	  } elseif($codec == 2) {
	    $codec = "GSM 14.8 Kbit";
	  } elseif($codec == 3) {
	    $codec = "GSM 16.4 Kbit";
	  } elseif($codec == 4) {
	    $codec = "CELP Windows 5.2 Kbit";
	  } elseif($codec == 5) {
	    $codec = "Speex 3.4 Kbit";
	  } elseif($codec == 6) {
	    $codec = "Speex 5.2 Kbit";
	  } elseif($codec == 7) {
	    $codec = "Speex 7.2 Kbit";
	  } elseif($codec == 8) {
	    $codec = "Speex 9.3 Kbit";
	  } elseif($codec == 9) {
	    $codec = "Speex 12.3 Kbit";
	  } elseif($codec == 10) {
	    $codec = "Speex 16.3 Kbit";
	  } elseif($codec == 11) {
	    $codec = "Speex 19.5 Kbit";
	  } elseif($codec == 12) {
	    $codec = "Speex 25.9 Kbit";
	  } else {
	    $codec = "unknown (".$codec.")";
	  }// end if
	  return $codec;
	}// end function getVerboseCodec($codec);
	  function __construct ($nr) { $this->nr = $nr; }
	  function getInfo() {
		$this->playerList = $this->getUserInfo($this->serverAddress, $this->serverQueryPort, $this->serverUDPPort);
		$this->channelList = $this->getChannelInfo($this->serverAddress, $this->serverQueryPort, $this->serverUDPPort);
		$this->serverInfo = $this->getServerInfo($this->serverAddress, $this->serverQueryPort, $this->serverUDPPort);
		#print_r($this->serverInfo);

		$this->GetSubChannel();
		$server = $this->serverInfo;
		$server['serverurl'] = "teamspeak://".$this->serverAddress.":".$this->serverUDPPort."/";
				global $login; $cUser= $login->currentUser();
				if ($cUser) $server['serverurl'] .= "?nickname=".$cUser['nickname'];
				if (!empty($this->serverPasswort)) $server['serverurl'] .= "?password=".$this->serverPasswort;
		$server['address'] = $this->serverAddress;
		$server['channel'] = $this->SubChannel;
		#print_r($server);
		return $server;
	  } // end getInfo()

	  function getServerInfo($server_ip, $server_query, $server_port) {
              $serverInfo = array();
		$playerList = array();
	    $cmd = "si $server_port\nquit\n";

	    @$connection = fsockopen ("$server_ip", $server_query, $errno, $errstr, 1);
	    if (!$connection) { 
	      #$mess.= "Cannot connect: ($errno)-$errstr<br>";
	    } else {
	      fputs($connection,$cmd, strlen($cmd));
	      while($serverdata = fgets($connection, 4096)) {

		$serverdata = explode("=", $serverdata);
		$serverdataname = trim($serverdata[0]);  // identitiy name
		@$serverdataval = trim($serverdata[1]);  // identitiy value

			$serverInfo[$serverdataname] = $serverdataval;
			}
	      fclose($connection);
	    }
		return $serverInfo;
		
		
	  }
	  
	  
	  function getChannelInfo($server_ip, $server_query, $server_port) {
	  global $mess;
		$channelList = array();
	    $cmd = "cl $server_port\nquit\n";

	    @$connection = fsockopen ("$server_ip", $server_query, $errno, $errstr, 1);
	    if (!$connection) {
		  #$mess.= "Cannot connect: ($errno)-$errstr<br>";  OFFLINE
	    } else {
		  $error = fputs($connection,$cmd, strlen($cmd));
		  if (!$error) {
		  	$mess.= "Cannot read Data!<br>";
		  } else {
		  
			  while($channeldata = fgets($connection, 4096)) {
				$channeldata = explode("	", $channeldata);
				@$channeldata0 = trim($channeldata[0]);  // number
				@$channeldata1 = trim($channeldata[1]);  // codec
				@$channeldata2 = trim($channeldata[2]);  // parent
				@$channeldata3 = trim($channeldata[3]);  // order
				@$channeldata4 = trim($channeldata[4]);  // maxuser
	
				@$channeldata5 = trim("$channeldata[5]");
				@$channeldata5 = substr("$channeldata5", 1);
				@$channeldata5 = substr("$channeldata5", 0, -1);
				@$channeldata5 = addslashes("$channeldata5"); // name
				@$channeldata5 = htmlentities("$channeldata5");
				 // $channeldata5 = addslashes(trim($channeldata[5]));  // name
	
				@$channeldata6 = trim($channeldata[6]);  // channel flags
				@$channeldata7 = trim($channeldata[7]);  // priv/pub

				@$channeldata8 = trim("$channeldata[8]");
				@$channeldata8 = substr("$channeldata8", 1);
				@$channeldata8 = substr("$channeldata8", 0, -1);
				@$channeldata8 = addslashes(htmlspecialchars("$channeldata8")); // topic
				 // $channeldata8 = addslashes(htmlspecialchars(trim($channeldata[8])));  // topic
			
				$channelid = $channeldata0;
				if ($channelid > 0) {
					$channelList[$channelid] = array(
					  "channelid" => $channelid,
					  "codec" => $channeldata1,
					  "parent" => $channeldata2,
					  "order" => $channeldata3,
					  "maxplayers" => $channeldata4,
					  "channelname" => $channeldata5,
					  "attribute" => $this->get_channel_flags($channeldata6),
					  "priv" => $channeldata7,
					  "isdefault" => 0,
					  "topic" => $channeldata8);
				}
			  }
		  }
	      fclose($connection);
	    }
	


		usort($channelList, "cmp");

	
		return $channelList;
	  }

	  function getUserInfo($server_ip, $server_query, $server_port) {
		$playerList = array();
	    $cmd = "pl $server_port\nquit\n";
            
	    @$connection = fsockopen ($server_ip, $server_query, $errno, $errstr, 1);
	    if (!$connection) {
	      #$mess.= "Cannot connect: ($errno)-$errstr<br>";
	    } else {
	      fputs($connection,$cmd, strlen($cmd));
	      while($userdata = fgets($connection, 4096)) {
		@$userdata = explode("	", $userdata);
		@$userdata0 = trim($userdata[0]);  // pl_id
		@$userdata1 = trim($userdata[1]);  // pl_channelid
		@$userdata2 = trim($userdata[2]);  // pl_pktssend
		@$userdata3 = trim($userdata[3]);  // pl_bytessend
		@$userdata4 = trim($userdata[4]);  // pl_pktsrecv
		@$userdata5 = trim($userdata[5]);  // pl_bytesrecv
		@$userdata6 = trim($userdata[6]);  // pl_pktloss
		@$userdata7 = trim($userdata[7]);  // pl_ping
		@$userdata8 = trim($userdata[8]);  // pl_logintime
		@$userdata9 = trim($userdata[9]);  // pl_idletime
		@$userdata10 = trim($userdata[10]);  // pl_channelprivileges
		@$userdata11 = trim($userdata[11]);  // pl_playerprivileges
		@$userdata12 = trim($userdata[12]);  // pl_playerflags
		@$userdata13 = trim($userdata[13]);  // pl_ipaddress

		@$userdata14 = trim("$userdata[14]");
		@$userdata14 = substr("$userdata14", 1);
		@$userdata14 = substr("$userdata14", 0, -1);
		@$userdata14 = addslashes("$userdata14");
			$userdata14 = htmlentities("$userdata14"); // pl_nickname
			  // $userdata14 = addslashes(trim($userdata[14]));  // pl_nickname

		@$userdata15 = trim("$userdata[15]");
		@$userdata15 = substr("$userdata15", 1);
		@$userdata15 = substr("$userdata15", 0, -1);
		@$userdata15 = addslashes("$userdata15"); // pl_loginname
		  // $userdata15 = addslashes(trim($userdata[15]));  // pl_loginname

			$playerid = $userdata0;
		
			$playerList[$playerid] = array(
			  "playerid" => $playerid,
			  "channelid" => $userdata1,
			  "receivedpackets" => $userdata4,
			  "receivedbytes" => $userdata5,
			  "sentpackets" => $userdata2,
			  "sentbytes" => $userdata3,
			  "paketlost" => $userdata6 / 100,
			  "pingtime" => $userdata7,
			  "totaltime" => $this->setTimeString($userdata8),
			  "idletime" => $this->setTimeString($userdata9),
			  "privileg" => $userdata10,
			  "userstatus" => $userdata11,
			  "attribute" => $this->getUserStatusBild($userdata12),
			  "flags" => $this->get_player_flags($userdata11,$userdata10),
			  "s" => $userdata13,
			  "playername" => $userdata14);
	      }
	      fclose($connection);
	    }
		return $playerList;
	  }
	  
	  function get_player_flags($num1,$num2) {
              $plpriv = '';
	      if ($num1 == '13') {
		$plpriv = "(R <b>SA</b>";
	      } else if ($num1 == '5') {
		$plpriv = "(R <b>SA</b>";
	      } else if ($num1 == '4') {
		$plpriv = "(R";
	      } else if ($num1 < '4') {
		$plpriv = "(U";
	      }
	      if ($num2 == '1') {
		$clpriv = " CA)";
	      } else {
		$clpriv = ")";
	      }
		  return $plpriv.$clpriv;
	  }
	  
	  function get_channel_flags($num) {
		 // (RMPSD) (0 2 4 6 8 16)
	      if ($num == '30') {
		$clflag = "(RMPSD)";
	      } else if ($num == '28') {
		$clflag = "(RPSD)";
	      } else if ($num == '26') {
		$clflag = "(RMSD)";
	      } else if ($num == '24') {
		$clflag = "(RSD)";
	      } else if ($num == '22') {
		$clflag = "(RMPD)";
	      } else if ($num == '20') {
		$clflag = "(RPD)";
	      } else if ($num == '18') {
		$clflag = "(RMD)";
	      } else if ($num == '16') {
		$clflag = "(RD)";
	      } else if ($num == '15') {
		$clflag = "(UMPS)";
	      } else if ($num == '14') {
		$clflag = "(RMPS)";
	      } else if ($num == '13') {
		$clflag = "(UPS)";
	      } else if ($num == '12') {
		$clflag = "(RPS)";
	      } else if ($cldata[9] == '11') {
		$clflag = "(UMS)";
	      } else if ($num == '10') {
		$clflag = "(RMS)";
	      } else if ($num == '9') {
		$clflag = "(US)";
	      } else if ($num == '8') {
		$clflag = "(RS)";
	      } else if ($num == '7') {
		$clflag = "(UMP)";
	      } else if ($num == '6') {
		$clflag = "(RMP)";
	      } else if ($num == '5') {
		$clflag = "(UP)";
	      } else if ($num == '4') {
		$clflag = "(RP)";
	      } else if ($num == '3') {
		$clflag = "(UM)";
	      } else if ($num == '2') {
		$clflag = "(RM)";
	      } else if ($num == '1') {
		$clflag = "(U)";
	      } else if ($num == '0') {
		$clflag = "(R)";
	      } else {
		$clflag = "";
	      }
		return $clflag;
	  }
	  
	function getUserStatusBild($attribut) {
		  //-------------------------------------------------------------------------------------------------
		//--- UserStatusBild --\\
		$playergif = "player.gif";
		if ($attribut == "0") $playergif = "player.gif";
		if (($attribut == "8") or
			($attribut == "9") or
			($attribut == "12") or
			($attribut == "13") or
			($attribut == "24") or
			($attribut == "25") or
			($attribut == "28") or
			($attribut == "29") or
			($attribut == "40") or
			($attribut == "41") or
			($attribut == "44") or
			($attribut == "45") or
			($attribut == "56") or
			($attribut == "57")) $playergif = "away.gif";
		if (($attribut == "16") or
			($attribut == "17") or
			($attribut == "20") or
			($attribut == "21")) $playergif = "mutemicro.gif";
		if (($attribut == "32") or
			($attribut == "33") or
			($attribut == "36") or
			($attribut == "37") or
			($attribut == "48") or
			($attribut == "49") or
			($attribut == "52") or
			($attribut == "53")) $playergif = "mutespeakers.gif";
		if ($attribut == "4") $playergif = "player.gif";
		if (($attribut == "1") or
			($attribut == "5")) $playergif = "channelcommander.gif";
		if  ($attribut >= "64") $playergif = "record.gif";
		//--- UserStatusBild --\\
		//-------------------------------------------------------------------------------------------------
		return $playergif;
	}
	function setTimeString($time) {
		$timestring = "0 Sekunden";

		if ($time < 60 ) {
		 $timestring = @strftime("%S Sekunden", $time);
		} else {
		   if ($time >= 3600 ) {
		    $timestring = @strftime("%H:%M:%S Stunden", $time - 3600);
		   } else {
		     $timestring = @strftime("%M:%S Minuten", $time);
		   }
		}
		return htmlentities("$timestring");
	}



	function getPlayer($chanelID, $channelID, $SubCounter) {
		global $config, $page;
		//$channelInfo = $this->channelList[$chanelID];
		$player = array();

		foreach($this->playerList as $playerInfo) {
			if ($playerInfo['channelid'] == $channelID) {
			$player = $playerInfo;

		//-------------------------------------------------------------------------------------------------
				#$player .= loadtpl('tsplayer_blt.tpl');

				#$width=32;
				$width=36;
				$player['gridicon'] = 0;
				for ($i=0;$i<=$SubCounter;$i++) {
					#$width+=16;
					$width+=18;
					#$gitter .= loadtpl('gitter_blt.tpl');
					$player['gridicon'] += 1;
				}
				$player['gridwidth'] = $width;

				//if ($channelInfo[parent]==-1) {

				#$player =  str_replace('%gitter%',$gitter,$player);
				#$player =  str_replace('%tstablewidth%',$width,$player);
				#$player =  str_replace('%playerAttribute%',$playerInfo[attribute],$player);
				#$player =  str_replace('%playeronlinetime%',$playerInfo[totaltime],$player);
				#$player =  str_replace('%playeridletime%',$playerInfo[idletime],$player);
				#$player =  str_replace('%playerping%',$playerInfo[pingtime],$player);
				#$player =  str_replace('%playername%',$playerInfo[playername],$player);
				#$player =  str_replace('%playerflags%',$playerInfo[flags],$player);
			#echo $playerInfo[playername];
		
	
			}
		}
		return $player;
	
	}

	function getSubChannel($chanelID=-1,$SubCounter=0) {

		

					#print_r ($this);
		global $config, $page, $counti;
		if ($chanelID==-1) $SubCounter=0; else $SubCounter++;
		if (!empty($this->channelList)) {
			foreach($this->channelList as $channelnumber => $channelInfo) {
				
				$SubChannel = $channelInfo;
				$SubChannel['id'] = $channelnumber;
			  $channelname = $channelInfo['channelname'];
			  // determine codec (verbose)
			  $codec = $this->getVerboseCodec($channelInfo['codec']);
			
			  if ($channelInfo['parent']==$chanelID) {

			//-------------------------------------------------------------------------------------------------
				//---> Channel <---\\ Anfang
				#$SubChannel .= loadtpl('tschannel_blt.tpl');

				$width=32;
				$SubChannel['gridicon'] = 0;
				for ($i=1;$i<=$SubCounter;$i++) {
					$width+=16;
					#$gitter .= loadtpl('gitter_blt.tpl');
					$SubChannel['gridicon'] += 1;
				} 
				$SubChannel['gridwidth'] = $width; 
				$SubChannel['channelurl'] = "teamspeak://".$this->serverAddress.":".$this->serverUDPPort."/?channel=".$channelname;
				global $login; $cUser= $login->currentUser();
				if ($cUser) $SubChannel['channelurl'] .= "?nickname=".$cUser['nickname'];
				if (!empty($this->serverPasswort)) $SubChannel['channelurl'] .= "?password=".$this->serverPasswort;

				#TODO:CHANNELPRIVATE   if ($SubChannel['priv'] == 1) { $SubChannel['channelurl'] = "javascript:window.location='test'"; }

				#$SubChannel['channelname'] = $channelname;
				#$SubChannel['channelTopic'] = $channelInfo[topic];
				#$SubChannel['channelattributes'] = $channelInfo[attribute];

				#$SubChannel =  str_replace('%gitter%',$gitter,$SubChannel);
				#$SubChannel =  str_replace('%tstablewidth%',$width,$SubChannel);
				#$SubChannel =  str_replace('%counti%',$counti,$SubChannel);
				#$SubChannel =  str_replace('%channelname%',$channelname,$SubChannel);
				#$SubChannel =  str_replace('%privateServer%',$this->serverInfo[server_password],$SubChannel);
				#$SubChannel =  str_replace('%privateChannel%',$channelInfo[priv],$SubChannel);
				#$SubChannel =  str_replace('%channelTopic%',$channelInfo[topic],$SubChannel);
		
		

				#if ($SubCounter==0)	{$SubChannel =  str_replace('%tschannelattribute%',loadtpl('tschannelattribute_blt.tpl'),$SubChannel);}
				#else { $SubChannel =  str_replace('%tschannelattribute%','',$SubChannel);}
				#$SubChannel =  str_replace('%channelAttribute%',$channelInfo[attribute],$SubChannel);
		

				//---> Channel <---\\ Ende
			//-------------------------------------------------------------------------------------------------

				$SubPlayer = $this->getPlayer($channelInfo['channelid'],$channelInfo['channelid'],$SubCounter); //User einfügen
				if (!empty($SubPlayer)) $SubChannel['player'][] = $SubPlayer;

				$this->SubChannel[]=$SubChannel;

				if (!empty($SubChannel)) $this->getSubChannel($channelInfo['channelid'],$SubCounter); //Rekusiver Aufruf!!

				 
				
			  }
			}
			return $SubChannel;
		}
	}
}

function cmp ($a, $b) {
	if ($a["order"] == $b["order"]) {
	  	return ($a["channelid"] < $b["channelid"]) ? -1 : 1;
	}//return 0;
	return ($a["order"] < $b["order"]) ? -1 : 1;
}


?>
