<?php
	
	function userHasClan($userid) {
		global $db;
		return $db->num_rows('clan_member', "`userid`=".(int)$userid) > 0;
	}
	
	function isMyClan($clanid) {
		global $db, $login;
		return $db->num_rows('clan_member', "`clanid`=".(int)$clanid." AND `userid`=".$login->currentUserId()) > 0;
	}
	
	function addClanPrefix($userid, $prefix) {
		global $db, $config;
		if ($config->get('clan', 'enable-prefix') == '1') {
			$sep = $config->get('clan', 'prefix-seperator');
			
			$oldnick = $db->selectOne('users', 'nickname', "`userid`=".(int)$userid);
			$newnick = secureMySQL($prefix).$sep.$oldnick;
			
			$db->update('users', "`nickname`='".$newnick."'", "`userid`=".(int)$userid);
		}
	}
	
	function removeClanPrefix($userid, $prefix) {
		global $db, $config;
		if ($config->get('clan', 'enable-prefix') == '1') {
			$sep = $config->get('clan', 'prefix-seperator');
			$oldnick = $db->selectOne('users', 'nickname', "`userid`=".(int)$userid);
			if ($sep != '') {
				$pos = strpos($oldnick, $sep);
				if ($pos > 0) {
					$newnick = substr($oldnick, $pos + strlen($sep));
				}
			}
			else {
				$newnick = substr($oldnick, strlen($prefix));
			}
			$db->update('users', "`nickname`='".$newnick."'", "`userid`=".(int)$userid);
		}
	}
	
?>