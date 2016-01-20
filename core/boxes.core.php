<?php

	/**
	 * Project: Higher For Hire
	 * File: boxes.core.php
	 *
	**/
	
	class Boxes {
		
		var $table;
		var $boxcounter = 0;
		
		function __construct() {
			$this->table = MYSQL_TABLE_PREFIX . 'boxes';
		}
		
		function setup() {
			global $db;
			$sql = "
				CREATE TABLE IF NOT EXISTS `".$this->table."` (
					`boxid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`title` VARCHAR( 63 ) NOT NULL , 
					`file` VARCHAR( 255 ) NOT NULL ,
					`position` VARCHAR( 6 ) NOT NULL ,
					`order` INT NOT NULL ,
					`visible` INT( 1 ) NOT NULL ,
					`requires_login` INT ( 1 ) NOT NULL ,
					`domainid` INT NOT NULL 
					) ENGINE = MYISAM ;";
			$db->query($sql);
		}
		
		function add($title, $file, $position, $order, $visible, $requires_login, $domainid = 0) {
			global $db;
			$db->insert($this->table,
				array('boxid', 'title', 'file', 'position', 'order', 'visible', 'requires_login', 'domainid'),
				array('NULL', "'".$title."'", "'".$file."'", "'".$position."'", (int)$order, (int)$visible, (int)$requires_login, (int)$domainid));
		}
		
		function edit($boxid, $title, $visible, $requires_login, $domainid = 0) {
			global $db;
			$db->update($this->table, 
				"`title`='".secureMySQL($title)."', `visible`=".(int)$visible.",
				`requires_login`=".(int)$requires_login.", `domainid` = ".(int)$domainid
				, "`boxid`=".(int)$boxid);
		}
		
		function remove($boxid) {
			global $db;
			$db->delete($this->table, "`boxid`=".(int)$boxid);
		}
		
		function move($boxid, $position, $order) {
			global $db;
			$db->update($this->table, "`position`='".secureMySQL($position)."', `order`=".(int)$order, "`boxid`=".(int)$boxid);
		}
		
		function getLeft() {
			global $db;
			return $db->selectList($this->table, "*", "`position`='left' AND `visible`=1 AND (`domainid` = 0 OR `domainid` = ".getCurrentDomainIndex().")", "`order` ASC");
		}
		
		function getRight() {
			global $db;
			return $db->selectList($this->table, "*", "`position`='right' AND `visible`=1 AND (`domainid` = 0 OR `domainid` = ".getCurrentDomainIndex().")", "`order` ASC");
		}
		
		function getAll() {
			global $db;
			return $db->selectList($this->table, "*", "1", "`position`, `order` ASC");
		}
		
		function listAvailable() {
			global $mod;
			
			$availableList = scandir('./boxes/');
			
			$returnList = array();
			
			if (null != $availableList && count($availableList) > 0) {
				foreach ($availableList as $i => $file) {
					if ($file != 'ad' && !$mod->isInstalled($file)) {
						unset($availableList[$i]);
					}
				}
			}
			
			if (null != $availableList && count($availableList) > 0) {
				foreach ($availableList as $file) {
					if (substr($file, 0, 1) != '.') {
						$returnList[] = $file;
					}
				}
			}
			
			return $returnList;
		}
		
		function run($file, &$template_file) {
			global $db, $login, $smarty, $lang, $stat, $user, $config, $favorites;
			$path = './boxes/'.$file.'/'.$file.'.box.php';
			if ($handle = @fopen ($path, "rb")) {
				$visible = false;
				$this->boxcounter++;
				$boxc = $this->boxcounter;
				$tpl_file = '';
				$template_dir = '../boxes/'.$file;
				include($path);
				$template_file = $tpl_file;
				return $visible;
			}
			else {
				$notify->raiseError('Boxes', 'Box <em>' . $file . '</em> not found!');
			}
		}
		
		function runAll() {
			global $box, $mod, $notify, $lang, $login;
			
			$left = $this->getLeft();
			if (null != $left && count($left) > 0) {
				foreach ($left as $l) {
					if ($mod->isInstalled($l['file']) && 
						($login->currentUser() !== false || $l['requires_login'] == 0) &&
						$l['visible'] == 1) {
						
						unset($b);
						if ($this->run($l['file'], $template_file)) {
							$b['file'] = $template_file;
							$b['title'] = $l['title'];
							$box['left'][] = $b;
						}
					}
				}
			}
			
			$right = $this->getRight();
			if (null != $right && count($right) > 0) {
				foreach ($right as $r) {
					if ($mod->isInstalled($r['file'])  && 
						($login->currentUser() !== false || $r['requires_login'] == 0) &&
						$r['visible'] == 1) {
						
						unset($b);
						if ($this->run($r['file'], $template_file)) {
							$b['file'] = $template_file;
							$b['title'] = $r['title'];
							$box['right'][] = $b;
						}
					}
				}
			}
		}
	}

?>