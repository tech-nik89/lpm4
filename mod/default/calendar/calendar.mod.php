<?php
	global $tbl;
	global $tpl_dir;
	$tpl_dir = $template_dir;
	$tbl = MYSQL_TABLE_PREFIX.'calendar';
	
	function writeExport() {
		global $config, $mod_dir;
		$path = 'media/ical/calendar.ics';
		
		if ($config->get('calendar', 'enable-ical-export') == '1') {
			// @unlink($path);
			require_once($mod_dir."/import_export.function.php");
			$ical = ical_export();
			$h = fopen($path, "w+");
			fwrite($h, $ical);
			fclose($h);
		}
	}
	
	global $isallowed;
	$isallowed = $rights->isAllowed($mod, 'manage');
	
	$lang->addModSpecificLocalization($mod);
	require_once($mod_dir."/calendar.function.php");
	
	$breadcrumbs->addElement($lang->get('calendar'), makeURL($mod));
	
	$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
	$view = isset($_GET['view']) ? $_GET['view'] : $config->get($mod, 'default-view');
	$day = isset($_GET['day']) ? (int)$_GET['day'] : 0;
	$calendarid = isset($_GET['calendarid']) ? (int)$_GET['calendarid'] : 0;
	
	if ($day == 0) $day = time();
	
	$menu->addSubElement($mod, $lang->get('switch_date'), 'switch_date', array('day' => $day, 'view' => $view));
	$menu->addSubElement($mod, $lang->get('today'), '', array('day' => time(), 'view' => $view));

	// new entry submenu
	if ($login->currentUser() !== false) {
		$menu->addSubElement($mod, $lang->get('new_calendar_entry'), 'new', array('day' => $day, 'view' => $view));
	}
	
	$smarty->assign('languages', $lang->listLanguages());
	
	switch ($mode) {
		default:
			// Show calendar (day, week, month, year or next events)
			switch ($view) {
				default:
				case 'day':
					require_once($mod_dir."/day.view.php");
					break;
					
				case 'month':
					require_once($mod_dir."/month.view.php");
					break;
					
				case 'week':
					require_once($mod_dir."/week.view.php");
					break;
					
				case 'year':
					require_once($mod_dir."/year.view.php");
					break;
					
				case 'next':
					require_once($mod_dir."/next.view.php");
					break;
			}
			
			break;
			
		case 'new':
			if ($login->currentUser() === false)
				break;
				
			// new entry
			require_once($mod_dir."/new.php");
			break;
			
		case 'edit':
			// edit entry
			require_once($mod_dir."/edit.php");
			break;
			
		case 'view':
			// view entry
			require_once($mod_dir."/entry.view.php");
			break;
			
		case 'switch_date':
			// change current date
			require_once($mod_dir."/switch_date.php");
			break;
		case 'import_ical':
			// Import data from ical
			require_once($mod_dir."/import_ical.php");
			break;
		case 'ical':
			// Show information how to use ical export
			require_once($mod_dir."/ical.php");
			break;
		case 'categories':
			if($isallowed) {
				require_once($mod_dir."/categories.php");
			}
			break;
	}
	
	// views submenus
	$switch = array('day', 'week', 'month', 'year', 'next');
	$switch_menu = array();
	foreach ($switch as $item) {
		if ($config->get('calendar', 'hide-'.$item.'-view') != '1') {
			$switch_menu[] = array(
				'url' => makeURL($mod, array('day' => $day, 'view' => $item)),
				'title' => $lang->get('view_'.$item),
				'active' => $view != $item
			);
		}
	}
	$smarty->assign('switch_menu', $switch_menu);
	$smarty->assign('view', $view);
	
	if ($isallowed) {
		$menu->addSubElement($mod, $lang->get('import_ical'), 'import_ical');
		$menu->addSubElement($mod, $lang->get('categories'), 'categories');
	}
	
	if ($config->get('calendar', 'enable-ical-export') == '1') {
		$menu->addSubElement($mod, $lang->get('ical_info'), 'ical');
	}
?>