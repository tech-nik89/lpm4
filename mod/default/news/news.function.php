<?php
	function makePreview($text, $maxlength) {
		$open='[';
		$close=']';
		if(substr_count($text, $open) != substr_count($text, $close)){
			echo "Falsch formatiert!\n";

			//Abbrechen
		}

		$htmlopentags = 0;
		$finaltext = "";

		$continue = true;
		$opentags = array();
		$nexttag = strpos($text, $open);
		$nextstop = 0;
		if($nexttag != null OR $nexttag>=0){

			while($continue){
				$nextstop = strpos($text, $close, $nextstop+1);
				if(strpos($text, $open.'/', $nexttag) == $nexttag) {
					unset($opentags[--$htmlopentags]);
				} else {
					$opentags[$htmlopentags++] = getTagName($text, $nexttag, $nextstop);
				}

				$maxlength+=$nextstop-$nexttag+1;

				$nexttag = strpos($text, $open, $nexttag+1);

				if($nexttag >= $maxlength OR $maxlength>=strlen($text)) {
					$continue=false;
				}
			}

			$finaltext.=substr($text, 0, $maxlength);

			$losttags='';
			if(count($opentags)>0) {
				foreach($opentags as $tag) {
					$losttags=$open.'/'.$tag.$close.$losttags;
				}
			}
		$finaltext.=$losttags;
		} else {
			$finaltext=substr($text, 0, $maxlength);
		}
		return $finaltext;
	}
	
	function getTagName($text, $bracketstart, $bracketstop) {
		$fullcontent = substr($text, $bracketstart+1, $bracketstop-$bracketstart-1);
		$equals=strpos($fullcontent, "=");
		if($equals!=null) {
			$fullcontent = substr($fullcontent, 0, $equals);
		}
		return $fullcontent;
	}
	
	function sendPm($senderid, $recieverid, $subject, $message) {
		global $db;
		$db->insert(MYSQL_TABLE_PREFIX . 'inbox', 
			array('senderid', 'recieverid', 'timestamp', 'subject', 'message'),
			array($senderid, $recieverid, time(), "'".$subject."'", "'".$message."'"));
	}
?>