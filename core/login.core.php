<?php

	/**
	 * Project: Higher For Hire
	 * File: login.core.php
	 *
	**/
	
	class Login
	{
		// pointer to the db class
		private $db;
		private $user;
		
		// user logged in
		private $cUser;
		
		function __construct(&$db_class, &$user_class)
		{
			$this->db = $db_class;
			$this->user = $user_class;
			$this->cUser = false;
		}
		
		function setup()
		{
			global $config;
			$config->register('core', 'save-login-disabled', 0, 'bool', 'Enables or disables if an user can stay logged in.');
		}
		
		function doLogin($email, $password, $save_login, $period = 86400)
		{
			global $log;
			global $config;
			global $user;
			
			$email = secureMySQL($email);
			
			if ($this->passwordValid($email, $password))
			{
				$u = $user->getUserByEmail($email);
				if ( $u['activated'] == 1 || $config->get('login', 'register-activation-required') == 0 )
				{
					
					$_SESSION['email'] = $email;
					$_SESSION['password'] = $password;
					
					if ($save_login == '1' && $config->get('core', 'save-login-disabled') == 0) {
						setcookie('hfh_email', $email, time() + 31536000);
						setcookie('hfh_password', $password, time() + 31536000);
					}
					
					$log->add('login', 'valid login by ' . $email);
					return true;
					
				}
			}
			else
			{
				$log->add('login', 'login by ' . $email . ' failed');
				return false;
			}
		}
		
		function logout()
		{
			global $log;
			$log->add('logout', 'logout by ' . @$_SESSION['email']);
			
			$this->cUser = false;
			$_SESSION['email'] = '';
			$_SESSION['password'] = '';
			session_destroy();
			@setcookie('hfh_email', '', time() - 1);
			@setcookie('hfh_password', '', time() -1 );
		}
		
		function updateLastSeen()
		{
			
			$sql = "UPDATE `" . $this->user->table . "`
					SET `lastaction`=" . time() . ", `session_id`='" . session_id() . "', `ipadress`='" . getRemoteAdr() . "'
					WHERE `userid`=" . (int)$this->cUser['userid'] . ";";
			
			$this->db->query($sql);
		}
		
		function passwordValid($email, $password)
		{
			// when cookies are empty, we doesn't have to check the db
			if (trim($email) != '' and trim($password) != '')
			{
			
				// read user from db
				$user = $this->user->getUserByEmail($email);
				
				// if user is false, user doesn't exist
				if ($user == false)
					return false;
				else
				{
					if ($user['password'] == $password)
						return true;
					else
						return false;
				}
				
			}
			else
				return false;
		}
		
		private function isLoggedIn()
		{
			global $log;
			// if session vars are empty, look if they are stored in cookies
			if (@$_SESSION['email'] == '' && @$_COOKIE['hfh_email'] != '') {
				$_SESSION['email'] = $_COOKIE['hfh_email'];
				$log->add('login', 'restored login data from cookie. [ email = '.$_COOKIE['hfh_email'].' ]');
			}
			if (@$_SESSION['password'] == '' && @$_COOKIE['hfh_password'] != '') {
				$_SESSION['password'] = $_COOKIE['hfh_password'];
				$log->add('login', 'restored login data from cookie. [ password ]');
			}
			
			$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
			$password = isset($_SESSION['password']) ? $_SESSION['password'] : '';
			
			if (trim($email) != '' and trim($password) != '')
			{
			
				// read user from db
				$user = $this->user->getUserByEmail($email);
				
				// if user is false, user doesn't exist
				if ($user == false)
					return false;
				else
				{
					// check if password is valid
					if ($user['password'] == $password && $user['ban'] == 0)
					{
						// password is valid, so put user into currentUser var
						$this->cUser = $user;
						return true;
					}
				}
				
			}
			else
				return false;
			
		}
		
		// returns the current user
		function currentUser()
		{
			
			if ($this->cUser == false)
				$this->isLoggedIn();
				
			return $this->cUser;
		
		}
		
		// returns just the id of the current user
		function currentUserID()
		{
			if ($this->cUser == false)
				$this->isLoggedIn();
				
			$temp = $this->cUser;
			
			if ($temp != false)
				return $temp['userid'];
			else
				return 0;
		}
		
	}

?>