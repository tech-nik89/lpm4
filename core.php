<?php
	
	 /**
	 * Project: Higher For Hire
	 * File: index.php
	 * Author: Nikolaus Themessl
	 * 
	 * Copyright (C) 2009-2012 Nikolaus Themessl
	 *
	 *
     * This file is part of LAN Party Manager 4.
	 *
	 * LAN Party Manager 4 is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU Lesser General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * LAN Party Manager 4 is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU Lesser General Public License for more details.
	 *
	 * You should have received a copy of the GNU Lesser General Public License
	 * along with LAN Party Manager 4.  If not, see <http://www.gnu.org/licenses/>.
	 *
	 */
	 
	// Debug class
	include('./core/debug.core.php');
	$debug = new Debug();
	
	// Common function collection
	include('./core/common.core.php');
	
	// Time Functions
	include('./core/time.core.php');
	
	// Detect Mobile Devices
	include('./core/mobile_detect.core.php');
	
	if (!mobile_detect() || @$_GET['nomobile'] == '1')
		$mobile = 'default';
	else
		$mobile = 'mobile';
	
	// Template Engine
	include('./core/smarty.core.php');
	$smarty = new Smarty;
	$smarty->allow_php_tag = true;
	$smarty->error_reporting = E_ALL & ~E_NOTICE;
	
	// Database Engine
	include('./core/database.core.php');
	$db = new Database();
	
	// Config Engine
	include('./core/config.core.php'); 
	$config = new Config($db);
	
	// Set Timezone
	$timezone = $config->get('core', 'timezone');
	if ($timezone == '') $timezone = 'Europe/Paris';
	date_default_timezone_set($timezone);
	
	// Notify Engine
	include('./core/notify.core.php');
	$notify = new Notify();
	
	// Localization
	include('./core/lang.core.php');
	$lang = new Language();
	
	// User Engine
	include('./core/user.core.php');
	$user = new User($db);
	
	// Check for login and current user
	include('./core/login.core.php');
	$login = new Login($db, $user);
	
	// Breadcrumbs Engine
	include('./core/breadcrumbs.core.php');
	$breadcrumbs = new Breadcrumbs();
	
	// Menu Engine
	include('./core/menu.core.php');
	$menu = new Menu($db, $login);
	
	// Right Management
	include('./core/rights.core.php');
	$rights = new Rights($user, $db, $login);
	
	// Avatar Class
	include('./core/avatar.core.php');
	$avatar = new Avatar($user, $lang, $login);
	
	// pages
	include('./core/pages.core.php');
	$pages = new Pages();
	
	// comments
	include('./core/comments.core.php');
	$comments = new Comments($db);
	
	// content
	include('./core/content.core.php');
	$content = new Content();
	
	// credit
	include('./core/credit.core.php');
	$credit = new Credit();
	
	// log
	include('./core/log.core.php');
	$log = new Log();
	
	// statistic
	include('./core/stat.core.php');
	$stat = new Stat();
		
	// boxes
	include('./core/boxes.core.php');
	$boxes = new Boxes();
	
	// backup
	include('./core/backup.core.php');
	$backup = new Backup();
	
	// favorites
	include('./core/favorites.core.php');
	$favorites = new Favorites();
	
	// mail
	include('./core/mail.core.php');
	$eMail = new Mail();
	
	// upload
	include('./core/upload.core.php');
	
	// Check if mobile device detection is disabled
	if ($config->get('core', 'enable-mobile') != 1)
		$mobile = 'default';
	
	// domains
	include('./core/domains.core.php');
	
?>