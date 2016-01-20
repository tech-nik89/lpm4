<?php
	
	class TournamentCredit {
	
		private $table;
		
		function __construct() {
			$this->table = MYSQL_TABLE_PREFIX.'tournamentcredit';
		}
	
		function exists($userid, $eventid) {
			global $db;
			return $db->num_rows($this->table, "`userid`=".(int)$userid . " AND `eventid`=".(int)$eventid) > 0;
		}
		
		function increment($userid, $eventid, $value) {
			$this->setValue($userid, $eventid, $this->getValue($userid, $eventid) + $value);
		}
		
		function decrement($userid, $eventid, $value) {
			$this->setValue($userid, $eventid, $this->getValue($userid, $eventid) - $value);
		}
		
		function setValue($userid, $eventid, $value) {
			if (!$this->exists($userid, $eventid)) {
				$this->create($userid, $eventid, $value);
			}
			else {
				$this->update($userid, $eventid, $value);
			}
		}
		
		function create($userid, $eventid, $value) {
			global $db;
			$db->insert($this->table,
					array('userid', 'eventid', 'credits', 'timestamp'),
					array((int)$userid, (int)$eventid, (int)$value, time())
				);
		}
		
		function update($userid, $eventid, $value) {
			global $db;
			$db->update($this->table, "`credits` = ".(int)$value.", `timestamp`=".time(),
				"`userid`=" . (int)$userid . " AND `eventid`=" . (int)$eventid);
		}
		
		function getValue($userid, $eventid) {
			global $db;
			$val = $db->selectOne($this->table, 'credits', 
				"`userid`=".(int)$userid . " AND `eventid`=".(int)$eventid);
			if ($val == null) {
				$this->create($userid, $eventid, 0);
				return 0;
			}
			else {
				return (int)$val;
			}
		}
		
		function getValues($userid) {
			global $db;
			$list = $db->selectList($this->table."`, `".MYSQL_TABLE_PREFIX."events", 
				"`".MYSQL_TABLE_PREFIX."events`.`name`, `".$this->table."`.`credits`",
				"`userid`=".(int)$userid."
				AND `".MYSQL_TABLE_PREFIX."events`.`eventid`=`".$this->table."`.`eventid`");
			return $list;
		}
	}
	
?>