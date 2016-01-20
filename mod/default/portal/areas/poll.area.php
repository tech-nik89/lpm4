<?php
	if ($this->isInstalled('poll')) {
		$area['title'] = $lang->get('poll');
		$area['content'] = '';
		
		$tbl_p = MYSQL_TABLE_PREFIX.'poll';
		
		$list = $db->selectList($tbl_p, "*", "`active` = 1", "`ID` DESC", "5");
		if (null != $list && count($list) > 0) {
			foreach ($list as $l) {
				$area['content'] = $area['content'] . "<p>&raquo; ".makeHtmlURL($l['name'], 
					makeURL('poll', array('pollid' => $l['ID']))) . '</p>';
			}
		}
		$areas[] = $area;
	}
?>