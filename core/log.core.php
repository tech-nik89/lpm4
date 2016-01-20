<?php
	
	/**
	 * Project: Higher For Hire
	 * File: avatar.core.php
	 *
	**/
	
	class Log
	{
		private $table;
		private $file_enabled;
		private $mysql_enabled;
		
		private $path;
		private $dir;
		
		function __construct()
		{
			global $config;
			
			$this->table = MYSQL_TABLE_PREFIX . "log";
			
			$this->file_enabled = (int)$config->get('core', 'log_file_enabled');
			$this->mysql_enabled = (int)$config->get('core', 'log_mysql_enabled');
			
			$this->dir = './log/';
			
			if ($config->get('core', 'log-crypt-key') == '')
				$this->path = './log/'.date("Ymd").'.log';
			else
				$this->path = './log/'.date("Ymd").'-'.$this->createLogFileNameHash().'.log';
		}
	
		function getTable()
		{
			return $this->table;
		}
		
		function fileIsEnabled()
		{
			if ($this->file_enabled == 1)
				return true;
			else	
				return false;
		}
	
		function mysqlIsEnabled()
		{
			if ($this->mysql_enabled == 1)
				return true;
			else	
				return false;
		}
	
		function setup()
		{
			global $config;
			global $db;
			
			$config->register('core', 'log_file_enabled', 1, 'bool', 'Enables or disables the file version of the log.');
			$config->register('core', 'log_mysql_enabled', 0, 'bool', 'Enables or disables the database version of the log.');
			$config->register('core', 'log-crypt-key', 'hfh', 'string', 'Specifies the crypt key for the log file names.');
			
			$sql = "
			
				CREATE TABLE IF NOT EXISTS `" . $this->table . "` (
					`logid` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`userid` INT NOT NULL ,
					`timestamp` INT NOT NULL ,
					`mod` VARCHAR (64) NOT NULL ,
					`description` VARCHAR (256) NOT NULL
					) ENGINE = MYISAM ;

				";
				
			$db->query($sql);
		}
		
		function add($mod, $description)
		{
			global $login;
			
			if ($this->file_enabled == 1)
			{
				
				$h = fopen($this->path, 'a');
				fwrite($h, '[ ' . date("d.m.Y") . " | " . date("H:i.s") . " ] userid = " . $this->makeSpace($login->currentUserID(), 4) . " mod = " . $this->makeSpace($mod, 14) . "  " . $description . "\r\n");
				fclose($h);
				
			}
			
			if ($this->mysql_enabled == 1)
			{
				
				global $db;
				$db->insert($this->table, array('userid', 'timestamp', 'mod', 'description'),
											array($login->currentUserID(), time(), "'".secureMySQL($mod)."'", "'".secureMySQL($description)."'"));
				
			}
			
		}
		
		function createLogFileNameHash()
		{
			global $config;
			return md5($config->get('core', 'config-crypt-key')."#~//5x;".date("Ymd"));
		}
		
		private function makeSpace($input, $length)
		{
			$out = '';
			$x = $length - strlen($input);
			for ($i = 1; $i < $x; $i++)
				$out .= ' ';
				
			$out = $input . $out;
			return $out;
		}
		
		function listFileLogs()
		{
			$list = scandir($this->dir);
			$lst = array();
			if (count($list) > 0 && $list !== false)
			foreach($list as $i => $l)
			{
				if (substr($l, 0, 1) != '.')
				{
					$entry['name'] = $l;
					$entry['url'] = $this->dir . $l;
					$lst[] = $entry;
					unset($entry);
				}
			}
			return $lst;
		}
	}
	
?>