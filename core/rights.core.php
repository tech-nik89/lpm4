<?php
	
	/**
	 * Project: Higher For Hire
	 * File: rights.core.php
	 *
	**/
	
	class Rights
	{
		
		// table name
		private $table_rights;
		private $table_groups;
		private $table_group_rights;
		private $table_group_users;
		
		// references to some classes
		private $user;
		private $db;
		private $login;
		
		function __construct(&$user, &$db, &$login = null)
		{
			$this->user = $user;
			$this->db = $db;
			$this->login = $login;
			
			$this->table_rights = MYSQL_TABLE_PREFIX . 'rights';
			$this->table_groups = MYSQL_TABLE_PREFIX . 'groups';
			$this->table_group_rights = MYSQL_TABLE_PREFIX . 'group_rights';
			$this->table_group_users = MYSQL_TABLE_PREFIX . 'group_users';
		}
		
		// creates the required tables
		function setup()
		{
			// Create table hfh_rights
			$sql = "
			
				CREATE TABLE IF NOT EXISTS `" . $this->table_rights . "` (
				`name` VARCHAR (64) NOT NULL, 
				`mod` VARCHAR (64) NOT NULL ,
				`description` VARCHAR (64) NOT NULL
				) ENGINE = MYISAM ;
				
				";
			$this->db->query($sql);
			
			
			// create table hfh_groups
			$sql = "
				CREATE TABLE IF NOT EXISTS `" . $this->table_groups . "` (
				`groupid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`name` VARCHAR (64) NOT NULL ,
				`description` VARCHAR (256) NOT NULL ,
				`display` INT ( 1 ) NOT NULL,
				`admin` INT ( 1 ) NOT NULL
				) ENGINE = MYISAM ;
			";
			$this->db->query($sql);
			
			
			// create table hfh_group_rights 
			$sql = "
				CREATE TABLE IF NOT EXISTS `" . $this->table_group_rights . "` (
				`mod` VARCHAR (64) NOT NULL ,
				`name` VARCHAR (64) NOT NULL ,
				`groupid` INT NOT NULL
				) ENGINE = MYISAM ;";
			$this->db->query($sql);
			
			// create table hfh_group_users
			$sql = "
				 CREATE TABLE IF NOT EXISTS `" . $this->table_group_users . "` (
				`groupid` INT NOT NULL ,
				`userid` INT NOT NULL
				) ENGINE = MYISAM ";
			$this->db->query($sql);
		}
		
		function rightExists($mod, $name)
		{
			$sql = "SELECT * FROM `" . $this->table_rights . "` 
					WHERE `mod`='" . secureMySQL($mod) . "'
					AND `name`='" . secureMySQL($name) . "'
					LIMIT 1;";

			$result = $this->db->query($sql);
			
			if (mysql_num_rows($result) > 0)
				return true;
			else
				return false;
		}
		
		// registers a permission in the system
		function registerRight($mod, $name, $description = '')
		{
			global $log;
			
			if (!$this->rightExists($mod, $name))
			{
				$sql = 	"INSERT INTO `" . $this->table_rights . "`
						(`name`,  `mod`, `description`)
						VALUES
						('" . secureMySQL($name) . "', '" . secureMySQL($mod) . "', '" . secureMySQL($description) . "')
						;";
				
				$this->db->query($sql);
				
				// add new right to all groups with the admin flag
				$groups = $this->getAllAdminGroups();
				foreach ($groups as $group) {
					$this->assignRightToGroup($group['groupid'], $mod, $name);
				}
				
				$log->add('rights', 'registered right ( ' . $mod . ' | ' . $name . ' )');
			}
		}
		
		// unregisters a right
		function removeRight($mod, $name)
		{
			$sql = "DELETE FROM `" . $this->table_rights . "`
					WHERE `mod`='" . secureMySQL($mod) . "' AND `name`='" . secureMySQL($name) . "' LIMIT 1;";
			
			$this->db->query($sql);
			
			global $log;
			$log->add('rights', 'removed right ( ' . $mod . ' | ' . $name . ' )');
		}
		
		// creates a new group
		function createGroup($name, $description = '', $display = 0, $admin = 0)
		{
			if (trim($name) != '')
			{
				
				$sql = "INSERT INTO `" . $this->table_groups . "`
						(`groupid`, `name`, `description`, `display`, `admin`)
						VALUES
						(NULL, '" . secureMySQL($name) . "', '" . secureMySQL($description) . "', " . (int)$display . ", " . (int)$admin . ");";
				
				$this->db->query($sql);
				
				global $log;
				$log->add('rights', 'created group ' . $name);
				
				return mysql_insert_id();
				
			}
		}
		
		// removes a group
		function removeGroup($groupid)
		{
			if ((int)$groupid == 1)
				return;
			
			$sql = "DELETE FROM `" . $this->table_groups . "`
					WHERE `groupid`=" . (int)$groupid . ";";
			
			$this->db->query($sql);
			
			$sql = "DELETE FROM `" . $this->table_group_rights . "`
					WHERE `groupid`=" . (int)$groupid . ";";
		
			$this->db->query($sql);
			
			global $log;
			$log->add('rights', 'removed group ' . $groupid);
		}
		
		// edits a group
		function editGroup($groupid, $name, $description = '', $display = 0, $admin = 0)
		{
			$sql = "UPDATE `" . $this->table_groups . "`
					SET `name`='" . secureMySQL($name) . "',
						`description`='" . $description . "',
						`display` = ".(int)$display.",
						`admin` = ".(int)$admin."
					WHERE `groupid`=" . (int)$groupid . ";";
			
			$this->db->query($sql);
			
			global $log;
			$log->add('rights', 'updated group ' . $groupid . ' (newname = ' . $name . ')');
		}
		
		// assigns the right $name of the modul $mod to the group $groupid
		function assignRightToGroup($groupid, $mod, $name)
		{
			$sql = "SELECT * FROM `" . $this->table_group_rights . "` WHERE `groupid`=" . (int)$groupid . " AND `mod`='" . secureMySQL($mod) . "' AND `name`='" . secureMySQL($name) . "';";
			$result = $this->db->query($sql);
			
			if (mysql_num_rows($result) == 0)
			{
				$sql = "INSERT INTO `" . $this->table_group_rights . "`
						(`groupid`, `mod`, `name`)
						VALUES
						(" . (int)$groupid . ", '" . secureMySQL($mod) . "', '" . secureMySQL($name) . "');";
				
				$this->db->query($sql);
				
				global $log;
				$log->add('rights', 'assigned right ( ' . $mod . ' | ' . $name . ' ) to group ' . $groupid);
			}
		}
		
		// removes a right $name of the modul $mod from the group $groupid
		function removeRightFromGroup($groupid, $mod, $name)
		{
			if ((int)$groupid == 1 && $mod == 'admin' && $name == 'groups')
				return;
				
			$sql = "DELETE FROM `" . $this->table_group_rights . "`
					WHERE `groupid`=" . (int)$groupid . " AND `mod`='" . secureMySQL($mod) . "' AND `name`='" . secureMySQL($name) . "'
					LIMIT 1;";
					
			$this->db->query($sql);
			
			global $log;
			$log->add('rights', 'removed right ( ' . $mod . ' | ' . $name . ' ) from group ' . $groupid);
		}
		
		// assigns an user to a group
		function assignUserToGroup($groupid, $userid)
		{
			$sql = "SELECT * FROM `" . $this->table_group_users . "` WHERE `userid`=" . (int)$userid . " AND `groupid`=" . (int)$groupid . ";";
			$result = $this->db->query($sql);
			
			if (mysql_num_rows($result) == 0)
			{
			
				$sql = "INSERT INTO `" . $this->table_group_users . "`
						(`groupid`, `userid`)
						VALUES
						(" . (int)$groupid . ", " . (int)$userid . ");";
				$this->db->query($sql);
			
				global $log;
				$log->add('rights', 'assigned user ' . $userid . ' to group ' . $groupid);
			}
		}
		
		// remove an user from a group
		function removeUserFromGroup($groupid, $userid)
		{
			$sql = "DELETE FROM `" . $this->table_group_users ."`
					WHERE `groupid`=" . (int)$groupid . " AND `userid`=" . (int)$userid . " LIMIT 1;";
			
			$this->db->query($sql);
			
			global $log;
			$log->add('rights', 'removed user ' . $userid . ' from group ' . $groupid);
		}
		
		// check if
		function isAllowed($mod, $name, $userid = -1)
		{
			// if no user is selected, use current logged in user
			if ($userid == -1)
				$userid = $this->login->currentUserID();
				
			// in which group is the user member?
			$sql = "SELECT * FROM `" . $this->table_group_users . "`
					WHERE `userid`=" . (int)$userid . ";";
			
			$result = $this->db->query($sql);
			
			while ($group_row = mysql_fetch_assoc($result))
			{
				
				
				// has this group the required right?
				
				$sql_0 = "SELECT * FROM `" . $this->table_group_rights . "`
						WHERE `groupid`=" . (int)$group_row['groupid'] . "
						AND `name`='" . secureMySQL($name) . "'
						AND `mod`='" . secureMySQL($mod) . "';";
				
				$result_0 = $this->db->query($sql_0);
				
				// yes it has, that's enough - quit and return
				if (mysql_num_rows($result_0) > 0)
					return true;
			}
			
			return false;
			
		}
		
		function getGroups($userid, $display = 0)
		{
			$list = array();
			if ($display == 0) {
				$sql = "SELECT * 
						FROM `" . $this->table_group_users . "`, `" . $this->table_groups . "`
						WHERE `" . $this->table_group_users . "`.`userid`=" . (int)$userid . "
						AND `" . $this->table_groups . "`.`groupid`=`" . $this->table_group_users . "`.`groupid`
						ORDER BY `name` ASC;";
			}
			else {
				$sql = "SELECT * 
					FROM `" . $this->table_group_users . "`, `" . $this->table_groups . "`
					WHERE `" . $this->table_group_users . "`.`userid`=" . (int)$userid . "
					AND `" . $this->table_groups . "`.`groupid`=`" . $this->table_group_users . "`.`groupid`
					AND `display` = 1
					ORDER BY `name` ASC;";
			}
			
			$result = $this->db->query($sql);
			
			while ($row = mysql_fetch_assoc($result))
				$list[] = $row;
				
			return $list;
		}
		
		function getAllGroups()
		{
			$list = array();
			$sql = "SELECT * FROM `" . $this->table_groups . "` ORDER BY `name` ASC;";
			$result = $this->db->query($sql);
			while ($row = mysql_fetch_assoc($result))
				$list[] = $row;
			return $list;
		}
		
		function getAllAdminGroups() {
			$list = array();
			$sql = "SELECT * FROM `" . $this->table_groups . "` WHERE `admin` = 1 ORDER BY `name` ASC;";
			$result = $this->db->query($sql);
			while ($row = mysql_fetch_assoc($result))
				$list[] = $row;
			return $list;
		}
		
		function getRights($groupid)
		{
			$list = array();
			$sql  = "SELECT * FROM `" . $this->table_group_rights . "`
					WHERE `groupid`=" . (int)$groupid . "
					ORDER BY `mod` ASC;";
			
			$result = $this->db->query($sql);
			while ($row = mysql_fetch_assoc($result))
				$list[] = $row;
				
			return $list;
		}
		
		
		function getAllRights()
		{
			$list = array();
			$sql  = "SELECT * FROM `" . $this->table_rights . "`
					ORDER BY `mod` ASC;";
			
			$result = $this->db->query($sql);
			while ($row = mysql_fetch_assoc($result))
				$list[] = $row;
				
			return $list;
		}
		
		function getGroup($groupid)
		{
			$sql = "SELECT * FROM `" . $this->table_groups . "` WHERE `groupid`=" . (int)$groupid . " LIMIT 1;";
			$result = $this->db->query($sql);
			return mysql_fetch_assoc($result);
		}
		
		function isInGroup($groupid, $userid = -1)
		{
			if ($userid == -1)
				$userid = $this->login->currentUserId();
				
			$userid = (int)$userid;
			$groupid = (int)$groupid;
			
			$sql = "SELECT * FROM `" . $this->table_group_users . "` 
					WHERE `userid`=".$userid."
						AND `groupid`=".$groupid." LIMIT 1;";
			$result = $this->db->query($sql);
			if (mysql_num_rows($result) > 0)
				return true;
			else
				return false;
		}
		
		// Returns a list of users which have the passed right
		function getUsersByRight($mod, $name) {
			global $db;
			$userList = Array();
			$u = MYSQL_TABLE_PREFIX."users";
			$groups = $db->selectList($this->table_group_rights, "*", 
				"`mod`='".secureMySQL($mod)."' AND `name`='".secureMySQL($name)."'");
			
			foreach ($groups as $group) {
				$users = $db->selectList($this->table_group_users."`, `".$u, "*",
					"`".$this->table_group_users."`.`userid`=`".$u."`.`userid` AND
					`".$this->table_group_users."`.`groupid`=".$group['groupid']);
				foreach ($users as $user) {
					$userList[] = $user;
				}
			}
			
			return $userList;
		}
		
		function getGroupMembers($groupid) {
	       
            $sql = "SELECT * FROM `" . $this->table_group_users . "`, `".MYSQL_TABLE_PREFIX."users`
                WHERE `groupid`=".$groupid."
                AND `" . $this->table_group_users . "`.`userid` = `".MYSQL_TABLE_PREFIX."users`.`userid`;";
            $result = $this->db->query($sql);
			
			$return = array();
            while ($row = mysql_fetch_assoc($result)) {
                $return[] = $row;
            }
            return $return;
        }
	}
	
?>