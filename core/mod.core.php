<?php
	
	/**
	 * Project: Higher For Hire
	 * File: mod.core.php
	 *
	**/
	

	class Mod {
		var $modpath = 'mod';
		var $table;
		
		function __construct() {
			$this->table = MYSQL_TABLE_PREFIX . 'mod';
		}
		
		function modExists($mod) {
			global $mobile;
			
			$path = './' . $this->modpath . '/' . $mobile . '/' . $mod . '/';
			$path2 = './' . $this->modpath . '/default/' . $mod . '/' . $mod . '.mod.php';
			if (is_dir($path) && $handle = @fopen ($path2, "rb"))
				return true;
			else
				return false;
		}
		
		private function setupFileExists($mod) {
			$path = './' . $this->modpath . '/default/' . $mod . '/' . $mod . '.setup.php';
			
			if ($handle = @fopen ($path, "rb"))
				return $path;
			else 
				return false;
		}
		
		function run($mod) {
			global $user;
			global $menu;
			global $login;
			global $smarty;
			global $notify;
			global $lang;
			global $breadcrumbs;
			global $rights;
			global $config;
			global $db;
			global $avatar;
			global $pages;
			global $blowfish;
			global $bbcode;
			global $comments;
			global $content;
			global $credit;
			global $log;
			global $backup;
			global $favorites;
			global $eMail;
			
			global $debug;
			global $template_dir;
			global $mod_dir;
				
			//$path = './' . $this->modpath . '/' . $mobile . '/' . $mod . '/' . $mod . '.mod.php';
			$path = './' . $this->modpath . '/default/' . $mod . '/' . $mod . '.mod.php';
		
			//$mod_dir = './' . $this->modpath . '/' . $mobile . '/' . $mod . '/';
			$mod_dir = './' . $this->modpath . '/default/' . $mod . '/';
			
			global $mobile;
			$template_dir = '../' . $this->modpath . '/' . $mobile . '/' . $mod;
			
			include($path);
		}
		
		function isInMenu($mod) {
			global $menu, $config;
			$exclusion = explode(';', $config->get('core', 'link-mod-to-menu-exclusions'));
			if (count($exclusion) > 0) {
				foreach ($exclusion as $ex) {
					if ($ex == $mod)
						return true;
				}
			}
			$m = $menu->getMenu();
			foreach (@$m as $e) {
				if ($this->checkIfIsInMenu($e, $mod))
					return true;
			}
			return false;
		}
		
		function checkIfIsInMenu($e, $mod) {
			if (@$e['mod'] == $mod) {
				return true;
			}
			if (@count($e['children']) > 0) {
				foreach ($e['children'] as $child) {
					if ($this->checkIfIsInMenu($child, $mod))
						return true;
				}
			}
		}
		
		function listAvailable() {
			global $debug;
			global $mobile;
			
			$out = '';
			
			// scan mod directory
			$path = './' . $this->modpath . '/default/';
			$list = scandir($path);
			
			$installed = $this->listInstalled();
			
			// remove . and ..
			foreach ($list as $val) {
				if ($val != '.'
					&& $val != '..'
					&& $val != '404'
					&& $val != '.svn') {
					$found = false;
					foreach ($installed as $i) {
						if ($i['mod'] == $val) {
							$found = true;
							break;
						}
					}
					if (!$found) {
						$t['mod'] = $val;
						$t['version'] = $this->getVersion($val);
						$out[] = $t;
						unset($t);
					}
				}
			}
			return $out;
		}
		
		function listInstalled() {
			global $db;
			
			$list = $db->selectList($this->table, "`mod`", "1", "`mod` ASC");
			
			if (count($list) > 0)
				foreach ($list as $i => $l)
				{
					$tmp['mod'] = $l['mod'];
					$tmp['version'] = $this->getVersion($l['mod']);
					$o[] = $tmp;
					unset($tmp);
				}
			
			return $o;
		}
		
		
		function installMod($module) {
			global $db;
			global $config;
			global $rights;
			global $lang;
			global $notify;
			
			$exists = $db->num_rows($this->table, "`mod`='" . $module . "'");
			
			if ($exists == 0) {
				$sql = "INSERT INTO `" . $this->table . "`
						(`mod`)
						VALUE
						('" . secureMySQL($module) . "');";
						
				$db->query($sql);
				
				$path = $this->setupFileExists($module);
				if ($path != '')
					include_once($path);
					
				$notify->add($lang->get('mods'), $lang->get('mod_installed'));
				
				global $log;
				$log->add('mod', 'mod ' . $module . ' installed');
			}
			
		}
		
		function uninstallMod($module) {
			global $db;
			global $notify;
			global $log;
			global $lang;
			
			$sql = "DELETE FROM `" . $this->table . "` WHERE `mod`='" . secureMySQL($module) . "';";
			$db->query($sql);
			
			
			$log->add('mod', 'mod ' . $module . ' removed');
			
			$notify->add($lang->get('mods'), $lang->get('mod_uninstalled'));
		}
		
		function getVersion($mod) {
			global $mobile;
			$path = './mod/'.$mobile.'/'.$mod.'/'.$mod.'.version.php';
			if ($h = @fopen($path, "rb")) {
				fclose($h);
				include($path);
				return $version['major'].".".$version['minor'].".".$version['revision'];
			}
		}
		
		function isInstalled($mod) {
			global $db;
			return $db->num_rows($this->table, "`mod`='".secureMySQL($mod)."'") > 0;
		}
	}

?>