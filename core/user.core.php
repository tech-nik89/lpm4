<?php

	/**
	 * Project: Higher For Hire
	 * File: user.core.php
	 *
	**/
	
	class User
	{
		// Pointer to database and language class
		private $db;
		
		// Contains the name of the table
		var $table;
		
		// Constructor
		function __construct(&$db_class)
		{
			// Set the database and language class pointer
			$this->db = $db_class;
			
			// name of the database-table
			$this->table = MYSQL_TABLE_PREFIX . 'users';
			
		}
		
		// creates the required table(s) for this class
		function setup()
		{
			$sql = "
			
			 CREATE TABLE IF NOT EXISTS `" . $this->table . "` (
				`userid` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`email` VARCHAR (64) NOT NULL,
				`password` VARCHAR (256) NOT NULL,
				`ipadress` VARCHAR (16) NOT NULL,
				`session_id` VARCHAR (26) NOT NULL,
				`nickname` VARCHAR (64) NOT NULL,
				`lastname` VARCHAR (64) NOT NULL,
				`prename` VARCHAR (64) NOT NULL, 
		 	 	`birthday` INT NOT NULL, 
				`regdate` INT NOT NULL,
				`lastaction` INT NOT NULL, 
				`template` VARCHAR (64) NOT NULL, 
				`comment` VARCHAR (256) NOT NULL, 
				`avatar` VARCHAR (128) NOT NULL,
				`ban` INT (1) NOT NULL, 
				`activated` INT ( 1 ) NOT NULL , 
				`activation_key` VARCHAR (64) NOT NULL ,
				`company` VARCHAR ( 255 ) NOT NULL ,
				`address` VARCHAR ( 1023 ) NOT NULL
				) ENGINE = MYISAM 
			
			
			";
			
			$this->db->query($sql);
		}
		
		// Returns an array containing the found user.
		function getUserByID($userid)
		{
			$sql = "SELECT * FROM `" . $this->table . "` WHERE `userid`=" . (int)$userid . " LIMIT 1;";
			$result = $this->db->query($sql);
			
			if (mysql_num_rows($result)> 0)
			{
				$row = mysql_fetch_assoc($result);
				return $row;
			} else
				return false;
		}
		
		// Returns an array containing the found user.
		function getUserByEMail($email)
		{
			$m_email = secureMySQL($email);
		
			$result = $this->db->query("SELECT * FROM `" . $this->table . "` WHERE `email`='" . $m_email . "' LIMIT 1;");
			
			if (mysql_num_rows($result)> 0)
			{
				$row = mysql_fetch_assoc($result);
				return $row;
			} else
				return false;
		}
		
		// Returns true if user already exists, otherwise returns false
		function userExists($email, $nickname)
		{
			$m_nickname = secureMySQL($nickname);
			$m_email = secureMySQL($email);
			
			// create sql query
			$sql = "SELECT `email`, `nickname` FROM `" . $this->table . "` 
					WHERE `email`='" . $m_email . "' OR `nickname`='" . $m_nickname . "' LIMIT 1;";
			
			if (mysql_num_rows($this->db->query($sql)) > 0)
				return true;
			else
				return false;
			
		}
		
		// Creates a new user
		function createUser($email, $password, $nickname, $lastname, $prename, $birthday, $company = '', $address = '')
		{
		
			/* return-codes
			 * 0 = success
			 * 1 = user exists
			 * 2 = something went terribly wrong
			 */
			 
			 global $config;
			 global $log;
			 global $notify;
			 global $lang;
			 global $eMail;
			
			if (trim($email) == '' || trim($password) == '' || trim($nickname) =='' || trim($lastname) == '' || trim($prename) == '' || $birthday == 0)
				return 2;
			
			// Escape strings and strip html tags
			$m_nickname = secureMySQL($nickname);
			$m_email = secureMySQL($email);
			
			$m_password = generatePasswordHash(secureMySQL($password));
			
			$m_company = secureMySQL($company);
			$m_address = secureMySQL($address);
			
			$key = calculateActivationKey($m_email, $m_nickname, $m_password, secureMySQL($prename));
			
			// Check if user already exists and create, otherwise notify
			if (!$this->userExists($m_email, $m_nickname))
			{
				// Read mail config values
				$activation_required = $config->get('login', 'register-activation-required');
				$send_mail = $config->get('login', 'register-send-email');
				
				if ($activation_required == 1 && $_GET['mod'] != 'admin')
					$activated = 0;
				else
					$activated = 1;
				
				// create query
				$sql = "INSERT INTO `" . $this->table . "`
						(`userid`, 	`email`, 			`password`, 			`nickname`		,		`regdate`,
						`lastname`, `prename`, `birthday`, `activation_key`, `activated`, `company`, `address`)
						VALUES
						(NULL, 		'" . $m_email . "',	'" . $m_password . "', 	'" . $m_nickname . "',		" . time() . ",
						'" . secureMySQL($lastname) . "', '" . secureMySQL($prename) . "', " . $birthday . ",
						'" . $key . "', " . $activated . ", '".$m_company."', '".$m_address."');";
				
				if ($activation_required == 1 || $send_mail == 1)
				{
					// check if user is created by admin module
					if ($_GET['mod'] != 'admin' || @$_POST['email_send'] == '1') {
						$subject = $config->get('login', 'register-mail-subject');
						$text = $config->get('login', 'register-mail-text');
						$sender = $config->get('login', 'register-mail-sender');
						$url = getSelfURL().'/'.makeURL('login', array('mode' => 'unlock', 'key' => $key));

						$text = str_replace(
								array("%key%", "%nickname%", "%prename%", "%lastname%", "%url%", "\r\n"), 
								array($key, $m_nickname, $prename, $lastname, $url, "<br />"),
								$text);
						
						@$mail_sent = $eMail->send($subject, $text, $m_email);
						
						if ($mail_sent)
						{
							$log->add('register', 'mail successfully sent to ' . $m_email);
							$notify->add($lang->get('register'), $lang->get('mail_sent'));
						} else {
							$log->add('register', 'mail to ' . $m_email . ' failed');
							$notify->add($lang->get('register'), $lang->get('mail_error'));
						}
					}
				}
				
				// query
				$this->db->query($sql);
				
				$log->add('user', 'created user ' . $nickname . ' (' . $email . ')');
				
				return 0;
			}
			else
				return 1;
			
		}
		
		function updateUser($userid, $email, $nickname, $lastname, $prename, $comment = '', $birthday = 0, $ban = 0, $activated = 0, $company = '', $address = '')
		{
			$m_company = secureMySQL($company);
			$m_address = secureMySQL($address);
			
			$sql = "UPDATE `" . $this->table . "` SET 
					`email`='" . secureMySQL($email) . "', `nickname`='" . secureMySQL($nickname) . "',
					`lastname`='" . secureMySQL($lastname) . "', `prename`='" . secureMySQL($prename) . "',
					`comment`='" . secureMySQL($comment) . "', `birthday`=" . (int)$birthday . ",
					`ban`=" . (int)$ban . ",
					`activated`=" . (int)$activated . ",
					`company`='".$m_company."',
					`address`='".$m_address."'
					WHERE `userid`=" . (int)$userid . ";";
			
			$this->db->query($sql);
			
			global $log;
			$log->add('user', 'updated user ' . $nickname . ' (' . $email . ')');
		}
		
		function setAvatar($userid, $avatar)
		{
			$sql = "UPDATE `" . $this->table . "` SET `avatar`='" . secureMySQL($avatar) . "' WHERE `userid`=" . (int)$userid . ";";
			$this->db->query($sql);
			
			global $log;
			$log->add('user', 'updated avatar to ' . $avatar . ' of user ' . $userid);
		}
		
		// changes the password of an user
		function changePassword($userid, $new_password)
		{

			$m_new_password = generatePasswordHash(secureMySQL($new_password));
			
			$sql = "UPDATE `" . $this->table . "` SET `password`='" . $m_new_password . "'
					WHERE `userid`=" . (int)$userid . ";";
			
			// query
			$this->db->query($sql);
			
			global $log;
			$log->add('user', 'userpassword of user ' . $userid . ' changed');
		}
		
		
		
		// Removes an user
		function removeUser($userid)
		{
			if ($userid == 1)
				return;
				
			$sql = "DELETE FROM `" . $this->table . "` WHERE `userid`=" . $userid . ";";
			$this->db->query($sql);
			
			global $log;
			$log->add('user', 'user ' . $userid . ' removed');
		}
		
		function listUsers($start = 0, $limit = 0, $order = "nickname")
		{
			if ((int)$start > 0)
				$sql = "SELECT * FROM `" . $this->table . "`
					ORDER BY " . secureMySQL($order) . "
					LIMIT " . (int)$start . ", " . (int)$limit . ";";
			else {
				if ((int)$limit > 0)
					$limit = ' LIMIT ' . (int)$limit;
				else
					$limit = '';
				$sql = "SELECT * FROM `" . $this->table . "`
					ORDER BY " . secureMySQL($order) . $limit . ";";
			}
					
			$result = $this->db->query($sql);
			
			while ($row = mysql_fetch_assoc($result))
				$list[] = $row;
			
			return $list;
			
		}
		
		function find($needle, $limit = "")
		{
			
			if ($limit != "")
				$limit = " LIMIT " . (int)$limit;
		
			$sql = "SELECT * FROM `" . $this->table  . "` WHERE
					INSTR(`nickname`, '" . secureMySQL($needle) . "') > 0 OR
					INSTR(`prename`, '" . secureMySQL($needle) . "') > 0 OR
					INSTR(`email`, '" . secureMySQL($needle). "') >0" . secureMySQL($limit) . ";";
					
			$result = $this->db->query($sql);
			
			if (mysql_num_rows($result) == 0)
				return false;
			
			while ($row = mysql_fetch_assoc($result))
				$list[] = $row;
			
			return $list;
		}
		
		function count($where = "1")
		{
			$sql = "SELECT * FROM `" . $this->table . "` WHERE " . $where . ";";
			$result = $this->db->query($sql);
			return mysql_num_rows($result);
		}
		
		function activate($key)
		{
			global $notify;
			global $lang;
			
			$sql = "UPDATE `" . $this->table . "` SET `activated`=1 WHERE `activation_key`='" . secureMySQL($key) . "';";
			$this->db->query($sql);
			
			$notify->add($lang->get('login'), $lang->get('account_activated'));
			
		}
		
	}
	
?>