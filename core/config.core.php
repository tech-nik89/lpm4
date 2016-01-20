<?php

	/**
	 * Project: Higher For Hire
	 * File: config.core.php
	 *
	**/
	
	class Config
	{
		// Pointer to database class
		private $db;
		
		// Contains the name of the table
		private $table;
		
		// Current configuration
		private $config;
		
		// Constructor
		function __construct(&$db_class)
		{
			// Set the database class pointer
			$this->db = $db_class;
			 
			// Set config table
			$this->table = MYSQL_TABLE_PREFIX . 'config';
			 
			global $setup_running;
			if ($setup_running != true)
			{
				// Read current configuration from db
				$cfg = $this->db->selectList($this->table);
				 
				foreach ($cfg as $c)
					$this->config[$c['mod']][$c['key']] = $c['value'];
			}
		}
		
		// creates the required table(s) for this class
		function setup()
		{
			$sql = "
			
			 CREATE TABLE IF NOT EXISTS `" . $this->table . "` (
				`mod` VARCHAR (64) NOT NULL ,
				`key` VARCHAR (64) NOT NULL ,
				`value` VARCHAR (512) NOT NULL,
				`type` VARCHAR (64) NOT NULL, 
				`description` VARCHAR (256) NOT NULL
				) ENGINE = MYISAM 
			
			";
			
			$this->db->query($sql);
			
			$this->register('core', 'title', 'Higher For Hire', 'string', 'Sets the title of the home page.');
		}
		
		
		// updates a config-value
		function set($mod, $key, $value)
		{
			$sql = "UPDATE `" . $this->table . "`
					SET `value`='" . ($value) . "'
					WHERE `mod`='" . secureMySQL($mod) . "'
					AND	  `key`='" . secureMySQL($key) . "';";
			
			$this->db->query($sql);
			
			global $log;
			$log->add('config', 'set ( mod = ' . $mod . ' , key = ' . $key . ', value = ' . $value . ' )');
			
			$this->config[$mod][$key] = $value;
		}
		
		// reads a value and returns it
		function get($mod, $key)
		{
			if (isset($this->config[$mod][$key]))
				return $this->config[$mod][$key];
			else
				return false;
		}
		
		// registers a new config-key
		function register($mod, $key, $default, $type = "", $description = "", $list = "")
		{
			// check if key already exists
			if ($this->keyExists($mod, $key) == false)
			{
				
				$sql = "INSERT INTO `" . $this->table . "`
						(`mod`, `key`, `value`, `type`, `description`)
						VALUES
						('" . secureMySQL($mod) . "', '" . secureMySQL($key) . "', 
						'" . secureMySQL($default) . "', '" . secureMySQL($type) . "', 
						'" . secureMySQL($description) . 
						(trim($list) != "" ? '|' . secureMySQL($list) : '') . "');";

				$this->db->query($sql);
				
				global $log;
				$log->add('config', 'register ( mod = ' . $mod . ' , key = ' . $key . ', type = ' . $type . ', value = ' . $default . ' )');
				
				$this->config[$mod][$key] = $default;
			}
		}
		
		function getCount($mod)
		{
			$sql = "SELECT * FROM `" . $this->table . "` WHERE `mod`='" . secureMySQL($mod) . "';";
			$result = $this->db->query($sql);
			return mysql_num_rows($result);
		}
		
		function getConfigList($mod)
		{
			$sql = "SELECT * FROM `" . $this->table . "` WHERE `mod`='" . secureMySQL($mod) . "' ORDER BY `key` ASC;";
			$result = $this->db->query($sql);
			
			while ($row = mysql_fetch_assoc($result)) {
				if ($row['type'] == 'list') {
				$pos = strpos($row['description'], '|');
					if ($pos !== false) {
						$row['list'] = explode(',', substr($row['description'], $pos + 1));
						$row['description'] = substr($row['description'], 0, $pos);
					}
				}
				$o[] = $row;
				$pos = 0;
			}
			
			return $o;
		}
		
		function keyExists($mod, $key)
		{
			if ($this->db->num_rows($this->table, "`mod`='" . $mod . "' AND `key`='" . $key . "'") > 0)
				return true;
			else
				return false;
		}
	}
	
?>