<?php

	/**
	 * Project: Higher For Hire
	 * File: smarty.core.php
	 *
	**/

	class Language
	{
		// the file containing the language after loading
		private $lang = array();
		
		// name of the current language
		var $currentLanguage;
		
		private $mobile;
		
		function set($language, $mobile)
		{
			$this->currentLanguage = $language;
			$this->mobile = $mobile;
			
			// Include lang-file

			$path = './lang/lang_' . $language . '.php';
			
			// file exists?
			if ($handle = @fopen($path, 'rb'))
			{
				// Load ...
				include($path);
				
				// ... and put into var.
				// $lang is in lang_XX.php
				$this->lang = $lang;
				
			}
			else
			{
				notify::raiseError('Language File', 'Language File lang_' . $language . '.php not found.');
			}
			
		}
		
		function addModSpecificLocalization($mod)
		{
			global $debug;
			
			// create path
			$path = './mod/default/' . $mod . '/' . $this->currentLanguage . '.lang.php';
			
			// check if file exists
			if (!$handle = @fopen ($path, "rb"))
			{
				$alternative_language = $this->getAnyAvailableLanguage($mod);
				$path = './mod/' . $this->mobile . '/' . $mod . '/' . $alternative_language . '.lang.php';
				$debug->add('Localization of ' . $mod, 'The localization file for language <em>' . $this->currentLanguage . '</em> could not be found. Using <em>' . $alternative_language . '</em> instead.');
			}
			
			include($path);
			
			foreach ($lang as $key => $val)
			{
				$this->lang[$key] = $val;
			}
		}
		
		// you need a translation? here it is.
		function get($key)
		{
			return @$this->lang[$key];
		}
		
		// here we get all translations
		function getAll()
		{
			return $this->lang;
		}
		
		// list all available languages
		function listLanguages()
		{
			// scan mod directory
			$path = './lang/';
			$list = scandir($path);
			
			// remove . and ..
			foreach ($list as $val)
			{
				if ($val != '.'
					&& $val != '..'
					&& $val != '.svn')
				{	
					
					// remove lang_
					$x = substr($val, 5);
					
					// remove .php
					$x = substr($val, 0, -4);
					
					$out[] = substr($x, 5);
					
				}
			}
			
			return $out;
		}
		
		function getAnyAvailableLanguage($mod)
		{
			$path = './mod/default/' . $mod . '/';
			$list = scandir($path);
			if (count($list) > 0)
				foreach ($list as $l)
				{
					if ( preg_match("/^[a-z]{2}(.lang.php)$/", $l) )
						return substr($l, 0, 2);
				}
			}
			
		function languageAvailable($lng) {
			$path = './lang/lang_' . $lng . '.php';
			return file_exists($path);
		}
	}

?>