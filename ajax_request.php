<?php
	
	/**
	 * Project: Higher For Hire
	 * File: ajax_request.php
	 * Author: Nikolaus Themessl
	**/
	
	header('Content-Type: text/html; charset=utf-8');

	// session is needed for login and other information
	session_start();
	
	// ++++++++ Include All Engines ++++++++ //
	include('./core.php');
	
	// bbcode
	include('./core/stringparser_bbcode.class.php');
	$bbcode = new StringParser_BBCode();

	// Set language
	global $current_language;
	if (isset($_GET['language']))
		$_SESSION['language'] = $_GET['language'];
	
	@$current_language = $_SESSION['language'];
	if ($current_language == '') {
		@$browser_language = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
		if ($lang->languageAvailable($browser_language))
			$current_language = $browser_language;
		else
			$current_language = $config->get('core', 'language');
	}
	$lang->set($current_language, $mobile);
	
	// generate the complete template path
	@$template_dir = "../" . $mobile . "/" . $template;
	
	// the css path - can be used in the smarty templates
	$smarty->assign('css_path', 'templates/' . $template_dir . '/css');
	
	// same as css path
	$smarty->assign('image_path', 'templates/' . $template_dir . '/images');
	
	// assign the language array
	$smarty->assign('lang', $lang->getAll());
	
	$modName = replaceHtmlEntities($_GET['mod']);
	$fileName = replaceHtmlEntities($_GET['file']);
	$path = 'mod/default/'.$modName.'/'.$fileName.'.php';
	require_once($path);
	
?>