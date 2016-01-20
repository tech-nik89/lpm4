<?php
	
	/* Calendar Setup File */
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX . "calendar` (
			`calendarid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`userid` INT NOT NULL ,
			`start` INT NOT NULL ,
			`end` INT NOT NULL ,
			`title` VARCHAR( 1023 ) NOT NULL ,
			`description` TEXT NOT NULL ,
			`visible` INT( 1 ) NOT NULL ,
			`language` VARCHAR ( 4 ) NOT NULL ,
			`categoryId` INT( 10 )
			) ENGINE = MYISAM ;
	";
	
	$db->query($sql);
	
	$sql = "
		CREATE TABLE IF NOT EXISTS `" . MYSQL_TABLE_PREFIX ."calendar_categories` (
			`categoryId` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`title` VARCHAR( 100 ) NOT NULL ,
			`backgroundcolor` VARCHAR( 10 ) NOT NULL ,
			`fontcolor` VARCHAR( 10 ) NOT NULL ,
			`description` VARCHAR( 1023 ) NOT NULL
			) ENGINE = MYISAM ;
			";

	$db->query($sql);
	
	$config->register('calendar', 'enable-ical-export', '0', 'bool', 'Enables or disables the ical file export.');
	$config->register('calendar', 'default-view', 'day', 'list', '', 'day,week,month,year,next');
	$config->register('calendar', 'show-birthdays', '1', 'bool', 'Enables or disables the showing of birthdays.');
	$config->register('calendar', 'box-number-of-entries', '3', 'int', 'Specifies the number of entries shown in the calendar box.');
	$config->register('calendar', 'current-event', '0', 'bool', 'Enables or disables the current event counter in the calendar box.');
	$config->register('calendar', 'current-event-refresh-time', '15', 'int', 'Specifies the sync-interval for the current event in seconds.');
	
	$rights->registerRight('calendar', 'manage');
	
	$config->register('calendar', 'hide-day-view', '0', 'bool', 'Hides the day-view.');
	$config->register('calendar', 'hide-week-view', '0', 'bool', 'Hides the week-view.');
	$config->register('calendar', 'hide-month-view', '0', 'bool', 'Hides the month-view.');
	$config->register('calendar', 'hide-year-view', '0', 'bool', 'Hides the year-view.');
	$config->register('calendar', 'hide-next-view', '0', 'bool', 'Hides the next-view.');
	
	$config->register('calendar', 'default-visibility', 'private', 'list', 'Sets the default visibility for new calendar entries.', 'private,logged-in,public');
	
?>