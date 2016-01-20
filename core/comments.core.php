<?php

	/**
	 * Project: Higher For Hire
	 * File: comments.core.php
	 *
	 * Comment class
	**/

	class Comments
	{
		
		// Pointer to database class
		private $db;
		
		// Contains the name of the table
		private $table;
		
		// Constructor
		function __construct(&$db_class)
		{
			// Set the database class pointer
			$this->db = $db_class;
			
			 $this->table = MYSQL_TABLE_PREFIX . 'comments';
			 
		}
		
		function setup()
		{
			$sql = "
				CREATE TABLE IF NOT EXISTS `" . $this->table . "` (
					`commentid` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`userid` INT NOT NULL ,
					`timestamp` INT NOT NULL ,
					`mod` VARCHAR (64) NOT NULL ,
					`contentid` INT NOT NULL, 
					`text` VARCHAR (4095) NOT NULL
					) ENGINE = MYISAM ;
				";
				
				$this->db->query($sql);
		}
		
		function add($mod, $userid, $comment, $contentid)
		{
			global $log, $login, $notify, $lang;
			
			if (strlen($comment) <= 2047) {
				if ($login->currentUser() !== false) {
					$log->add('comments', 'comment to module ' . $mod . ' added');
					$this->db->insert($this->table, array("mod", "userid", "text", "timestamp", "contentid"),
																array("'".$mod."'", (int)$userid, "'".$comment."'", time(), (int)$contentid));
					return mysql_insert_id();
				}
				else {
					return false;
				}
			}
			else {
				$notify->add($lang->get('comments'), $lang->get('comment_too_long'));
			}
		}
		
		function remove($commentid)
		{
			global $log;
			$log->add('comments', 'comment ' . $commentid . ' removed');
			$this->db->delete($this->table, "`commentid`=" . (int)$commentid);
		}
		
		function get($mod, $contentid)
		{
			global $user;
			global $bbcode;
			$list = $this->db->selectList($this->table, "*", "`mod`='" . secureMySQL($mod) . "' AND `contentid`=" . (int)$contentid, "`timestamp` ASC");
			
			if (count($list) > 0)
			foreach ($list as $i => $l)
			{
				$u = $user->getUserByID($l['userid']);
				$list[$i]['time'] = timeElapsed($l['timestamp']);
				$list[$i]['nickname'] = $u['nickname'];
				$list[$i]['text'] = $bbcode->parse($l['text']);
			}
			
			return $list;
		}
		
		function getAll($order = 'timestamp', $dir = 'DESC', $start = 0, $cpp = 20)
		{
			global $user;
			global $bbcode;
			
			$s = $start > 0 ? $start : "0";
			
			$list = $this->db->selectList($this->table, "*", "1", "`".$order . "` " . $dir, $s.", ".$cpp);
			
			if (count($list) > 0)
			foreach ($list as $i => $l)
			{
				$u = $user->getUserByID($l['userid']);
				$list[$i]['time'] = timeElapsed($l['timestamp']);
				$list[$i]['nickname'] = $u['nickname'];
				$list[$i]['text'] = $bbcode->parse($l['text']);
				$list[$i]['url'] = makeURL('profile', array('userid' => $l['userid']));
			}
			
			return $list;
		}
		
		function find($needle)
		{
			global $user, $bbcode;
			
			$sql = "SELECT * FROM `".$this->table."` WHERE `text` LIKE '%".secureMySQL($needle)."%' LIMIT 20;";
			$result = $this->db->query($sql);
			$list = array();
			while ($row = mysql_fetch_assoc($result)) {
				$u = $user->getUserByID($row['userid']);
				$l['time'] = timeElapsed($row['timestamp']);
				$l['nickname'] = $u['nickname'];
				$l['text'] = $bbcode->parse($row['text']);
				$l['mod'] = $row['mod'];
				$l['url'] = makeURL('profile', array('userid' => $row['userid']));
				$list[] = $l;
			}
			return $list;
		}
		
		function count($mod, $contentid)
		{
			return (int)$this->db->num_rows($this->table, "`mod`='" . secureMySQL($mod) . "' AND `contentid`=" . (int)$contentid);
		}
		
		function countAll()
		{
			return (int)$this->db->num_rows($this->table, "1");
		}
	}
	
?>