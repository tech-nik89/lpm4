<?php
	
	/**
	 * Project: Higher For Hire
	 * File: common.core.php
	 *
	 * Common function collection
	**/

	
	class Favorites {
		
		var $table;
		
		function setup() {
			global $db, $config;
			
			$sql = "
				CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "favorites` (
				`favoriteid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`name` VARCHAR ( 127 ) NOT NULL , 
				`userid` INT NOT NULL ,
				`mod` VARCHAR( 127 ) NOT NULL ,
				`params` TEXT NOT NULL
				) ENGINE = MYISAM ;
			";
			
			$db->query($sql);
			
			$config->register('favorites', 'enable', 0, 'bool', 'Enables or disables the global favorites.');
			
		}
		
		function __construct() {
			$this->table = MYSQL_TABLE_PREFIX . 'favorites';
		}
		
		function addFromCurrent() {
			global $breadcrumbs;
			$name = $breadcrumbs->getSmall();
			if ($name == '')
				$name = $_GET['mod'];
			$mod = $_GET['mod'];
			$params = Array();
			foreach ($_GET as $key => $val) {
				if ($key != 'mod') {
					$params[$key] = $val;
				}
			}
			$this->add($name, $mod, $params);
		}
		
		function add($name, $mod, $params = array()) {
			global $db, $login, $config;
			if ($config->get('favorites', 'enable') == '1') {
				$db->insert($this->table, array('name', 'userid', 'mod', 'params'),
										array("'".$name."'", $login->currentUserId(), "'".$mod."'", "'".serialize($params)."'"));
			}
		}
		
		function get() {
			global $db, $login;
			$list = array();
			if ($login->currentUser() !== false) {
				$list = $db->selectList($this->table, "*", "`userid`=".$login->currentUserId());
				if (count($list) > 0) {
					foreach ($list as $i => $l) {
						$list[$i]['url'] = makeURL($l['mod'], unserialize($l['params']));
					}
				}
			}
			return $list;
		}
		
		function delete($favoriteid) {
			global $db, $login;
			$db->delete($this->table, "`userid`=".$login->currentUserId()." AND `favoriteid`=".(int)$favoriteid);
		}
	}
	
?>