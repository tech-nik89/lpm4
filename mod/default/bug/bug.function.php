<?php

	function addZeros($issueid) {
		$out = "0";
		for ($i = 0; $i < 5 - strlen($issueid); $i++)
			$out .= "0";
		$out .= $issueid;
		return $out;
	}

?>