<?php
	
	/**
	 * Project: Higher For Hire
	 * File: index.php
	 *
     * This file is part of LAN Party Manager 4.
	 *
	 * LAN Party Manager 4 is licensed under the MIT License. See the 
	 * "LICENSE" file for more details.
	 *
	 *
	 * LAN Party Manager 4 (Higher For Hire) is using Smarty Template Engine
	 * Licenced under the LGPL.
	 *
	 * This library is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU Lesser General Public
	 * License as published by the Free Software Foundation; either
	 * version 2.1 of the License, or (at your option) any later version.
	 *
	 * This library is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	 * Lesser General Public License for more details.
	 *
	 * You should have received a copy of the GNU Lesser General Public
	 * License along with this library; if not, write to the Free Software
	 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	 *
	 * For questions, help, comments, discussion, etc., please join the
	 * Smarty mailing list. Send a blank e-mail to
	 * smarty-discussion-subscribe@googlegroups.com 
	 *
	**/
	
	header('Content-Type: text/html; charset=utf-8');
	
	// Version Number
	$version['major'] = 4;
	$version['minor'] = 2;
	$version['bug'] = 0;
	
	$version['string'] = $version['major'] . "." . $version['minor'] . "." . $version['bug'] . "";
	
	// script runtime start
	$start['core'] = microtime(true);
	
	// session is needed for login and other information
	session_start();
	$_GET['mode'] = isset($_GET['mode']) ? $_GET['mode'] : '';
	
	// ++++++++ Include All Engines ++++++++ //
	$start['initialize'] = microtime(true);
	include('./core.php');
	
	// bbcode
	include('./core/stringparser_bbcode.class.php');
	$bbcode = new StringParser_BBCode();

	$stop['initialize'] = microtime(true);
	
	// Set language
	$start['lang'] = microtime(true);
	global $current_language;
	if (isset($_GET['language']))
		$_SESSION['language'] = $_GET['language'];
	
	@$current_language = $_SESSION['language'];
	if ($current_language == '') {
		$browser_language = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
		if ($lang->languageAvailable($browser_language))
			$current_language = $browser_language;
		else
			$current_language = $config->get('core', 'language');
	}
	$stop['lang'] = microtime(true);
	
	// Check if install.php still exists
	if (file_exists('./install.php') && $config->get('core', 'debug') == 0) {
        $notify->add('Critical Error', $lang->get('install_php_error'));
    }
	
	// Force disable magic quotes if required
	if ($config->get('core', 'disable-magic-quotes') == '1') {
		disable_magic_quotes();
	}
	
	// Module Engine
	$start['initialize::mod'] = microtime(true);
	include('./core/mod.core.php');
	$mod = new Mod();
	$stop['initialize::mod'] = microtime(true);
	
	// +++++++++++ Domains +++++++++++++ //
	$domain = getCurrentDomain();
	@$d_id = (int)$domain['domainid'];
	if (@$d_id > 0) {
		$debug->add('core::domain', $domain['name'] . ' [ ' . $domain['domainid'] . ' ]');
		$template = $domain['template'];
		
		if ($domain['language'] != '') {
			$current_language = $domain['language'];
			$debug->add('core::domain', 'Domain forced language to '.strtoupper($current_language));
		}
	}
	else {
		$debug->add('core::domain', '[ NONE ]');
	}
	
	// Finally, set language after all checks have been made.
	$lang->set($current_language, $mobile);
	
	// +++++++++++ Check Login +++++++++++++ //
	$start['login'] = microtime(true);
	// login information are stored in $_SESSION array
	if ($login->currentUser() !== false) {
		// Someone is logged in
		$current = $login->currentUser();
		$login->updateLastSeen();
		
		// show logout link always
		if ($_GET['mode'] != 'logout' && $config->get('core', 'hide-logout-menu-entry') != '1')
			$menu->addSubElement('login', $current['nickname'] ." ". $lang->get('logout'), 'logout');
		
		// Has this user selected a template?
		if (trim($current['template']) != '') {
			$template = $current['template'];
		}
		else {
			if (@trim($template) == '') {
				$template = $config->get('core', 'template');
			}
		}
	} 
	else {
		if (@$template == '') {
			// Nobody logged in, use the default template
			$template = $config->get('core', 'template');
		}
	}
	
	// if template isn't set yet, we use the default template
	if (trim($template) == '')
		$template = 'default';
	
	$stop['login'] = microtime(true);
	
	// ++++++++++++ Statistic ++++++++++++++ //
	$stat->update();
	
	// +++++++++++++++++++++++++++++++++++++ //
	
	// add notifications from redirect
	if(isset($_SESSION['notifications'])) {
		foreach($_SESSION['notifications'] as $notification) {
			$notify->add($notification['subject'], $notification['message']);
		}
		unset($_SESSION['notifications']);
	}
	
	// check if mod is selected, if not - show default
	$_GET['mod'] = isset($_GET['mod']) ? $_GET['mod'] : '';
	if (trim($_GET['mod']) == '') {
		$load_module = $db->selectOne('menu', 'mod', "`home`=1 AND (`language`='".$current_language."' OR `language`='') AND (`domainid`=".$d_id." OR `domainid`=0)");
		$_GET['mod'] = $load_module;
	}
	
	$start['mod'] = microtime(true);
	
	// open the module
	if ($config->get('core', 'maintenance') == 1) {
		if (!$rights->isAllowed('admin', 'config')) {
			$_GET['mod'] = $_GET['mod'] == 'admin' || $_GET['mod'] == 'login' ? $_GET['mod'] : 'maintenance';
		}
		$notify->add($lang->get('maintenance'), $config->get('core', 'maintenance_description'));
	}
	
	if ($mod->modExists($_GET['mod']))
		if ($config->get('core', 'link-mod-to-menu') != '1' || $mod->isInMenu($_GET['mod'])) {
			$mod->run($_GET['mod']);
		}
		else {
			$mod->run('noaccess');
		}
	else {
		// If the mod doesn't exist, it could be a static page
		if ($content->pageExists($_GET['mod'])) {
			$_GET['key'] = $_GET['mod'];
			$_GET['mod'] = 'content';
			$mod->run('content');
		} else {
			if ($mod->modExists('404'))
				$mod->run('404');
			else
				$notify->raiseError('Module', "The module '404' has been removed from the mod/default folder.");
		}
	}
	
	// Favorites
	if (isset($_POST['btnAddToFavorites']) && $login->currentUser() !== false) {
		// If user is logged in and add button has been pressed, add the current page to the favorites.
		$favorites->addFromCurrent();
	}
	
	$stop['mod'] = microtime(true);
	
	// boxes
	global $box;
	$boxes->runAll();
	$smarty->assign('box', $box);
	
	// run notification files
	$notify->runFiles();
	
	// get the menu
	$include = (bool)$config->get('core', 'include-submenu') == '1';
	$mnu = $menu->getMenu(0, null, null, $include);
	$smarty->assign('menu', $mnu);
	$smarty->assign('submenu', $menu->getSubMenu());
	
	if (@$menu->active_menu_entry['template'] != '') {
		$template = $menu->active_menu_entry['template'];
	}
	
	// if preview (get parameter) is set take this template
	if(isset($_GET['tpl'])) {
		$template = replaceHtmlEntities($_GET['tpl']);
	}
	
	// generate the complete template path
	$template_path = $mobile . "/" . $template;
	
	// check if template exists
	if (!is_dir('templates/'.$template_path)) {
		$template_path = $mobile . '/default';
		if ($mobile != 'mobile')
			$notify->add('Template', "The selected template '" . $template . "' doesn't exist. Using template 'default' instead.");
		
		// if default also doesn't exist, raise error
		if (!is_dir('templates/'.$template_path))
			$notify->raiseError('Template', "The template 'default' doesn't exist.");
	}
	
	// the css path - can be used in the smarty templates
	$smarty->assign('css_path', 'templates/' . $template_path . '/css');
	
	// same as css path
	$smarty->assign('image_path', 'templates/' . $template_path . '/images');
	$smarty->assign('template_path', $template_path);
	
	// set the breadcrumbs
	$smarty->assign('breadcrumbs', $breadcrumbs->get(true, 'breadcrumb_element'));
	
	// notifications
	$smarty->assign('notify', $notify->getAll());
	
	// assign the language array
	$smarty->assign('lang', $lang->getAll());
	
	// assign core values
	$c['version'] = $version;
	$c['title'] = $config->get('core', 'title');
	$head_path = './templates/head.html';
	$h = fopen($head_path, 'r');
	$c['head'] = fread($h, filesize($head_path));
	fclose($h);
	$c['self_url'] = getSelfUrl();
	$c['print_url'] = makeURL($_GET['mod'], array_merge($_GET, array('tpl' => 'print')));
	$c['language'] = $current_language;
	
	// add meta tags
	$meta = $db->selectList('meta', '*', "(`language`='' OR `language`='".$current_language."') AND (`domainid`=0 OR `domainid`=".$d_id.")");
	$t = '';
	foreach ($meta as $m) {
		$t .= '<meta';
		if ($m['name'] != '') {
			$t .= ' name="'.$m['name'].'"';
		}
		if ($m['http_equiv'] != '') {
			$t .= ' http-equiv="'.$m['http_equiv'].'"';
		}
		if ($m['content'] != '') {
			$t .= ' content="'.$m['content'].'"';
		}
		$t .= ' />';
	}
	$c['head'] = $t . $c['head'];
	
	$smarty->assign('core', $c);
	
	// smarty runtime analyse
	$start['smarty'] = microtime(true);
	
	// finally display the entire page
	$smarty->display($template_path . '/index.tpl');
	
	$stop['smarty'] = microtime(true);
	
	// add additional debug information ++++++++++++++++ //
	$debug->add('<b>db::query_count</b>', $db->query_count());
	
	foreach ($_GET as $i => $v)
		@$get[] = $i . " = " . $v;
	$debug->add('$_GET content', @implode(", ", $get));
	
	foreach ($_POST as $i => $v)
		@$post[] = $i . " = " . $v;
	$debug->add('$_POST content', @implode(", ", $post));
	
	foreach ($_SESSION as $i => $v)
		@$session[] = $i . " = " . $v;
	$debug->add('$_SESSION content', @implode(", ", $session));
	$debug->add('core::template-loaded', $template);
	$debug->add('core::template-mobile', $mobile);
	$debug->add('core::language', $current_language);
	$debug->setRuntime(microtime(true)-$start['core']);
	$debug->add('db::average_query_time', $debug->makeBar($db->averageQueryTime()));
	$debug->add('db::sum_query_time', $debug->makeBar($db->sumQueryTime()));
	$debug->add('runtime::initialize', $debug->makeBar($stop['initialize'] - $start['initialize']));
	$debug->add('runtime::lang', $debug->makeBar($stop['lang'] - $start['lang']));
	$debug->add('runtime::initialize::mod', $debug->makeBar($stop['initialize::mod'] - $start['initialize::mod']));
	$debug->add('runtime::login', $debug->makeBar($stop['login'] - $start['login']));
	$debug->add('runtime::mod', $debug->makeBar($stop['mod'] - $start['mod']));
	$debug->add('runtime::smarty', $debug->makeBar($stop['smarty'] - $start['smarty']));
	$debug->add('<b>runtime::core</b>', $debug->makeBar());
	
	// debug output +++++++++++++++++++++++++++++ //
	$debug->show();
	
?>
