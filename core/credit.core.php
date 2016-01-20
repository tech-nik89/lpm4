<?php

	/**
	 * Project: Higher For Hire
	 * File: credit.core.php
	 *
	**/
	
	class Credit
	{
		
		private $table;
		
		function __construct()
		{
			$this->table = MYSQL_TABLE_PREFIX . 'credit';
		}
		
		function setup()
		{
			global $db;
			
			$sql = "
				
				CREATE TABLE IF NOT EXISTS `" . $this->table . "` (
					`creditid` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`userid` INT NOT NULL ,
					`timestamp` INT NOT NULL ,
					`description` VARCHAR (256) NOT NULL ,
					`value` INT NOT NULL
					) ENGINE = MYISAM ;
				
				";
				
			$db->query($sql);
			
		}
		
		function getMovements()
		{
			global $db;
			global $login;
			
			$result = $db->selectList($this->table, "*", "`userid`=".$login->currentUserID(), "`timestamp` DESC");
			
			if (count($result) > 0)
			foreach ($result as $i => $r)
				$result[$i]['time'] = timeElapsed($r['timestamp']);
			
			return $result;
		}
		
		function getBalance()
		{
			global $db;
			global $login;
			global $lang;
			
			$sql = "SELECT SUM(`value`) FROM `" . $this->table . "` WHERE `userid`=".$login->currentUserID();
			$result = $db->query($sql);
			$row = mysql_fetch_assoc($result);
			
			$balance = $row["SUM(`value`)"];
			if ($balance >= 0)
				$b = '<font color="#00AA00">+' . number_format($balance / 100, 2) . $lang->get('currency') . '</font>';
			else
				$b = '<font color="#FF0000">' . number_format($balance / 100, 2) . $lang->get('currency') . '</font>';
			
			return $b;
		}
		
		function getBalanceRaw()
		{
			global $db;
			global $login;
			
			$sql = "SELECT SUM(`value`) FROM `" . $this->table . "` WHERE `userid`=".$login->currentUserID();
			$result = $db->query($sql);
			$row = mysql_fetch_assoc($result);
			
			$balance = $row["SUM(`value`)"];
			return $balance;
		}
		
		function pay($value, $userid = 0, $payedto_userid = 0, $description = '') // (cent)$value
		{
			global $db;
			global $login;
			global $log;
			
			if ($userid == 0)
				$userid = $login->getCurrentUserId();
			
			// Check if user has enough credit
			if ($this->getBalanceRaw() >= (int)$value)
			{
				$time = time();
				$descr = $description;
				
				$db->insert($this->table, 
							array("userid", "timestamp", "description", "value"), 
							array($userid, $time, $descr, ((int)$value * -1))
							);
				
				if ((int)$payedto_userid > 0)
				{
					$db->insert($this->table, 
							array("userid", "timestamp", "description", "value"), 
							array($payedto_userid, $time, $descr, ((int)$value))
							);
				}
				
				$log->add('credit', 'payed ' . $value . ' (currency) from userid ' . $userid . ' to userid ' . $payedto_userid . ' (' . $description . ')');
				return true;
				
			} else {
				
				$log->add('credit', 'failed to pay ' . $value . ' (currency) (' . $description . ')');
				return false;
				
			}
		}
		
		function deposit($userid, $value, $description = '') // (cent)$value
		{
			global $db;
			global $log;
			
			$db->insert($this->table, 
						array("userid", "timestamp", "description", "value"), 
						array((int)$userid, time(), "'".secureMySQL($description)."'", (int)$value)
						);
			$log->add('credit', 'increased balance of user ' . $userid . ' by ' . $value . ' (currency)');
		}
		
		function getLastActivity()
		{
			global $db;
			global $login;
			
			$last = $db->selectOne($this->table, "timestamp", "`userid`=".$login->currentUserID(), "`timestamp` DESC");
			return $last;
		}
		
	}
	
?>