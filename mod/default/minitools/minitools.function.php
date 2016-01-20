<?php

	function saveMinitools($visibleTools) {
		global $db;
		
		$db->delete('minitools');
		
		if(count($visibleTools)>0) {	
			foreach($visibleTools as $tool=>$on) {
				$db->insert('minitools', array('modename'), array("'".$tool."'"));
			}
		}
	}
	
	function getAllVisibleMinitools() {
		global $db;
		global $lang;
		
		$visibletools = $db->selectList('minitools', '`modename`');
		
		for($i=0; $i<count($visibletools); $i++) {
			$visibletools[$i]['url'] = makeHTMLUrl($lang->get($visibletools[$i]['modename']), makeURL('minitools', array('mode' => $visibletools[$i]['modename'])));
		}
		
		return $visibletools;
	}
	
	
	function getAllMinitools($availableModes) {
		global $db;
		
		$visiblelist = $db->selectList('minitools', '`modename`');
		
		$vis = array();
		foreach($visiblelist as $visible) {
			$vis[] = $visible['modename'];
		}
		
		if($availableModes) {
			foreach($availableModes as $mode) {
				$dummy = array();
				if(in_array($mode, $vis)) {
					$dummy['visible'] = true;
				} 
				$dummy['modename'] = $mode;
			$ret[] = $dummy;
			}	
		}

		return $ret;
	}
	
	function makeMenueEntries() {
		global $db;
		global $menu;
		global $lang;
		
		$all = $db->selectList('minitools', '`modename`');
		
		foreach($all as $single) {
			$menu->addSubElement('minitools', $lang->get($single['modename']), $single['modename']);
		}
	}
	
	
	function wrapText($wrapme, $length) {
		$data = $wrapme;
		$datalb = "";
		while (strlen($data) > $length) {
			$datalb .= substr($data, 0, $length) . "\n";
			$data = substr($data, $length);
		}
		$datalb .= $data;
		return $datalb;
	} 
	
	
	function getRomanFromDec($number) {
		$roman = array(1=>'I', 4=>'IV', 5=>'V', 9=>'IX', 10=>'X', 40=>'XL', 50=>'L', 90=>'XC', 100=>'C', 400=>'CD', 500=>'D', 900=>'CM', 1000=>'M');
		$counter = array(1000, 900, 500, 400, 100, 90, 50, 40, 10, 9, 5, 4, 1);
		$result = '';
		foreach($counter as $nr){
			while($number>=$nr) {
				$result.=$roman[$nr];	
				$number-=$nr;
			}
		}		
		return $result;
	}

	
	function getDecFromRoman($number) {
		$number = strtoupper($number);
		if(!preg_match("/^[IVXLCDM]*$/", $number)) {
			return 0;
		}
		$roman = array('I'=>1, 'V'=>5, 'X'=>10, 'L'=>50, 'C'=>100, 'D'=>500, 'M'=>1000);
		$result = 0;
		$rem = 0;
		for($i=strlen($number)-1; $i>=0; $i--) {
			$char = $number[$i];
			if($rem > $roman[$char]) {
				$result -= $roman[$char];
			} else {
				$result += $roman[$char];
				$rem = $roman[$char];
			}	
		}		
		return $result;
	}
	
?>