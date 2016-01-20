<?php

	function DeleteAll() {
		global $db;
		$tables = array("board", "boxes", "calendar", "calendar_categories", "comments", "config", "contact",
						"content", "content_permissions", "credit", "detailedpoll", "detailedpoll_answers",
						"detailedpoll_questions", "detailedpoll_user_answers", "domains", "events", "favorites",
						"groups", "groupware", "group_rights", "group_users", "guestbook", "imprint", "inbox",
						"log", "media_categories", "media_categories_permissions", "media_downloads", "media_downloads_counter",
						"media_movies", "menu", "mod", "newposts", "news", "notify", "outbox", "personal_data",
						"personal_fields", "poll", "poll_questions", "post", "register", "rights", "shoutbox",
						"stat", "tetris_attack", "tetris_chat", "tetris_highscore", "tetris_player", "tetris_start",
						"thread", "thread_abo", "users");
		
		foreach($tables as $table) { 
			$db->query("DROP TABLE IF EXISTS `" . MYSQL_TABLE_PREFIX . $table . "`");
		}
	}
	
	function CreateAll($coursename, $dummypassword, $defaultpassword) {
		global $db;
		
		if(trim($coursename) == "") {
			$coursename = "DEMO";
		}
		
		if(trim($dummypassword) == "") {
			$dummypassword = "pw";
		}
		
		if(trim($defaultpassword) == "") {
			$defaultpassword = "pw";
		}
		
		$dummypassword = generatePasswordHash(secureMySQL($dummypassword));
		$defaultpassword = generatePasswordHash(secureMySQL($defaultpassword));
		
		
		$dayofweek = date("w");
		$offset = 0;
		if($dayofweek == 0) {
			$offset = 1;
		} else {
			$offset = 1 - $dayofweek;
		}

		$date_monday_day = date("d") + $offset;
		$date_monday_month = date("m");
		$date_monday_year = date("Y");
		
		//
		//	Users
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "users` (
			  `userid` bigint(20) NOT NULL AUTO_INCREMENT,
			  `email` varchar(64) NOT NULL,
			  `password` varchar(256) NOT NULL,
			  `ipadress` varchar(16) NOT NULL,
			  `session_id` varchar(26) NOT NULL,
			  `nickname` varchar(64) NOT NULL,
			  `lastname` varchar(64) NOT NULL,
			  `prename` varchar(64) NOT NULL,
			  `birthday` int(11) NOT NULL,
			  `regdate` int(11) NOT NULL,
			  `lastaction` int(11) NOT NULL,
			  `template` varchar(64) NOT NULL,
			  `comment` varchar(256) NOT NULL,
			  `avatar` varchar(128) NOT NULL,
			  `ban` int(1) NOT NULL,
			  `activated` int(1) NOT NULL,
			  `activation_key` varchar(64) NOT NULL,
			  `company` varchar(255) NOT NULL,
			  `address` varchar(1023) NOT NULL,
			  PRIMARY KEY (`userid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;";
		$db->query($sql);

		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "users` (`userid`, `email`, `password`, `ipadress`, `session_id`, `nickname`, `lastname`, `prename`, `birthday`, `regdate`, `lastaction`, `template`, `comment`, `avatar`, `ban`, `activated`, `activation_key`, `company`, `address`) VALUES
			(1, 'info@smartstudies.de', 'e84bf3942a3e99072042e5802b432db4', '141.31.111.1', '82csg28ufsm4vjl2gog4moepq2', 'Admin', 'Admin', 'Anton', 1307211898, 1338834298, 1339666825, '', '', '', 0, 1, 'c9815d89c450ec319a6ca10cb1aaf36f', '', ''),
			(2, 'bea.brue@irgendwas.de', '" . $defaultpassword . "', '', '', 'Bea', 'Brünette', 'Beate', 575848800, 1338834963, 0, '', '', '', 0, 1, 'e1809f5df02e911190c3aca0df3d0f12', '', ''),
			(3, 'roundhouse@kick.com', '" . $defaultpassword . "', '141.31.111.1', '82csg28ufsm4vjl2gog4moepq2', 'Chuck', 'Cnorris', 'Chuck', 541378800, 1338835024, 1339666644, '', '', '3_4fd99ec8696e0_Unbenannt.jpg', 0, 1, 'cded8b60591ea885b103e3d8862e4e3c', '', ''),
			(4, 'emilia@yahuu.de', '" . $defaultpassword . "', '141.31.111.1', 'o2hofj4unl8e6i6nlbi5coa8o1', 'Emilia', 'Echo', 'Emilia', 630889200, 1338835144, 1339661050, '', '', '', 0, 1, '11c258d467d42442282f2f6db4aa8039', '', ''),
			(5, 'fred@feuerstein.org', '" . $defaultpassword . "', '141.31.111.1', 'o2hofj4unl8e6i6nlbi5coa8o1', 'Freddy', 'Feuerstein', 'Fred', 654213600, 1338835184, 1339662325, '', '', '5_4fd99bfaa7cc8_fred-feuerstein.jpg', 0, 1, '13488a0bad56932c32ac3af580cee0af', '', ''),
			(6, 'mitglied@smartstudies.de', '" . $dummypassword . "', '141.31.111.1', 'o2hofj4unl8e6i6nlbi5coa8o1', 'Gabi', 'Gans', 'Gabriella', 593046000, 1338835237, 1339660668, '', '', '', 0, 1, '25fbe2530a483980c6a26750f84af6a7', '', ''),
			(7, 'haha@hoho.de', '" . $defaultpassword . "', '', '', 'Hansi', 'Halblang', 'Hans', 637974000, 1338835341, 0, '', '', '', 0, 1, 'ff6d745040c13dd6502a1720b6c54b97', '', ''),
			(8, 'isnichdeinernst@ohdoch.com', '" . $defaultpassword . "', '', '', 'Isabella', 'Isnichdeinernst', 'Isabella', 691542000, 1338835404, 0, '', '', '', 0, 1, 'bff8c6f1744f6f191f26bcddb9ac3d1e', '', ''),
			(9, 'jajay@ridiculouslongmail.com', '" . $defaultpassword . "', '', '', 'James', 'Johnson', 'James', 576885600, 1338835449, 0, '', '', '', 0, 1, '4c0c02cdbe5af455ca579ca1a6dc2b55', '', ''),
			(10, 'kurssprecher@smartstudies.de', '" . $dummypassword . "', '', '', 'Kirsten', 'Kurssprecher', 'Kirsten', " . mktime(0, 0, 0, date('m'), date('d'), date('Y')) . ", 1338835509, 0, '', '', '', 0, 1, '63862d42b3bbed8b931b18d929a149c3', '', ''),
			(11, 'wolverine@x-men.de', '" . $defaultpassword . "', '141.31.111.1', 'o2hofj4unl8e6i6nlbi5coa8o1', 'Logan', 'Lonesome', 'Logan', 476233200, 1338835772, 1339662221, '', '', '11_4fd99db67fa6e_Hugh Jackman Wolverine 02.jpg', 0, 1, 'a78b71f4fe4cc1041dba8c7834720e2c', '', ''),
			(12, 'noone.listens.to.me@never.uk', '" . $defaultpassword . "', '141.31.111.1', 'o2hofj4unl8e6i6nlbi5coa8o1', 'Marvin', 'Melancholie', 'Marvin', 653349600, 1338835843, 1339661465, '', '', '12_4fd99c7022261_Marvin.jpg-675x550.jpg', 0, 1, '357f0737b7ff12cd57609f8d2bc4301d', '', ''),
			(13, 'naoie.niedlich@wehb.de', '" . $defaultpassword . "', '', '', 'Naomi', 'Niedlich', 'Naomi', 650844000, 1338835889, 0, '', '', '', 0, 1, 'dd07114fc108a36ad1a2cc2d67d3f6d0', '', ''),
			(14, 'ottifant@walkes.com', '" . $defaultpassword . "', '141.31.111.1', 'o2hofj4unl8e6i6nlbi5coa8o1', 'Otto', 'Otter', 'Otto', -870314400, 1338835915, 1339661282, '', '', '14_4fd99b7f44936_zeichenkurs0.jpg', 0, 1, 'd11d99c5eea51a7233e32c9277288e58', '', ''),
			(15, 'paul_paulson@arkohr.de', '" . $defaultpassword . "', '', '', 'Paul', 'Paulson', 'Paul', 589500000, 1338835958, 0, '', '', '', 0, 1, 'b2aa1970af9eea73d83dc80a436d5091', '', ''),
			(16, 'quaqua@whatever.de', '" . $defaultpassword . "', '', '', 'Quasi', 'Quast', 'Quasimodo', 608248800, 1338836001, 0, '', '', '', 0, 1, '75d330a257767e968bfddfcd774e3af9', '', ''),
			(17, 'raulrasmus@myemail.de', '" . $defaultpassword . "', '', '', 'Raúl', 'Rasmus', 'Raúl', 507855600, 1338836077, 0, '', '', '', 0, 1, 'd67b9f5fbd7e4bf495774f7f4228bf04', '', ''),
			(18, 'sunny_sewatheart@blubb.de', '" . $defaultpassword . "', '', '', 'Sunny', 'Sweetheart', 'Sonja', 518306400, 1338836141, 0, '', '', '', 0, 1, 'cce3be584d67c013d94c499129c2bd11', '', ''),
			(19, 'tobi@test.com', '" . $defaultpassword . "', '', '', 'Tobi', 'Tester', 'Tobi', 529714800, 1338836209, 0, '', '', '', 0, 1, '6b3f7abdc63e1cd28bf919d62444eb9e', '', ''),
			(20, 'uwe@under.de', '" . $defaultpassword . "', '', '', 'Uwe', 'Underdog', 'Uwe', 525132000, 1338836262, 0, '', '', '', 0, 1, '450a9825737b333e7cec7812f0c2a081', '', ''),
			(21, 'helsing@vampires.de', '" . $defaultpassword . "', '141.31.111.1', 'o2hofj4unl8e6i6nlbi5coa8o1', 'Van Helsing', 'van Helsing', 'Victor', 604796400, 1338836313, 1339662299, '', '', '21_4fd99d9725f85_van helsing foto.jpg', 0, 1, '124f4953560f96b00b129594918bd7e8', '', ''),
			(22, 'xyz@what.de', '" . $defaultpassword . "', '', '', 'Xaver', 'Xelsbrot', 'Xaver', -870314400, 1338836349, 0, '', '', '', 0, 1, 'a3e00aec25f47e4ef01658f3eb261099', '', ''),
			(23, 'yoyo@illbeback.de', '" . $defaultpassword . "', '', '', 'Yasmin', 'Yoga', 'Yasmin', 699404400, 1338836384, 0, '', '', '', 0, 1, '54812144089e2c695e07b00537def9b4', '', ''),
			(24, 'icanseeyou@omg.de', '" . $defaultpassword . "', '', '', 'Zooey', 'Zoom', 'Zooey', -870314400, 1338836412, 0, '', '', '', 0, 1, '27d0482a650a922546139161c25e767d', '', ''),
			(25, 'ihavemoney@myhome.de', '" . $defaultpassword . "', '141.31.111.1', 'o2hofj4unl8e6i6nlbi5coa8o1', 'Dago', 'Duck', 'Dagobert', 707263200, 1338836483, 1339661671, '', '', '25_4fd99cf3cb994_26868_1.jpg', 0, 1, '21c9805772a0eb5fe78afa1e1c87f8a8', '', ''),
			(26, 'warrio@thewrongcastle.com', '" . $defaultpassword . "', '', '', 'Warrio', 'W. Waster', 'Warrio', -870314400, 1338836810, 0, '', '', '', 0, 1, '6229a376c9bb44e19885098e8879bc0e', '', '')";
		$db->query($sql);

		//
		// Board
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "board` (
				  `boardid` int(11) NOT NULL AUTO_INCREMENT,
				  `board` varchar(64) NOT NULL,
				  `order` int(11) NOT NULL,
				  `description` varchar(64) NOT NULL,
				  `assigned_groupid` int(11) NOT NULL,
				  PRIMARY KEY (`boardid`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "board` (`boardid`, `board`, `order`, `description`, `assigned_groupid`) VALUES
				(1, 'Talk', 1, 'Alles mögliche...', 0),
				(2, 'Klausuren', 2, 'Übungen, Informationen,...', 0),
				(3, 'Events', 4, 'WG Parties, Wasen,...', 0),
				(4, 'Vorlesungen', 3, 'Vorlesungsmaterial', 0);";
		$db->query($sql);
		
		//
		//	Boxes
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "boxes` (
			  `boxid` int(11) NOT NULL AUTO_INCREMENT,
			  `title` varchar(63) NOT NULL,
			  `file` varchar(255) NOT NULL,
			  `position` varchar(6) NOT NULL,
			  `order` int(11) NOT NULL,
			  `visible` int(1) NOT NULL,
			  `requires_login` int(1) NOT NULL,
			  `domainid` int(11) NOT NULL,
			  PRIMARY KEY (`boxid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "boxes` (`boxid`, `title`, `file`, `position`, `order`, `visible`, `requires_login`, `domainid`) VALUES
				(1, 'Umfragen', 'poll', 'left', 0, 1, 1, 0),
				(5, 'Forum', 'board', 'right', 0, 1, 1, 0),
				(4, 'Vorlesungsplan', 'calendar', 'left', 1, 1, 1, 0),
				(6, 'Shoutbox', 'shoutbox', 'right', 1, 1, 1, 0);";
		$db->query($sql);
		
		//
		//	Calendar
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "calendar` (
			  `calendarid` int(11) NOT NULL AUTO_INCREMENT,
			  `userid` int(11) NOT NULL,
			  `start` int(11) NOT NULL,
			  `end` int(11) NOT NULL,
			  `title` varchar(1023) NOT NULL,
			  `description` text NOT NULL,
			  `visible` int(1) NOT NULL,
			  `language` varchar(4) NOT NULL,
			  `categoryId` int(10) DEFAULT NULL,
			  PRIMARY KEY (`calendarid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "calendar` (`calendarid`, `userid`, `start`, `end`, `title`, `description`, `visible`, `language`, `categoryId`) VALUES
			(1, 1, " . mktime(9, 0, 0, $date_monday_month, $date_monday_day, $date_monday_year) . ", 
				   " . mktime(12, 15, 0, $date_monday_month, $date_monday_day, $date_monday_year) . ",
					'Mathematik', '', 2, '', 2),
			(2, 1, " . mktime(13, 0, 0, $date_monday_month, $date_monday_day, $date_monday_year) . ", 
				   " . mktime(16, 45, 0, $date_monday_month, $date_monday_day, $date_monday_year) . ", 
				   'Mathematik', '', 2, '', 2),
			(3, 1, " . mktime(9, 0, 0, $date_monday_month, $date_monday_day + 1, $date_monday_year) . ", 
				   " . mktime(12, 15, 0, $date_monday_month, $date_monday_day + 1, $date_monday_year) . ", 
				   'ABWL', '', 2, '', 2),
			(4, 1, " . mktime(17, 0, 0, $date_monday_month, $date_monday_day + 1, $date_monday_year) . ", 
				   " . mktime(20, 00, 0, $date_monday_month, $date_monday_day + 1, $date_monday_year) . ", 
				   'Wasen', 'Treffpunkt bei dem Mann mit der roten Giraffe.', 2, '', 4),
			(5, 1, " . mktime(13, 0, 0, $date_monday_month, $date_monday_day + 1, $date_monday_year) . ", 
				   " . mktime(16, 30, 0, $date_monday_month, $date_monday_day + 1, $date_monday_year) . ",
				   'Recht', '', 2, '', 2),
			(6, 1, " . mktime(13, 0, 0, $date_monday_month, $date_monday_day + 2, $date_monday_year) . ", 
				   " . mktime(16, 30, 0, $date_monday_month, $date_monday_day + 2, $date_monday_year) . ",
				   'Mathe Tutorium', '', 2, '', 3),
			(7, 1, " . mktime(14, 0, 0, $date_monday_month, $date_monday_day + 2, $date_monday_year) . ", 
				   " . mktime(17, 00, 0, $date_monday_month, $date_monday_day + 2, $date_monday_year) . ",
				   'ABWL Tutorium', '', 2, '', 3),
			(8, 1, " . mktime(9, 0, 0, $date_monday_month, $date_monday_day + 2, $date_monday_year) . ", 
				   " . mktime(12, 15, 0, $date_monday_month, $date_monday_day + 2, $date_monday_year) . ", 
				   'Spanisch', '', 2, '', 3),
			(9, 1, " . mktime(9, 0, 0, $date_monday_month, $date_monday_day + 3, $date_monday_year) . ", 
				   " . mktime(12, 0, 0, $date_monday_month, $date_monday_day + 3, $date_monday_year) . ",
				   'Soziale Kompetenzen', '', 2, '', 2),
			(10, 1, " . mktime(9, 0, 0, $date_monday_month, $date_monday_day + 4, $date_monday_year) . ", 
				   " . mktime(12, 15, 0, $date_monday_month, $date_monday_day + 4, $date_monday_year) . ",
				   'Grundlegende Technologien zur Entwickung künstlich gesteuerter Massageroboter', '', 2, '', 2),
			(11, 1, " . mktime(13, 0, 0, $date_monday_month, $date_monday_day + 4, $date_monday_year) . ", 
				   " . mktime(16, 30, 0, $date_monday_month, $date_monday_day + 4, $date_monday_year) . ",
				   'Fortgeschrittene Technologien zur Entwickung künstlich gesteuerter Massageroboter', '', 2, '', 2);";
		$db->query($sql);
		
		//
		//	Calendar_Categories
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "calendar_categories` (
			  `categoryId` int(10) NOT NULL AUTO_INCREMENT,
			  `title` varchar(100) NOT NULL,
			  `backgroundcolor` varchar(10) NOT NULL,
			  `fontcolor` varchar(10) NOT NULL,
			  `description` varchar(1023) NOT NULL,
			  PRIMARY KEY (`categoryId`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "calendar_categories` (`categoryId`, `title`, `backgroundcolor`, `fontcolor`, `description`) VALUES
			(2, 'Pflichtvorlesung', '#6b6b6b', '#000000', ''),
			(3, 'Freiwillige Vorlesung', '#b7d59f', '#000000', ''),
			(4, 'Event', '#24d5d6', '#000000', '');";
		$db->query($sql);
		
		//
		//	Comments
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "comments` (
			  `commentid` bigint(20) NOT NULL AUTO_INCREMENT,
			  `userid` int(11) NOT NULL,
			  `timestamp` int(11) NOT NULL,
			  `mod` varchar(64) NOT NULL,
			  `contentid` int(11) NOT NULL,
			  `text` varchar(4095) NOT NULL,
			  PRIMARY KEY (`commentid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$db->query($sql);

		//
		//	Config
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "config` (
			  `mod` varchar(64) NOT NULL,
			  `key` varchar(64) NOT NULL,
			  `value` varchar(512) NOT NULL,
			  `type` varchar(64) NOT NULL,
			  `description` varchar(256) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "config` (`mod`, `key`, `value`, `type`, `description`) VALUES
			('core', 'title', '" . $coursename . "', 'string', 'Sets the title of the home page.'),
			('core', 'save-login-disabled', '0', 'bool', 'Enables or disables if an user can stay logged in.'),
			('core', 'log_file_enabled', '1', 'bool', 'Enables or disables the file version of the log.'),
			('core', 'log_mysql_enabled', '0', 'bool', 'Enables or disables the database version of the log.'),
			('core', 'log-crypt-key', 'hfh', 'string', 'Specifies the crypt key for the log file names.'),
			('core', 'stat-enabled', '0', 'bool', 'Enables or disables the visitors statistic.'),
			('core', 'stat-duration', '420', 'int', 'Specifies the value in seconds a visitor is counted as one.'),
			('core', 'stat-last-seen-length', '10', 'int', 'Specifies the number of users shown in the last-seen list.'),
			('stat', 'show-visitors-this-year', '1', 'bool', 'Enables or diasables the showing of visitors this year.'),
			('stat', 'show-visitors-this-month', '1', 'bool', 'Enables or diasables the showing of visitors this month.'),
			('stat', 'show-browseragent', '1', 'bool', 'Enables or diasables the showing of the browseragent.'),
			('stat', 'show-os', '1', 'bool', 'Enables or diasables the showing of the operationg system.'),
			('stat', 'show-referer', '1', 'bool', 'Enables or diasables the showing of the referer.'),
			('stat', 'show-bots', '0', 'bool', 'Enables or diasables the showing of bots.'),
			('favorites', 'enable', '0', 'bool', 'Enables or disables the global favorites.'),
			('login', 'register-send-email', '0', 'bool', 'Sends a mail after registration.'),
			('login', 'register-activation-required', '0', 'bool', 'Sets if an activation is required.'),
			('login', 'register-mail-subject', '', 'string', 'Sets the subject of the registration mail.'),
			('login', 'register-mail-text', '', 'text', 'Sets the text of the registration mail.'),
			('login', 'register-mail-sender', 'test@test.de', 'string', 'Sets the sender of the registration and password mail.'),
			('login', 'register-notification-mail-address', '', 'string', 'Specifies the mail address where the register notification will be sent to.'),
			('login', 'lostpw-mail-text', '', 'text', 'Sets the text of the password mail.'),
			('login', 'lostpw-mail-subject', '', 'string', 'Sets the subject of the password mail.'),
			('login', 'disable-second-email', '0', 'bool', 'Enables or disables the second e-mail field of registration.'),
			('login', 'disable-nickname', '0', 'bool', 'Disables the nickname field. It will be replaced by pre- and lastname.'),
			('login', 'disable-birthday', '0', 'bool', 'Disables the birthday field.'),
			('login', 'register-disable', '0', 'bool', 'Enables or disables the register functionality'),
			('pmbox', 'email-notification', '0', 'bool', 'Enables or disables the email notification for new pms'),
			('pmbox', 'inbox_limit', '35', 'int', 'Sets the max number of messages stored in the inbox.'),
			('core', 'maintenance', '0', 'bool', 'Enables or disables the maintenance mode.'),
			('core', 'maintenance_description', '', 'text', 'Sets the description for the maintenance mode.'),
			('login', 'session-time', '240', 'int', 'Sets the time in seconds a session is valid.'),
			('core', 'template', 'smartstudies_orange', 'string', 'Sets the default template.'),
			('core', 'default_mod', 'login', 'string', 'Sets the default module for the start page.'),
			('core', 'language', 'de', 'string', 'Sets the language.'),
			('admin', 'users-per-page', '20', 'int', 'Sets the number of users displayed on one page.'),
			('admin', 'comments-per-page', '20', 'int', 'Sets the number of comments displayed on one page.'),
			('core', 'mod_rewrite', '1', 'bool', 'Enables or disables mod rewrite.'),
			('core', 'debug', '0', 'bool', 'Enables or disables the debug mode.'),
			('core', 'bbcode', '1', 'bool', 'Enables or disables the bbcode parser.'),
			('core', 'img-width', '150', 'int', 'Sets the avatar width in pixel.'),
			('core', 'img-height', '150', 'int', 'Sets the avatar height in pixel.'),
			('core', 'timezone', 'Europe/Paris', 'string', 'Sets the server timezone.'),
			('core', 'admin-mail', '', 'string', 'Specifies the email adress of the page administrator.'),
			('core', 'enable-mobile', '0', 'bool', 'Enables or disables the mobile device detection.'),
			('core', 'disable-reflections', '0', 'bool', 'Enables or disables the reflections of the avatars.'),
			('core', 'link-mod-to-menu', '0', 'bool', 'When enabled, modules without visible menu entry aren''t accessable.'),
			('core', 'link-mod-to-menu-exclusions', '', 'string', 'List of modules, which are always accessable. Separate with semicolon (;).'),
			('profile', 'hide-lastname', '0', 'bool', 'Hides the lastname in profile.'),
			('core', 'disable-magic-quotes', '0', 'bool', 'Force disables magic quotes, if php.ini is not accessable.'),
			('core', 'include-submenu', '0', 'bool', 'Specifies if the submenu entires are included in main menu tree.'),
			('core', 'hide-logout-menu-entry', '0', 'bool', 'Specifies if the logout-submenu is included in menu tree.'),
			('core', 'password-salt', 'ipvq94w038o', 'string', 'Specifies the string which is used to salt the user passwords.'),
			('usercp', 'hide-overview', '0', 'bool', 'Hides the overview submenu entry of the usercp.'),
			('usercp', 'hide-personal', '0', 'bool', 'Hides the personal submenu entry of the usercp.'),
			('usercp', 'hide-avatar', '0', 'bool', 'Hides the avatar submenu entry of the usercp.'),
			('usercp', 'hide-comments', '0', 'bool', 'Hides the my-comments submenu entry of the usercp.'),
			('usercp', 'hide-changepw', '0', 'bool', 'Hides the changepw submenu entry of the usercp.'),
			('usercp', 'hide-company', '1', 'bool', 'Hides the company submenu entry of the usercp'),
			('core', 'allow-html-tags', '1', 'bool', 'Enables or disables html tags in forms.'),
			('usercp', 'disable-editing', '0', 'bool', 'Specifies, if an user is allowed to change his pre-, lastname and birthday.'),
			('contact', 'login-required', '0', 'bool', 'Specifies, if a guest has to be logged in to send a request.'),
			('contact', 'title', '', 'string', 'Specifies the title of the form.'),
			('contact', 'description', '', 'text', 'Specifies the description of the form.'),
			('contact', 'send-mail', '0', 'bool', 'Sends an email to the specified adress when a new request was sent.'),
			('contact', 'email-adress', '', 'string', 'The email adress the notification will be sent to.'),
			('calendar', 'enable-ical-export', '1', 'bool', 'Enables or disables the ical file export.'),
			('calendar', 'default-view', 'week', 'list', '|day,week,month,year,next'),
			('calendar', 'show-birthdays', '1', 'bool', 'Enables or disables the showing of birthdays.'),
			('calendar', 'box-number-of-entries', '3', 'int', 'Specifies the number of entries shown in the calendar box.'),
			('calendar', 'current-event', '1', 'bool', 'Enables or disables the current event counter in the calendar box.'),
			('calendar', 'current-event-refresh-time', '15', 'int', 'Specifies the sync-interval for the current event in seconds.'),
			('calendar', 'hide-day-view', '0', 'bool', 'Hides the day-view.'),
			('calendar', 'hide-week-view', '0', 'bool', 'Hides the week-view.'),
			('calendar', 'hide-month-view', '0', 'bool', 'Hides the month-view.'),
			('calendar', 'hide-year-view', '0', 'bool', 'Hides the year-view.'),
			('calendar', 'hide-next-view', '0', 'bool', 'Hides the next-view.'),
			('calendar', 'default-visibility', 'public', 'list', 'Sets the default visibility for new calendar entries.|private,logged-in,public'),
			('guestbook', 'entries-per-page', '20', 'int', 'Sets the number of entries displayed on one page.'),
			('guestbook', 'ip-blocker-enable', '0', 'bool', 'Enables or disables the ip blocker.'),
			('guestbook', 'ip-blocker-timelimit', '180', 'int', 'Sets the ip blocker timelimit.'),
			('events', 'bar-width', '90', 'int', 'Sets the width of the register bar.'),
			('media', 'pictures-per-row', '3', 'int', 'Sets the number of images displayed on one page.'),
			('media', 'thumbnailwidth', '200', 'int', 'Sets the thumbnail width of image previews.'),
			('media', 'download-login-required', '0', 'bool', 'Enables if a login is required to download a file.'),
			('media', 'max-upload-size', '10485760', 'int', 'Specifies the maximum file size in bytes you can upload.'),
			('media', 'number-of-uploads', '10', 'int', 'Specifies the number of upload forms displayed in the image upload dialog.'),
			('media', 'auto-resize', '0', 'bool', 'Enables or disables the auto resize functionality of the uploaded images.'),
			('media', 'auto-resize-width', '1024', 'int', 'Specifies the width of the images to which they will be resized to.'),
			('media', 'hide-submedia', '0', 'bool', 'Hides or displays the number of subcategories and media of a category.'),
			('media', 'mail-notification-address', '', 'string', 'Mail address where download notifications are sent to.'),
			('media', 'hide-upload-author', '0', 'bool', 'Hides or shows the user who uploaded content.'),
			('media', 'hide-upload-date', '0', 'bool', 'Hides or shows the upload date.'),
			('news', 'news-per-page', '5', 'int', 'Sets the number of news displayed on one page.'),
			('news', 'news-box-entries', '5', 'int', 'Specifies the number of entries displayed in the news box.'),
			('news', 'preview-char-length', '500', 'int', 'Sets the length of the preview news.'),
			('news', 'rss', '0', 'bool', 'Enables or disables the rss feed.'),
			('news', 'hide-time', '0', 'bool', 'Hides or shows the time.'),
			('news', 'hide-author', '0', 'bool', 'Hides or shows the author of a news entry.'),
			('poll', 'polls-per-page', '5', 'int', 'Sets the number of polls displayed on one page.'),
			('poll', 'maximum-questions', '10', 'int', 'Sets the max number of questions.'),
			('poll', 'maxbarlength', '300', 'int', 'Sets the max length of the bar.'),
			('poll', 'barcolor', '#00ff00,#00dd00,#00bb00,#009900,#007700', 'string', 'A list of the bar colors.'),
			('poll', 'box-show-bar-in-second-row', '1', 'bool', 'Defines if the bar should be shown in a seperate row.'),
			('poll', 'box-layout', 'boxes', 'list', '|lines,boxes'),
			('poll', 'main-layout', 'boxes', 'list', '|lines,boxes'),
			('board', 'posts-per-page', '20', 'int', 'Sets the number of posts displayed on one page.'),
			('board', 'threads-per-page', '20', 'int', 'Sets the number of threads displayed on one page.'),
			('board', 'convert-urls', '1', 'bool', 'Enables or disables the automatic url converted.'),
			('board', 'box-thread-once', '1', 'bool', 'Sets if a thread is only displayed once in the board box.'),
			('board', 'box-posts', '5', 'int', 'Sets the number of posts displayed in the board box.'),
			('board', 'enable-subscriptions', '0', 'bool', 'Enables or disables thread subscribtions.'),
			('board', 'disable-number-of-posts', '0', 'bool', 'Enables or disables the number of posts for an user.'),
			('shoutbox', 'posts', '7', 'int', 'Number of posts shown in the shoutbox.'),
			('shoutbox', 'lock-time', '15', 'int', 'The time in seconds an user cannot post again.'),
			('shoutbox', 'reverse', '0', 'bool', 'Reverses the shoutbox.');";
		$db->query($sql);
		
		//
		//	Contact
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "contact` (
		  `contactid` int(11) NOT NULL AUTO_INCREMENT,
		  `userid` int(11) NOT NULL,
		  `timestamp` int(11) NOT NULL,
		  `uniqid` varchar(64) NOT NULL,
		  `subject` varchar(64) NOT NULL,
		  `text` text NOT NULL,
		  `read` int(1) NOT NULL,
		  `done` int(1) NOT NULL,
		  PRIMARY KEY (`contactid`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$db->query($sql);
		
		//
		//	Content
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "content` (
		  `key` text NOT NULL,
		  `title` text NOT NULL,
		  `text` text NOT NULL,
		  `box_content` text NOT NULL,
		  `version` int(11) NOT NULL,
		  `version_timestamp` int(11) NOT NULL,
		  `version_author` int(11) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "content` (`key`, `title`, `text`, `box_content`, `version`, `version_timestamp`, `version_author`) VALUES
			('emails', 'Wichtige Emailadressen', '<p>\r\n	<b>Herr Apfel:</b> apfel@saft.de</p>\r\n<p>\r\n	<b>Herr Walter:</b> walter@t-offline.de</p>\r\n<p>\r\n	<b>Herr M&uuml;ller:</b> a.mueller@gabelbw.de</p>\r\n', '', 0, 1338838174, 1),
			('websites', 'Webseiten', '<p>\r\n	<a href=\"http://google.de\" target=\"_blank\">Google</a></p>\r\n<p>\r\n	<a href=\"http://heise.de\" target=\"_blank\">heise</a></p>\r\n<p>\r\n	&nbsp;</p>\r\n', '', 0, 1338838265, 1),
			('informations', 'Informationen', '<p>\r\n	Wichtige Informationen</p>\r\n', '', 0, 1338838374, 1),
			('websites', 'Webseiten', '<p>\r\n	<a href=\"http://google.de\" target=\"_blank\">Google</a></p>\r\n<p>\r\n	<a href=\"http://heise.de\" target=\"_blank\">heise</a></p>\r\n<p>\r\n	<a href=\"http://verydemotivational.memebase.com/\">verydemotivational</a></p>\r\n<p>\r\n	&nbsp;</p>\r\n', '', 1, 1339662933, 1),
			('websites', 'Webseiten', '<p>\r\n	<a href=\"http://google.de\" target=\"_blank\">Google</a></p>\r\n<p>\r\n	<a href=\"http://heise.de\" target=\"_blank\">heise</a></p>\r\n<p>\r\n	<a href=\"http://verydemotivational.memebase.com/\" target=\"_blank\">verydemotivational</a></p>\r\n<p>\r\n	&nbsp;</p>\r\n', '', 2, 1339662968, 1);
			;";
		$db->query($sql);
		
		//
		//	Content_Permissions
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "content_permissions` (
			  `key` varchar(255) NOT NULL,
			  `groupid` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		//
		//	Domains
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "domains` (
			  `domainid` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(255) NOT NULL,
			  `comment` text NOT NULL,
			  `template` varchar(255) NOT NULL,
			  PRIMARY KEY (`domainid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$db->query($sql);
		
		//
		//	Groups
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "groups` (
			  `groupid` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(64) NOT NULL,
			  `description` varchar(256) NOT NULL,
			  `display` int(1) NOT NULL,
			  `admin` int(1) NOT NULL,
			  PRIMARY KEY (`groupid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "groups` (`groupid`, `name`, `description`, `display`, `admin`) VALUES
			(1, 'Admin', 'Administratoren', 0, 1),
			(2, 'Mitglied', 'Studenten des Kurses " . $coursename . "', 0, 0),
			(3, 'Kurssprecher', 'Kurssprecher des Kurses " . $coursename . "', 0, 0);";
		$db->query($sql);
		
		//
		//	Groupware
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "groupware` (
			  `groupwareid` int(11) NOT NULL AUTO_INCREMENT,
			  `title` text NOT NULL,
			  `description` text NOT NULL,
			  `state` int(1) NOT NULL,
			  `end` int(11) NOT NULL,
			  `priority` int(1) NOT NULL,
			  `contactid` int(11) DEFAULT NULL,
			  `userid` int(11) DEFAULT NULL,
			  PRIMARY KEY (`groupwareid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$db->query($sql);
		
		//
		//	Group_Rights
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "group_rights` (
			  `mod` varchar(64) NOT NULL,
			  `name` varchar(64) NOT NULL,
			  `groupid` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "group_rights` (`mod`, `name`, `groupid`) VALUES
			('admin', 'groups', 1),
			('admin', 'sessions', 1),
			('admin', 'users', 1),
			('admin', 'personal_fields', 1),
			('admin', 'menu', 1),
			('admin', 'mod', 1),
			('admin', 'config', 1),
			('admin', 'comments', 1),
			('admin', 'content', 1),
			('admin', 'log', 1),
			('admin', 'groupware', 1),
			('admin', 'contact', 1),
			('admin', 'boxes', 1),
			('admin', 'backup', 1),
			('imprint', 'manage', 1),
			('calendar', 'manage', 1),
			('detailedpoll', 'editor', 1),
			('events', 'manage', 1),
			('guestbook', 'manage', 1),
			('media', 'manage', 1),
			('media', 'upload', 1),
			('news', 'manage', 1),
			('poll', 'editor', 1),
			('stat', 'manage', 1),
			('tetris', 'admin', 1),
			('calendar', 'manage', 3),
			('guestbook', 'manage', 3),
			('events', 'manage', 3),
			('news', 'manage', 3),
			('poll', 'editor', 3),
			('board', 'manage', 1),
			('admin', 'personal_fields', 3),
			('media', 'upload', 3),
			('media', 'manage', 3),
			('shoutbox', 'manage', 1),
			('shoutbox', 'manage', 3),
			('smartstudies', 'manage', 1);";
		$db->query($sql);
		
		//
		//	Group_Users
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "group_users` (
			  `groupid` int(11) NOT NULL,
			  `userid` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "group_users` (`groupid`, `userid`) VALUES
			(1, 1),
			(3, 10),
			(2, 10),
			(3, 3),
			(2, 3),
			(2, 2),
			(2, 25),
			(2, 4),
			(2, 5),
			(2, 6),
			(2, 7),
			(2, 8),
			(2, 9),
			(2, 11),
			(2, 12),
			(2, 13),
			(2, 14),
			(2, 15),
			(2, 16),
			(2, 17),
			(2, 18),
			(2, 19),
			(2, 20),
			(2, 21),
			(2, 22),
			(2, 23),
			(2, 24),
			(2, 26);";
		$db->query($sql);
		
		//
		//	Guestbook
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "guestbook` (
			  `guestbookid` int(11) NOT NULL AUTO_INCREMENT,
			  `author` varchar(64) NOT NULL,
			  `timestamp` int(11) NOT NULL,
			  `message` varchar(512) NOT NULL,
			  `ipadress` varchar(16) NOT NULL,
			  PRIMARY KEY (`guestbookid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "guestbook` (`guestbookid`, `author`, `timestamp`, `message`, `ipadress`) VALUES
			(1, 'Bewunderer', " . mktime(16, 6, 43, date('m'), date('d') - 4, date('Y')) . ", 'Wow, cool.', '127.0.0.1'),
			(2, 'Troll', " . mktime(9, 43, 12, date('m'), date('d') - 3, date('Y')) . ", 'I was here.', '141.31.111.1'),
			(3, 'Mr. Banane', " . mktime(22, 11, 30, date('m'), date('d') - 3, date('Y')) . ", 'Hey, ich wollte auch mal loswerden, dass diese Seite sehr informativ ist.', '141.31.111.1');
			";
		$db->query($sql);
		
		//
		//	Imprint
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "imprint` (
			  `owner_name` varchar(255) NOT NULL,
			  `owner_street` varchar(255) NOT NULL,
			  `owner_loc` varchar(255) NOT NULL,
			  `owner_tel` varchar(255) NOT NULL,
			  `owner_mail` varchar(255) NOT NULL,
			  `cont_name` varchar(255) NOT NULL,
			  `cont_street` varchar(255) NOT NULL,
			  `cont_loc` varchar(255) NOT NULL,
			  `court` varchar(255) NOT NULL,
			  `imprint` text NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		$sql = "INSERT INTO `hfh_imprint` (`owner_name`, `owner_street`, `owner_loc`, `owner_tel`, `owner_mail`, `cont_name`, `cont_street`, `cont_loc`, `court`, `imprint`) VALUES
				('Nikolaus Themessl', 'Rötlenstraße 25', 'Filderstadt', '', 'subwoof@isobeef.org', 'Nikolaus Themessl', 'Rötlenstraße 25', 'Filderstadt', '', ' 1. Inhalt des Onlineangebotes\r\n\r\nDer Autor übernimmt keinerlei Gewähr für die Aktualität, Korrektheit, Vollständigkeit oder Qualität der bereitgestellten Informationen. Haftungsansprüche gegen den Autor, welche sich auf Schäden materieller oder ideeller Art beziehen, die durch die Nutzung oder Nichtnutzung der dargebotenen Informationen bzw. durch die Nutzung fehlerhafter und unvollständiger Informationen verursacht wurden, sind grundsätzlich ausgeschlossen, sofern seitens des Autors kein nachweislich vorsätzliches oder grob fahrlässiges Verschulden vorliegt.\r\n\r\nAlle Angebote sind freibleibend und unverbindlich. Der Autor behält es sich ausdrücklich vor, Teile der Seiten oder das gesamte Angebot ohne gesonderte Ankündigung zu verändern, zu ergänzen, zu löschen oder die Veröffentlichung zeitweise oder endgültig einzustellen.\r\n \r\n\r\n\r\n2. Verweise und Links\r\n\r\nBei direkten oder indirekten Verweisen auf fremde Internetseiten (&quot;Links&quot;), die außerhalb des Verantwortungsbereiches des Autors liegen, würde eine Haftungsverpflichtung ausschließlich in dem Fall in Kraft treten, in dem der Autor von den Inhalten Kenntnis hat und es ihm technisch möglich und zumutbar wäre, die Nutzung im Falle rechtswidriger Inhalte zu verhindern.\r\n\r\nDer Autor erklärt hiermit ausdrücklich, dass zum Zeitpunkt der Linksetzung keine illegalen Inhalte auf den zu verlinkenden Seiten erkennbar waren. Auf die aktuelle und zukünftige Gestaltung, die Inhalte oder die Urheberschaft der gelinkten/verknüpften Seiten hat der Autor keinerlei Einfluss. Deshalb distanziert er sich hiermit ausdrücklich von allen Inhalten aller gelinkten /verknüpften Seiten, die nach der Linksetzung verändert wurden. Diese Feststellung gilt für alle innerhalb des eigenen Internetangebotes gesetzten Links und Verweise sowie für Fremdeinträge in vom Autor eingerichteten Gästebüchern, Diskussionsforen und Mailinglisten.\r\n\r\nFür illegale, fehlerhafte oder unvollständige Inhalte und insbesondere für Schäden, die aus der Nutzung oder Nichtnutzung solcherart dargebotener Informationen entstehen, haftet allein der Anbieter der Seite, auf welche verwiesen wurde, nicht derjenige, der über Links auf die jeweilige Veröffentlichung lediglich verweist.\r\n \r\n\r\n\r\n3. Urheber- und Kennzeichenrecht\r\n\r\nDer Autor ist bestrebt, in allen Publikationen die Urheberrechte der verwendeten Grafiken, Tondokumente, Videosequenzen und Texte zu beachten, von ihm selbst erstellte Grafiken, Tondokumente, Videosequenzen und Texte zu nutzen oder auf lizenzfreie Grafiken, Tondokumente, Videosequenzen und Texte zurückzugreifen.\r\nAlle innerhalb des Internetangebotes genannten und ggf. durch Dritte geschätzten Marken- und Warenzeichen unterliegen uneingeschränkt den Bestimmungen des jeweils gültigen Kennzeichenrechts und den Besitzrechten der jeweiligen eingetragenen Eigentümer. Allein aufgrund der bloßen Nennung ist nicht der Schluss zu ziehen, dass Markenzeichen nicht durch Rechte Dritter geschützt sind!\r\n\r\nDas Copyright für veröffentlichte, vom Autor selbst erstellte Objekte bleibt allein beim Autor der Seiten. Eine Vervielfältigung oder Verwendung solcher Grafiken, Tondokumente, Videosequenzen und Texte in anderen elektronischen oder gedruckten Publikationen ist ohne ausdrückliche Zustimmung des Autors nicht gestattet.\r\n \r\n\r\n\r\n4. Datenschutz\r\n\r\nSofern innerhalb des Internetangebotes die Möglichkeit zur Eingabe persönlicher oder geschäftlicher Daten (Emailadressen, Namen, Anschriften) besteht, so erfolgt die Preisgabe dieser Daten seitens des Nutzers auf ausdrücklich freiwilliger Basis. Die Inanspruchnahme und Bezahlung aller angebotenen Dienste ist - soweit technisch möglich und zumutbar - auch ohne Angabe solcher Daten bzw. unter Angabe anonymisierter Daten oder eines Pseudonyms gestattet.\r\n \r\n\r\n\r\n5. Rechtswirksamkeit dieses Haftungsausschlusses\r\n\r\nDieser Haftungsausschluss ist als Teil des Internetangebotes zu betrachten, von dem aus auf diese Seite verwiesen wurde. Sofern Teile oder einzelne Formulierungen dieses Textes der geltenden Rechtslage nicht, nicht mehr oder nicht vollständig entsprechen sollten, bleiben die übrigen Teile des Dokumentes in ihrem Inhalt und ihrer Gültigkeit davon unberührt.\r\n');
				";
		
		$db->query($sql);
		
		//
		//	Inbox
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "inbox` (
			  `pmid` bigint(20) NOT NULL AUTO_INCREMENT,
			  `senderid` int(11) NOT NULL,
			  `recieverid` int(11) NOT NULL,
			  `timestamp` int(11) NOT NULL,
			  `subject` varchar(64) NOT NULL,
			  `message` text NOT NULL,
			  `read` int(1) NOT NULL,
			  `notified` int(1) NOT NULL,
			  PRIMARY KEY (`pmid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "inbox` (`pmid`, `senderid`, `recieverid`, `timestamp`, `subject`, `message`, `read`, `notified`) VALUES
			(1, 3, 1," . mktime(date('H'), date('i') - 31, 0, date('m'), date('d'), date('Y')) . ",
						'Admin Rechte', 'Gib mir Adminrechte.\n Sofort.', 0, 0),
			(2, 6, 1," . mktime(12, 13, 34, date('m'), date('d') - 2, date('Y')) . ",
						'Mittagessen', 'Wo gehn wir heute zum Mittagessen hin, irgendwelche Ideen?', 1, 1);
			";
		$db->query($sql);
		
		//
		//	Log
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "log` (
			  `logid` bigint(20) NOT NULL AUTO_INCREMENT,
			  `userid` int(11) NOT NULL,
			  `timestamp` int(11) NOT NULL,
			  `mod` varchar(64) NOT NULL,
			  `description` varchar(256) NOT NULL,
			  PRIMARY KEY (`logid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$db->query($sql);
		
		//
		//	Media_Categories
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "media_categories` (
			  `categoryid` int(11) NOT NULL AUTO_INCREMENT,
			  `parentid` int(11) NOT NULL,
			  `name` varchar(64) NOT NULL,
			  `uniqid` varchar(255) NOT NULL,
			  `language` varchar(4) NOT NULL,
			  PRIMARY KEY (`categoryid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "media_categories` (`categoryid`, `parentid`, `name`, `uniqid`, `language`) VALUES
			(1, 0, '1. Semester', '20d55d5bfe7e7917b664ea622ff1003e', ''),
			(2, 0, '2. Semester', '6d6a95490fd4ce958531f1af5537a519', ''),
			(3, 1, 'Statistik', 'bf1723f53565f3dd452fdfb8282219aa', ''),
			(4, 1, 'Recht', '37f2e310ff00e4488e9b5fa3c61aefb3', ''),
			(5, 1, 'ABWL', 'ceaf346f78d38f2f1935b31e0b80721e', ''),
			(6, 1, 'Soziale Kompetenzen', '5e7db39cff497df88e31cd08c1a08084', ''),
			(7, 1, 'Methodisches Arbeiten', '604dff2b64d0680cadcd24fc2140827b', ''),
			(8, 0, 'Bilder', 'e933bb021584be75f86f952936975247', ''),
			(9, 8, 'Gruppenfoto', '7ae5d9796fd2d9b9ed953d83f2abe440', ''),
			(10, 8, 'Ausflug in den Zoo', '36730f7f22fb33856be4ccf9a919df31', ''),
			(11, 0, 'Sonstiges', 'e7234ad084c5ab81c4a7c8a685a8a2c5', '');";
		$db->query($sql);
		
		//
		//	Media_Categories_Permissions
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "media_categories_permissions` (
			  `categoryid` int(11) NOT NULL,
			  `groupid` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		//
		//	Media_Downloads
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "media_downloads` (
			  `downloadid` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(64) NOT NULL,
			  `description` varchar(1023) NOT NULL,
			  `version` varchar(64) NOT NULL,
			  `file` varchar(256) NOT NULL,
			  `userid` int(11) NOT NULL,
			  `timestamp` int(11) NOT NULL,
			  `categoryid` int(11) NOT NULL,
			  `counter` int(11) NOT NULL,
			  `release_notes` text NOT NULL,
			  `thumbnail` varchar(511) NOT NULL,
			  `disabled` int(1) NOT NULL,
			  PRIMARY KEY (`downloadid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "media_downloads` (`downloadid`, `name`, `description`, `version`, `file`, `userid`, `timestamp`, `categoryid`, `counter`, `release_notes`, `thumbnail`, `disabled`) VALUES
			(1, 'Präsentation', '', '', 'Lorem ipsum.pdf', 1, " . mktime(9, 21, 4, date('m'), date('d') - 5, date('Y')) . ", 3, 0, '', '', 0),
			(2, 'Lernblatt', '', '', 'Lorem ipsum 2.pdf', 1, " . mktime(18, 34, 12, date('m'), date('d') - 3, date('Y')) . ", 3, 0, '', '', 0),
			(3, 'Übungsaufgaben', '', '', 'Lorem ipsum 1.docx', 1, " . mktime(23, 3, 53, date('m'), date('d') - 2, date('Y')) . ", 3, 0, '', '', 0),
			(4, 'Gruppe 1 - Präsentation & Handout', '', '', 'Lorem ipsum 3.rar', 1, " . mktime(12, 46, 52, date('m'), date('d') - 1, date('Y')) . ", 5, 0, '', '', 0),
			(5, 'Gruppe 2 - Präsentation & Handout', '', '', 'Lorem ipsum 0.rar', 1, " . mktime(9, 11, 12, date('m'), date('d') - 3, date('Y')) . ", 5, 0, '', '', 0),
			(6, 'Beispiel', '', '', 'Lorem ipsum 4.txt', 1, " . mktime(20, 34, 25, date('m'), date('d') - 2, date('Y')) . ", 6, 0, '', '', 0);";
		$db->query($sql);
		
		//
		//	Media_Downloads_Counter
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "media_downloads_counter` (
			  `downloadid` int(11) NOT NULL,
			  `timestamp` int(11) NOT NULL,
			  `browseragent` VARCHAR (511) NOT NULL,
			  `os` VARCHAR (511) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
			";
		$db->query($sql);
		
		//
		//	Media_Movies
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "media_movies` (
			  `movieid` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(64) NOT NULL,
			  `file` varchar(64) NOT NULL,
			  `categoryid` int(11) NOT NULL,
			  `userid` int(11) NOT NULL,
			  `timestamp` int(11) NOT NULL,
			  `description` varchar(256) NOT NULL,
			  PRIMARY KEY (`movieid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "media_movies` (`movieid`, `name`, `file`, `categoryid`, `userid`, `timestamp`, `description`) VALUES
			(1, 'Tetris to the MaxX Intro', 'tetris.flv', 11, 1, " . mktime(11, 14, 52, date('m'), date('d') - 3, date('Y')) . ", 'Intro zu dem Tetris - Klon Tetris to the MaxX.\nMehr Infos unter www.t3m-game.org.');
			";
		$db->query($sql);
		
		//
		//	Menu
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "menu` (
			  `menuid` int(11) NOT NULL AUTO_INCREMENT,
			  `parentid` int(11) NOT NULL,
			  `order` int(11) NOT NULL,
			  `title` varchar(64) NOT NULL,
			  `mod` varchar(64) NOT NULL,
			  `requires_login` int(1) NOT NULL,
			  `assigned_groupid` int(11) NOT NULL,
			  `language` varchar(4) NOT NULL,
			  `url` varchar(511) NOT NULL,
			  `home` int(1) NOT NULL,
			  `template` varchar(255) NOT NULL,
			  `domainid` int(11) NOT NULL,
			  PRIMARY KEY (`menuid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "menu` (`menuid`, `parentid`, `order`, `title`, `mod`, `requires_login`, `assigned_groupid`, `language`, `url`, `home`, `template`, `domainid`) VALUES
			(1, 0, 6, 'Login', 'login', 0, 0, '', '', 1, '', 0),
			(2, 1, 2, 'Admin', 'admin', 1, 1, '', '', 0, '', 0),
			(3, 1, 3, 'User Center', 'usercp', 1, 0, '', '', 0, '', 0),
			(4, 1, 4, 'Nachrichten', 'pmbox', 1, 0, '', '', 0, '', 0),
			(5, 0, 2, 'Vorlesungsplan', 'calendar', 1, 0, '', '', 0, '', 0),
			(6, 0, 4, 'Umfragen', 'poll', 1, 0, '', '', 0, '', 0),
			(7, 0, 5, 'Mediathek', 'media', 1, 0, '', '', 0, '', 0),
			(8, 0, 1, 'News', 'news', 1, 0, '', '', 0, '', 0),
			(9, 0, 10, 'Gästebuch', 'guestbook', 0, 0, '', '', 0, '', 0),
			(14, 0, 11, 'Informationen', 'informations', 1, 0, '', '', 0, '', 0),
			(11, 0, 9, 'Tetris', 'tetris', 1, 0, '', '', 0, '', 0),
			(13, 0, 3, 'Forum', 'board', 1, 0, '', '', 0, '', 0),
			(15, 14, 12, 'E-Mail Adressen', 'emails', 1, 0, '', '', 0, '', 0),
			(16, 14, 13, 'Webseiten', 'websites', 1, 0, '', '', 0, '', 0),
			(18, 1, 5, 'Shoutbox', 'shoutbox', 1, 1, '', '', 0, '', 0),
			(19, 1, 5, 'SmartStudies', 'smartstudies', 1, 1, '', '', 0, '', 0),
			(20, 0, 14, 'Impressum', 'imprint', 0, 0, '', '', 0, '', 0);
			";
		$db->query($sql);
		
		//
		//	A
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "mod` (
			  `mod` varchar(64) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
			";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "mod` (`mod`) VALUES
			('login'),
			('pmbox'),
			('admin'),
			('usercp'),
			('404'),
			('imprint'),
			('content'),
			('maintenance'),
			('profile'),
			('contact'),
			('calendar'),
			('shoutbox'),
			('guestbook'),
			('events'),
			('media'),
			('news'),
			('tetris'),
			('poll'),
			('board');";
		$db->query($sql);
		
		//
		//	Newposts
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "newposts` (
			  `userid` int(11) NOT NULL,
			  `threadid` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
			";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "newposts` (`userid`, `threadid`) VALUES
			(25, 1),
			(12, 2),
			(5, 3),
			(14, 3),
			(21, 4);";
		$db->query($sql);
		
		//
		//	News
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "news` (
			  `newsid` int(11) NOT NULL AUTO_INCREMENT,
			  `title` varchar(64) NOT NULL,
			  `text` text NOT NULL,
			  `preview` text NOT NULL,
			  `userid` int(11) NOT NULL,
			  `timestamp` int(11) NOT NULL,
			  `edit_count` int(11) NOT NULL,
			  `language` varchar(4) NOT NULL,
			  `domainid` int(11) NOT NULL,
			  PRIMARY KEY (`newsid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "news` (`newsid`, `title`, `text`, `preview`, `userid`, `timestamp`, `edit_count`, `language`, `domainid`) VALUES
			(1, 'Site geht online', '[b]Hallo zusammen![/b]\n\nIch freue mich euch mitteilen zu könne, dass die Siete [i]ab sofort[/i] für alle Mitglieder des Kurses [u]" . $coursename . "[/u] zugänglich ist!\n\nViel Spaß und Gruß,\nAnton Admin', '[b]Hallo zusammen![/b]\n\nIch freue mich euch mitteilen zu könne, dass die Siete [i]ab sofort[/i] für alle Mitglieder des Kurses [u]" . $coursename . "[/u] zugänglich ist!\n\nViel Spaß und Gruß,\nAnton Admin[/b]', 1, " . mktime(13, 37, 00, date('m'), date('d') - 6, date('Y')) . ", 4, '', 0);
			";
		$db->query($sql);
		
		//
		//	Notify
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "notify` (
			  `file` varchar(64) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
			";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "notify` (`file`) VALUES
			('pmbox.notify.php');
			";
		$db->query($sql);
		
		//
		//	Outbox
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "outbox` (
		  `pmid` bigint(20) NOT NULL AUTO_INCREMENT,
		  `senderid` int(11) NOT NULL,
		  `recieverid` int(11) NOT NULL,
		  `timestamp` int(11) NOT NULL,
		  `subject` varchar(64) NOT NULL,
		  `message` text NOT NULL,
		  PRIMARY KEY (`pmid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
		";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "outbox` (`pmid`, `senderid`, `recieverid`, `timestamp`, `subject`, `message`) VALUES
			(1, 3, 1, " . mktime(date('H'), date('i') - 31, 0, date('m'), date('d'), date('Y')) . ", 'Admin Rechte', 'Gib mir Adminrechte.');
			";
		$db->query($sql);
		
		

		//
		//	Personal_Data
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "personal_data` (
			  `userid` int(11) NOT NULL,
			  `fieldid` int(11) NOT NULL,
			  `value` varchar(256) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
			";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "personal_data` (`userid`, `fieldid`, `value`) VALUES
			(21, 1, ''),
			(21, 2, 'Ungeziefer vernichten'),
			(21, 3, 'Rot'),
			(11, 1, 'X-Men GmbH'),
			(11, 2, 'Manuelle Zerstörung'),
			(11, 3, 'Gelb');";
		$db->query($sql);
		
		//
		//	Personal_Fields
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "personal_fields` (
			  `fieldid` int(11) NOT NULL AUTO_INCREMENT,
			  `value` varchar(64) NOT NULL,
			  PRIMARY KEY (`fieldid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "personal_fields` (`fieldid`, `value`) VALUES
			(1, 'Unternehmen'),
			(2, 'Kenntnisse'),
			(3, 'Lieblingsfarbe');";
		$db->query($sql);
		
		//
		//	Poll
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "poll` (
			  `ID` int(10) NOT NULL AUTO_INCREMENT,
			  `name` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  `button` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'radio',
			  `date` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  `active` int(1) NOT NULL DEFAULT '1',
			  `voted` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  `votes` int(10) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`ID`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "poll` (`ID`, `name`, `button`, `date`, `active`, `voted`, `votes`) VALUES
			(1, 'Wie findet ihr die Seite?', 'radio', '" . mktime(18, 23, 10, date('m'), date('d') - 3, date('Y')) . "', 1, '', 9),
			(2, 'Mittagessen, wo geht ihr am liebsten essen?', 'checkbox', '" . mktime(22, 12, 45, date('m'), date('d') - 2, date('Y')) . "', 1, '', 6);
			";
		$db->query($sql);
		
		//
		//	Poll_Questions
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "poll_questions` (
			  `ID` int(10) NOT NULL AUTO_INCREMENT,
			  `pollID` int(10) NOT NULL,
			  `text` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  `count` int(10) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`ID`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;
			";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "poll_questions` (`ID`, `pollID`, `text`, `count`) VALUES
			(1, 1, 'Stark!', 5),
			(2, 1, 'Sehr gut', 2),
			(3, 1, 'Ok', 1),
			(4, 1, 'Naja', 0),
			(5, 1, 'URL in Browser eingeben?', 1),
			(6, 2, 'Döner', 6),
			(7, 2, 'McDonalds', 2),
			(8, 2, 'Burger King', 3),
			(9, 2, 'Chinese', 3),
			(10, 2, 'Bäcker', 1),
			(11, 2, 'PizzaHut', 2),
			(12, 2, 'Currywurst', 1),
			(13, 2, 'Supermarkt', 3),
			(14, 2, 'Salat', 0),
			(15, 2, 'Anderes (siehe Kommentar)', 1),
			(16, 3, 'Döner', 0),
			(17, 3, 'McDonalds', 0),
			(18, 3, 'Burger King', 0),
			(19, 3, 'Chinese', 0),
			(20, 3, 'Bäcker', 0),
			(21, 3, 'PizzaHut', 0),
			(22, 3, 'Currywurst', 0),
			(23, 3, 'Supermarkt', 0),
			(24, 3, 'Salat', 0),
			(25, 3, 'Anderes (siehe Kommentar)', 0);";
		$db->query($sql);

		//
		//	Post
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "post` (
			  `postid` bigint(20) NOT NULL AUTO_INCREMENT,
			  `threadid` int(11) NOT NULL,
			  `post` text NOT NULL,
			  `userid` int(11) NOT NULL,
			  `timestamp` int(11) NOT NULL,
			  `attachments` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`postid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;
			";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "post` (`postid`, `threadid`, `post`, `userid`, `timestamp`, `attachments`) VALUES
			(1, 1, 'Hallo,\n\nich habe mir jetzt ne neue Yacht gekauft und war 3 Wochen in Hawaii. Was könnte man sich grade sonst noch so leisten, irgendwer ne idee?', 25, " . mktime(14, 17, 13, date('m'), date('d') - 2, date('Y')) . ", ''),
			(2, 2, 'Hey Leute,\n\nich feiere meinen Geburtstag bei mir daheim. Es wird zwar wie jedes Jahr niemand kommen, aber dennoch seit ihr alle eingeladen.\n\nGruß, Marvin\n', 12, " . mktime(0, 42, 0, date('m'), date('d') - 3, date('Y')) . ", ''),
			(3, 3, 'Wie wärs mit eine Lerngruppe für die kommende Matheklausur?', 4, " . mktime(13, 37, 12, date('m'), date('d') - 1, date('Y')) . ", ''),
			(4, 3, 'Ich wär dabei!\n\nraff eh nix...', 5, " . mktime(19, 52, 22, date('m'), date('d') - 1, date('Y')) . ", ''),
			(5, 3, 'Ich auch!\n\nWir wärs mit nächstem Montag?', 14, " . mktime(8, 27, 13, date('m'), date('d'), date('Y')) . ", ''),
			(6, 4, 'Hallo zusammen,\n\ndie X-Men GmbH sucht noch Verstärkung in ihrem Team. Falls ihr Interesse habt könnt ihr mixh gerne ansprechen.\n\nGruß,\nLogan', 11, " . mktime(21, 23, 10, date('m'), date('d') - 3, date('Y')) . ", ''),
			(7, 4, 'Generell hätte ich Interesse.\nWas genau macht ihr so?', 21, " . mktime(12, 24, 32, date('m'), date('d') - 2, date('Y')) . ", '');
			";
		$db->query($sql);
		
		//
		//	Register
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "register` (
			  `userid` int(11) NOT NULL,
			  `eventid` int(11) NOT NULL,
			  `payed` int(1) NOT NULL,
			  `appeared` int(1) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		//
		//	Rights
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "rights` (
			  `name` varchar(64) NOT NULL,
			  `mod` varchar(64) NOT NULL,
			  `description` varchar(64) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "rights` (`name`, `mod`, `description`) VALUES
			('manage', 'stat', ''),
			('sessions', 'admin', ''),
			('users', 'admin', ''),
			('personal_fields', 'admin', ''),
			('groups', 'admin', ''),
			('menu', 'admin', ''),
			('mod', 'admin', ''),
			('config', 'admin', ''),
			('comments', 'admin', ''),
			('content', 'admin', ''),
			('log', 'admin', ''),
			('groupware', 'admin', ''),
			('contact', 'admin', ''),
			('boxes', 'admin', ''),
			('backup', 'admin', ''),
			('manage', 'imprint', ''),
			('manage', 'calendar', ''),
			('editor', 'detailedpoll', ''),
			('manage', 'guestbook', ''),
			('manage', 'events', ''),
			('manage', 'media', ''),
			('upload', 'media', ''),
			('manage', 'news', ''),
			('admin', 'tetris', ''),
			('editor', 'poll', ''),
			('manage', 'board', ''),
			('manage', 'shoutbox', ''),
			('manage', 'smartstudies', '');";
		$db->query($sql);
		
		//
		//	Shoutbox
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "shoutbox` (
			  `shoutid` bigint(20) NOT NULL AUTO_INCREMENT,
			  `userid` int(11) NOT NULL,
			  `timestamp` int(11) NOT NULL,
			  `text` varchar(255) NOT NULL,
			  PRIMARY KEY (`shoutid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "shoutbox` (`shoutid`, `userid`, `timestamp`, `text`) VALUES
			(1, 1, " . mktime(12, 41, 15, date('m'), date('d') - 2, date('Y')) . ", 'Hey zusammen!'),
			(2, 3, " . mktime(16, 32, 23, date('m'), date('d') - 1, date('Y')) . ", 'Eh, was geht?'),
			(3, 6, " . mktime(21, 21, 53, date('m'), date('d') - 1, date('Y')) . ", 'Laaaangweillig'),
			(4, 11, " . mktime(13, 13, 42, date('m'), date('d') - 1, date('Y')) . ", 'Interesanter[url=http://www.apfelsoft.net/article.html?categoryid=15&articleid=21] Artikel[/url]'),
			(5, 21, " . mktime(date('H'), date('i') - 10, 0, date('m'), date('d'), date('Y')) . ", 'Wer kommt mit heute Vampire jagen?');
			";
		$db->query($sql);
		
		//
		//	Stat
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "stat` (
			  `userid` int(11) NOT NULL,
			  `timestamp` int(11) NOT NULL,
			  `duration` int(11) NOT NULL,
			  `last_action` int(11) NOT NULL,
			  `referer` varchar(1023) DEFAULT NULL,
			  `browseragent` varchar(255) DEFAULT NULL,
			  `os` varchar(255) DEFAULT NULL,
			  `ipadress` varchar(16) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);

		//
		//	Tetris_Attack
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tetris_attack` (
			  `nickname` varchar(255) NOT NULL,
			  `rows` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		//
		//	Tetris_Chat
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tetris_chat` (
			  `chatid` bigint(20) NOT NULL AUTO_INCREMENT,
			  `type` int(3) NOT NULL,
			  `nickname` varchar(255) NOT NULL,
			  `text` varchar(255) NOT NULL,
			  PRIMARY KEY (`chatid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;";
		$db->query($sql);
		
		//
		//	Tetris_Highscore
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tetris_highscore` (
			  `nickname` varchar(255) NOT NULL,
			  `score` bigint(20) NOT NULL,
			  `lines` bigint(20) NOT NULL,
			  `level` int(11) NOT NULL,
			  `timestamp` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "tetris_highscore` (`nickname`, `score`, `lines`, `level`, `timestamp`) VALUES
			('Chuck', 9999999999, 999999, 99999, " . mktime(13, 37, 00, date('m'), date('d') - 4, date('Y')) . "),
			('Marvin', 5000001, 356, 35, " . mktime(12, 11, 15, date('m'), date('d') - 3, date('Y')) . "),
			('Gabi', 601235, 281, 28, " . mktime(16, 22, 13, date('m'), date('d') - 32, date('Y')) . "),
			('Admin', 50000, 103, 10, " . mktime(3, 51, 13, date('m'), date('d') - 1, date('Y')) . "),
			('Spartian', 9000, 23, 2, " . mktime(10, 12, 23, date('m'), date('d') - 2, date('Y')) . "),
			('Noob', 403, 2, 1, " . mktime(3, 51, 13, date('m'), date('d') - 1, date('Y')) . ");";
		$db->query($sql);
		
		//
		//	Tetris_Player
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tetris_player` (
			  `nickname` varchar(255) NOT NULL,
			  `ipadress` varchar(31) NOT NULL,
			  `score` bigint(20) NOT NULL,
			  `level` int(11) NOT NULL,
			  `last_action` int(11) NOT NULL,
			  `last_real_action` int(11) NOT NULL,
			  `alive` int(1) NOT NULL,
			  `master` int(1) NOT NULL,
			  `field` varchar(512) NOT NULL,
			  `uniquid` varchar(100) NOT NULL,
			  `games` int(5) NOT NULL,
			  `wins` int(5) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		//
		//	Tetris_Start
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "tetris_start` (
		  `nickname` varchar(255) NOT NULL,
		  `seed` int(10) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		//
		//	Thread
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "thread` (
			  `threadid` int(11) NOT NULL AUTO_INCREMENT,
			  `boardid` int(11) NOT NULL,
			  `thread` varchar(64) NOT NULL,
			  `userid` int(11) NOT NULL,
			  `lastpost` int(11) NOT NULL,
			  `sticky` int(1) NOT NULL,
			  `hits` int(11) NOT NULL,
			  `closed` int(1) NOT NULL,
			  PRIMARY KEY (`threadid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;";
		$db->query($sql);
		
		$sql = "INSERT INTO `" . MYSQL_TABLE_PREFIX . "thread` (`threadid`, `boardid`, `thread`, `userid`, `lastpost`, `sticky`, `hits`, `closed`) VALUES
			(1, 1, 'Was macht ihr so mit eurem vielen Geld', 25, 1, 0, 0, 0),
			(2, 3, 'Geburtstagsparty', 12, 2, 0, 0, 0),
			(3, 2, 'Mathe Lerngruppe', 4, 5, 0, 0, 0),
			(4, 1, 'Suche Verstärkung im Team', 11, 7, 0, 0, 0);";
		$db->query($sql);
		
		//
		//	Thread_Abo
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "thread_abo` (
			  `userid` int(11) NOT NULL,
			  `threadid` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$db->query($sql);
		
		//
		//	Events
		//
		$sql = "CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "events` (
			  `eventid` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(64) NOT NULL,
			  `description` varchar(512) NOT NULL,
			  `start` int(11) NOT NULL,
			  `end` int(11) NOT NULL,
			  `reg_start` int(11) NOT NULL,
			  `reg_end` int(11) NOT NULL,
			  `min_age` int(11) NOT NULL,
			  `agb` text NOT NULL,
			  `last_check` int(11) NOT NULL,
			  `login_active` int(1) NOT NULL,
			  `seats` int(11) NOT NULL,
			  `free` int(1) NOT NULL,
			  `credits` int(11) NOT NULL,
			  PRIMARY KEY (`eventid`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$db->query($sql);
		
	}
	
?>