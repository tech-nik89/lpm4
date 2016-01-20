<?php
	
	function getCurrentDomainName() {
		if (isset($_GET['debug-domain'])) {
			return $_GET['debug-domain'];
		}
		
		$replace = array('www.', 'http://', 'https://', '/');
		$domain = $_SERVER['HTTP_HOST'];
		$domain = str_replace($replace, '', $domain);
		return $domain;
	}
	
	function getCurrentDomain() {
		global $db;
		$alias_max_depth = 10;
		
		$name = getCurrentDomainName();
		$result = $db->selectOneRow('domains', '*', "INSTR(`name`, '".$name."') > 0");
		
		$alias_depth = 0;
		while ($result['alias'] > 0) {
			$result = $db->selectOneRow('domains', '*', "`domainid`=".(int)$result['alias']);
			
			$alias_depth++;
			if ($alias_depth > $alias_max_depth) {
				break;
			}
		}
		
		return $result;
	}
	
	function getCurrentDomainIndex() {
		$domain = getCurrentDomain();
		return (int)$domain['domainid'];
	}
	
	function getDomainList() {
		global $db;
		return $db->selectList('domains');
	}
	
?>