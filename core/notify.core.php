<?php
	
	/**
	 * Project: Higher For Hire
	 * File: notify.core.php
	 *
	**/
	
	class Notify {
		private $table;
		private $notifications = array();
		
		function __construct() {
			$this->table = MYSQL_TABLE_PREFIX . 'notify';
		}
		
		function setup() {
			global $db;
			$sql = "CREATE TABLE IF NOT EXISTS `".$this->table."` (
						`file` VARCHAR (64) NOT NULL
						) ENGINE = MYISAM ;";
			$db->query($sql);
		}
		
		function registerFile($file) {
			global $db;
			$c = $db->num_rows($this->table, "`file`='".secureMySQL($file)."'");
			if ($c == 0)
				$db->insert($this->table, array('file'), array("'".secureMySQL($file)."'"));
		}
		
		function runFiles() {
			global $db;
			$result = $db->selectList($this->table);
			if (count($result) > 0)
				foreach($result as $r)
					$this->runFile($r['file']);
		}
		
		function runFile($file) {
			global $db;
			global $menu;
			global $login;
			global $lang;
			
			include('./notify/'.$file);
		}
		
		function add($subject, $message) {
			$this->notifications[] = array(
									'subject' => $subject,
									'message' => $message
									);
		}
		
		function getAll() {
			return $this->notifications;
		}
		
		function raiseError($subject, $message, $location = "") {
			die ('
			
			<h1>Error: ' . $subject . '</h1>
			<p>' . $message . '</p>'
			
				);
		}	
	}
?>