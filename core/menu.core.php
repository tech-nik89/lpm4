<?php

	/**
	 * Project: Higher For Hire
	 * File: menu.core.php
	 *
	**/
	
	class Menu {
		// Pointer to database and login class
		private $db;
		private $login;

		// Contains the name of the table
		private $table;
		
		// contains the sub menu
		private $subMenu;
		public $active_menu_entry;
		
		function __construct(&$db_class, &$login_class = null) {
			// Set the database and language class pointer
			$this->db = $db_class;
			$this->login = $login_class;
			
			// name of the database-table
			$this->table = MYSQL_TABLE_PREFIX . 'menu';
		}
		
		function setup() {
			$sql = "
			 CREATE TABLE IF NOT EXISTS `" . $this->table . "` (
				`menuid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`parentid` INT NOT NULL , 
				`order` INT NOT NULL ,
				`title` VARCHAR (64) NOT NULL ,
				`mod` VARCHAR (64) NOT NULL , 
				`requires_login` INT(1) NOT NULL , 
				`assigned_groupid` INT NOT NULL , 
				`language` VARCHAR ( 4 ) NOT NULL ,
				`url` VARCHAR (511) NOT NULL , 
				`home` INT ( 1 ) NOT NULL ,
				`template` VARCHAR ( 255 ) NOT NULL ,
				`domainid` INT NOT NULL 
				) ENGINE = MYISAM ";
			$this->db->query($sql);
		}
		
		function getMenu($parentid = 0, $view_menu = null, $grl = null, $submenu = true) {
			global $rights;
			global $current_language;
			global $config;
			
			if ($view_menu === null) {
				$view_menu = $rights->isAllowed('admin', 'config') === true ? true : false;
			}
			
			$lng = secureMySQL($current_language);
			$menu = Array();
			
			if ($this->login->currentUser() !== false) {
				if ($grl === null) {
					$gr = array_row($rights->getGroups($this->login->currentUserID()), 'groupid');
					$gr[] = 0;
					$gr[] = -1;
					$grl = '`assigned_groupid` IN ('.implode(', ', $gr).')';
				}
				
				$sql = "SELECT * FROM `" . $this->table . "`
					WHERE " . $grl . "
					AND `parentid`=".(int)$parentid."
					AND (`language` = '".$lng."' OR `language` = '')
					AND (`domainid` = '".getCurrentDomainIndex()."' OR `domainid` = '')
					ORDER BY `order` ASC;";
			}
			else {
				$sql = "SELECT * FROM `" . $this->table . "`
					WHERE (`requires_login`=0 AND `assigned_groupid` IN (0, -1)) 
					AND `parentid`=".(int)$parentid."
					AND (`language` = '".$lng."' OR `language` = '')
					AND (`domainid` = '".getCurrentDomainIndex()."' OR `domainid` = '')
					ORDER BY `order` ASC;";
			}
			
			$result = $this->db->query($sql);
			
			while ($row = mysql_fetch_assoc($result)) {
				if ($config->get('core', 'maintenance') == 0 || $view_menu || $row['mod'] == 'login') {
					if ($row['mod'] != '---') {
						$entry = array();
						
						if ($row['mod'] != 'url') {
							$url = makeURL($row['mod']);
						}
						else {
							$url = $row['url'];
						}
						
						$children = $submenu ? $this->getSubMenu($row) : array();
						$children = array_merge($children, $this->getMenu($row['menuid'], $view_menu, $grl, $submenu));
						
						@$active = $_GET['mod'] == $row['mod'] || ($_GET['key'] == $row['mod'] && $_GET['mod'] != 'admin');
						
						@$entry = array(
							'title' => $row['title'],
							'url' => $url,
							'mod' => $row['mod'],
							'children' => $children,
							'active' => $active,
							'template' => $row['template']
						);
						
						if ($active) {
							$this->active_menu_entry = $entry;
						}
						
						if ($row['assigned_groupid'] > -1)
							$menu[] = $entry;
					}
				}
			}
			return $menu;
		}
		
		function getSubMenu($mod = '') {
			$subMenu = array();
			if ($mod != '') {
				if (count($this->subMenu) > 0) {
					foreach ($this->subMenu as $m) {
						if ($m['mod'] == $mod['mod']) {
							$m['parentid'] = $mod['parentid'];
							$m['subitem'] = true;
							$subMenu[] = $m;
						}
					}
				}
			}
			else {
				$subMenu = $this->subMenu;
			}
			return $subMenu;
		}
		
		function addSubElement($mod, $title, $mode, $additional_parameters = false) {
			if ($additional_parameters !== false)
			{
				$p = $additional_parameters;
				$p['mode'] = $mode;
			} else
				$p = array('mode' => $mode);
			
			$this->subMenu[] = array(
						'mod' => $mod,
						'title' => $title,
						'url' => makeURL($mod, $p),
						'submenu' => true
							);
		}
		
		function getMenuRaw($parentid = 0, $language = '', $domainid = 0) {
			$domain_filter = "";
			if ($domainid > 0) {
				$domain_filter = "AND (`d`.`domainid` = 0 OR `d`.`domainid`= ".(int)$domainid.")";
			}
			
			if ($language == '') {
				$sql = "SELECT * FROM `" . $this->table . "` AS `m`
						LEFT JOIN `".MYSQL_TABLE_PREFIX."domains` AS `d`
						ON d.domainid = m.domainid
						WHERE `parentid`=".(int)$parentid."
						".$domain_filter."
						ORDER BY `order`;";
			}
			else {
				$sql = "SELECT * FROM `" . $this->table . "` AS `m`
						LEFT JOIN `".MYSQL_TABLE_PREFIX."domains` AS `d`
						ON d.domainid = m.domainid
						WHERE `parentid`=".(int)$parentid."
						AND `language`='".secureMySQL($language)."'
						".$domain_filter."
						ORDER BY `order`;";
			}
			$result = $this->db->query($sql);
			$list = array();
			
			while ($row = mysql_fetch_assoc($result)) {
				$row['children'] = $this->getMenuRaw($row['menuid'], $language);
				$row['edit_url'] = makeURL('admin', array('mode' => 'menu', 'action' => 'edit', 'menuid' => $row['menuid']));
				$list[] = $row;
			}
			return $list;
		}
		
		function getMaxOrder() {
             // get highest value
			$sql = "SELECT `order` FROM `" . $this->table . "` ORDER BY `order` DESC LIMIT 1;";
			$result = $this->db->query($sql);
			$row = mysql_fetch_assoc($result);
			
			// current max index
			$max = $row['order'];
			return $max;
        }
		
		function addUrl($title, $url, $requires_login = 0, $assigned_groupid = 0, $parentid = 0, $language = '') {

			// increase by one
			$order = $this->getMaxOrder() + 1;
		
			// insert new menu entry
			$sql = "INSERT INTO `" . $this->table . "`
					(`menuid`, 	`order`, 		`title`, 						`mod`, 							`requires_login`, `assigned_groupid`, `url`, `parentid`, `language`)
					VALUES
					(NULL, 		" . $order . ", '" . secureMySQL($title) . "', 	'url', " . (int)$requires_login . ", " . (int)$assigned_groupid . ", '" . secureMySQL($url) . "', " . (int)$parentid . ", '" . secureMySQL($language). "')";
			
			$this->db->query($sql);
        }
		
		function addElement($title, $mod, $requires_login = 0, $assigned_groupid = 0, $parent_id = 0, $language = '', $startpage = 0, $template = '', $domainid = 0) {
			
			if ($startpage > 0)
				$this->db->update($this->table, '`home`=0', "`language`='".secureMySQL($language)."' AND `domainid`=".(int)$domainid);
			
			// increase by one
			$order = $this->getMaxOrder() + 1;
		
			// insert new menu entry
			$sql = "INSERT INTO `" . $this->table . "`
					(`menuid`, 	`order`, 		`title`, 						`mod`, 							`requires_login`, `assigned_groupid`, `parentid`, `language`, `home`, `template`, `domainid`)
					VALUES
					(NULL, 		".$order.", '".secureMySQL($title)."', 	'".secureMySQL($mod)."', ".(int)$requires_login.", ".(int)$assigned_groupid.", ".(int)$parent_id.", '".secureMySQL($language)."', ".(int)$startpage.", '".secureMySQL($template)."', ".(int)$domainid.")";
			
			$this->db->query($sql);
			
		}
		
		function editUrl($index, $title, $url, $requires_login = 0, $assigned_groupid = 0, $parent_id = 0, $language = '') {
            $sql = "UPDATE `" . $this->table . "` SET
					`title`='" . secureMySQL($title) . "', 
					`requires_login`=" . (int)$requires_login . ",
					`assigned_groupid`=" . (int)$assigned_groupid . ",
					`url`='" . secureMySQL($url) . "',
					`parentid`=" . (int)$parent_id . ",
					`language`='" . secureMySQL($language) . "' 
					WHERE `menuid`=" . (int)$index . " AND `mod`='url';";
			$this->db->query($sql);
        }
		
		function editElement($index, $title, $mod, $requires_login = 0, $assigned_groupid = 0, $parent_id = 0, $language = '', $startpage = 0, $template = '', $domainid = 0) {
			if ($startpage > 0)
				$this->db->update($this->table, '`home`=0', "`language`='".secureMySQL($language)."' AND `domainid`=".(int)$domainid);
				
			$sql = "UPDATE `" . $this->table . "` SET
					`title`='" . secureMySQL($title) . "', 
					`mod`='" . secureMySQL($mod) . "', 
					`requires_login`=" . (int)$requires_login . ",
					`assigned_groupid`=" . (int)$assigned_groupid . ",
					`parentid`=" . (int)$parent_id . ",
					`language`='" . secureMySQL($language) . "',
					`home`=".(int)$startpage.",
					`template`='".secureMySQL($template)."',
					`domainid` =" .(int)$domainid." 
					WHERE `menuid`=" . (int)$index . ";";
			$this->db->query($sql);
			
		}
		
		function removeElement($index) {
			$sql = "DELETE FROM `" . $this->table . "`
					WHERE `menuid`=" . (int)$index . ";";
			$this->db->query($sql);
		}
		
		private function getElementOrder($index) {
			$sql = "SELECT `order` FROM `" . $this->table . "`
					WHERE `menuid`=" . (int)$index . ";";
			
			$result = $this->db->query($sql);
			$row = mysql_fetch_assoc($result);
			
			return $row['order'];
		}
		
		function setElementOrder($index, $order) {
			$sql = "UPDATE `" . $this->table . "` SET 
					`order`=" . (int)$order . "
					WHERE `menuid`=" . (int)$index . ";";
			
			$this->db->query($sql);
		}
		
		function swapElements($indexOne, $indexTwo) {
			$orderOne = $this->getElementOrder($indexOne);
			$orderTwo = $this->getElementOrder($indexTwo);
			
			$this->setElementOrder($indexOne, $orderTwo);
			$this->setElementOrder($indexTwo, $orderOne);
		}
		
		function getMenuCount() {
			$sql = "SELECT * FROM `" . $this->table . "`;";
			$result = $this->db->query($sql);
			return mysql_num_rows($result);
		}
		
		function getMenuEntry($menuid) {
			return $this->db->selectOneRow('menu', '*', '`menuid`='.(int)$menuid);
		}
	}

?>