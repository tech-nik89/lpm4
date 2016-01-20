<?php

	/**
	 * Project: Higher For Hire
	 * File: common.core.php
	 *
	 * Common function collection
	**/
	
	// replaces all umlaute by html entities
	function replaceHtmlEntities($text) {
		$text = trim($text);
		$text = str_replace("&", "&amp;", $text);
		$text = str_replace("Ä","&Auml;", $text);
		$text = str_replace("Ö", "&Ouml;", $text);
		$text = str_replace("Ü", "&Uuml;", $text);
		$text = str_replace("ä", "&auml;", $text);
		$text = str_replace("ö", "&ouml;", $text);
		$text = str_replace("ü", "&uuml;", $text);
		$text = str_replace("ß", "&szlig;", $text);
		$text = str_replace("<", "&lt;", $text);
		$text = str_replace(">", "&gt;", $text);
		$text = str_replace("Ã„", "&Auml;", $text);
		$text = str_replace("Ã–", "&Ouml;", $text);
		$text = str_replace("Ãœ", "&Uuml;", $text);
		$text = str_replace("Ã¤", "&auml;", $text);
		$text = str_replace("Ã¶", "&ouml;", $text);
		$text = str_replace("Ã¼", "&uuml;", $text);
		$text = str_replace("ÃŸ", "&szlig;", $text);
		$text = str_replace('"', "&quot;", $text);
		$text = str_replace("'", "&apos;", $text);
		return $text;
	}
	
	function replaceXmlEntities($text) {
		$text = trim($text);
		$text = str_replace("&", "&amp;", $text);
		$text = str_replace("<", "&lt;", $text);
		$text = str_replace(">", "&gt;", $text);
		$text = str_replace('"', "&quot;", $text);
		$text = str_replace("'", "&apos;", $text);
		return $text;
	}
	
	function replaceBars($text) {
		$text = trim($text);
		$text = str_replace("[", "&#91", $text);
		$text = str_replace("]", "&#93", $text);
		return $text;
	}
	
	// returns if a module is accessable without being in the menu
	function modMustNotBeInMenu($mod) {
		if ($mod == 'maintenance' ||
			$mod == 'profile' ||
			$mod == 'admin')
		return true;
		return false;
	}
	
	// Converts a 0 into no and a 1 into yes
	function intToYesNo($int) {
		global $lang;
		if ($int == 0) return $lang->get('no');
		return $lang->get('yes');
	}
	
	function secureMySQL($input) {
		global $config;
		if ($config->get('core', 'allow-html-tags') != '1') {
			$input = strip_tags($input);
		}
		return 	mysql_real_escape_string(
					convertLineBreaks (
						$input
					)
				);
		
	}
	
	function stripLastName($lastname) {
		return strtoupper(substr($lastname, 0, 1)) . ".";
	}
	
	function convertLineBreaks($input) {
		return preg_replace ("/\015\012|\015|\012/", "\n", $input);
	}
	
	function makeLineBreaks($input) {
		return str_replace("\n", "<br />\n", $input);
	}
	
	// cuts a string and returns it, optional with url to more
	function cutString($string, $length = 50, $url = "") {
		global $lang;
		
		if (strlen($string) > $length) {
			$str = substr($string, 0, $length - 3) . "...";
			
			if ($url != '')
				$str .= ' <a href="' . $url . '">' . $lang->get('more') . '</a>';
				
			return $str;
		}
		else
			return $string;
	}
	
	// returns the html bold version of $string
	function makeHTMLBold($string) {
		return '<strong>'.$string.'</strong>';
	}
	
	// generates a html url
	function makeHTMLURL($name, $url) {
		return '<a href="' . $url . '">' . $name . '</a>';
	}
	
	// generates an url containing modul and several parameters
	function makeURL($mod, $parameter = array(), $sharp = "") {
        global $config, $template;
		$mod_rewrite = $config->get('core', 'mod_rewrite');
		$seperator = "&amp;";
		
		$p = ''; $s = '';
		
		if (trim($sharp) != '')
			$s = "#".$sharp;
			
		if (@$_GET['nomobile'] == '1') {
			$parameter['nomobile'] = '1';
		}
		
		if (isset($_GET['debug-domain'])) {
			$parameter['debug-domain'] = $_GET['debug-domain'];
		}
		
		if (isset($_GET['tpl']))
			$parameter['tpl'] = $template;
			
		if ($mod_rewrite == "1") {
			$first = true;
			foreach ($parameter as $i => $val) {
                if ($first) {
                	$first = false;
                	$p  .= $i . "=" . $val;
                }
                else {
                    $p  .= $seperator . $i . "=" . $val;
    			}
    		}
    		if ($p != '') {
                $url = $mod . ".html?" . $p;
    		}
    		else {
                $url = $mod . ".html";
            }
		}
		else {
            foreach ($parameter as $i => $val) {
    			$p  .= $seperator . $i . "=" . $val;
    		}
    		
			$url = "?mod=" . $mod . $p;
		}
		
		return $url.$s;
	}
	
	// checks if the entered mail adress is valid
	function checkMail($email)
	{
		return preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-.]+\.([a-zA-Z]{2,4})$/", $email);
	}
	
	function listTemplates() {
		// scan mod directory
		$path = './templates/default/';
		$list = scandir($path);
		
		// remove . and ..
		foreach ($list as $val) {
			if ($val != '.'
				&& $val != '..'
				&& $val != '.svn'
				&& $val != 'bbcode.html') {					
					if (file_exists($path . $val . '/index.tpl'))
						$out[] = $val; 
			}
		}
		
		return $out;
	}
	
	function randomPassword($length = 5) {
		$chars = '';
		for ($i = 0; $i < $length; $i++) {
			$type = rand(0, 1);
			switch ($type) {
				case 0:
					$chars .= chr(rand(48, 57));
					break;
				case 1:
					$chars .= chr(rand(97, 122));
					break;
				case 2:
					$chars .= chr(rand(65, 90));
					break;
			}
		}
		
		return $chars;
	}
	
	function uploadButton($dir, $return, $name = '', $append = false) {
		global $lang;
		if ($name == '')
			$name = $lang->get('upload');
		if ($append) $append = 1; else $append = 0;
		
		return "<input type=\"button\" onClick=\"javascript:window.open('core/upload/upload.core.php?dir=" . $dir . "&return=" . $return . "&append=" . $append . "', '_blank', 'width=350, height=200, resizable=no'); return false;\" 
					name=\"upload\" value=\"" . $name . "\" />";
	}
	
	function stringToURL($string){
		
		$special_chars = array(" ", ":", ".", "=", "\"", "§", "$", "%", "&", "/", "(", ")", "?", 
								"`", "´", "'", "#", "+", "~", "*", "_", ",", ";", "{", "}", "[", "]", "^", "<", ">", "|");
		$ae = array("ä", "Ä");
		$oe = array("ö", "Ö");
		$ue = array("ü", "Ü");
		$szlig = "ß";
		
		$s = $string;
		
		$s = str_replace($special_chars, "-", $s);
		$s = str_replace($ae, "ae", $s);
		$s = str_replace($oe, "oe", $s);
		$s = str_replace($ue, "ue", $s);
		$s = str_replace($szlig, "ss", $s);
		
		$s = strtolower($s);
		
		return $s;
	}
	
	function convertLinks($link) {
		global $config;
		
		if ($config->get('board','convert-urls') == 0)
			return $link;
		
		$link = str_replace("http://www.","www.",$link);
		$link = str_replace("www.","http://www.",$link);
		$link = preg_replace(
		"/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","<a href=\"$1\" target=\"_blank\">$1</a>", $link);
		$link = preg_replace(
		"/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.
		([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<a href=\"mailto:$1\" target=\"_blank\">$1</a>",$link);
		
		return $link;
	}
	
	function calculateActivationKey($email, $nickname, $password, $prename) {
		return md5("hfh_" . $nickname . substr($password, 1, 5) . "_" . strtoupper($prename) . "_" . substr(strtolower($email), 0, 5));
	}
	
	function getRemoteAdr() {
	
		if (isSet($_SERVER)) {
			if (isSet($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}
			elseif (isSet($_SERVER["HTTP_CLIENT_IP"])) {
				$realip = $_SERVER["HTTP_CLIENT_IP"];
			}
			else {
				$realip = $_SERVER["REMOTE_ADDR"];
			}
		}
		else {
			if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
				$realip = getenv( 'HTTP_X_FORWARDED_FOR' );
			}
			elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
				$realip = getenv( 'HTTP_CLIENT_IP' );
			}
			else {
				$realip = getenv( 'REMOTE_ADDR' );
			}
		}
		
		if ($realip == '::1')
			$realip = 'localhost';
			
		return $realip;
	}
	
	// a function which makes the index in a desired format
	function makeIndex($count, $type='default') {
		switch($type) {
			case 'alphabet':
				$alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
				$alph_len=26;
				for($i=0; $i<$count; $i++){
					if($i>=$alph_len) {
						$final[$i] = $alphabet[floor($i/$alph_len)-1].$alphabet[$i%$alph_len];
					} else {
						$final[$i] = $alphabet[$i];
					}
				}
				return $final;
			case 'roman':
				$roman = array(1=>'I', 4=>'IV', 5=>'V', 9=>'IX', 10=>'X', 40=>'XL', 50=>'L', 90=>'XC', 100=>'C', 400=>'CD', 500=>'D', 900=>'CM', 1000=>'M');
				$counter = array(1000, 900, 500, 400, 100, 90, 50, 40, 10, 9, 5, 4, 1);
				for($i=1; $i<=$count; $i++){
					$temp=$i;
					$dummy='';
					foreach($counter as $nr)
					{
						while($temp>=$nr) {
							$dummy.=$roman[$nr];	
							$temp-=$nr;
						}
					}					
					$final[$i-1]=$dummy;
				}
				return $final;
			case 'binary':
				$length=ceil(log($count,2));
				for($i=1;$i<=$count; $i++) {
					$temp=$i;
					$dummy='';
					while($temp>0) {
						$dummy=($temp%2).$dummy;
						$temp=floor($temp/2);
					}
					while(strlen($dummy)<$length){
						$dummy='0'.$dummy;
					}
					$final[$i-1]=$dummy;
				}
				return $final;
			case 'pipes':
				$dummy_fives='';
				for($i=0;$i<$count; $i+=5) {
					$dummy_final=$dummy_fives;
					for($k=1;$k<5;$k++) {
						$dummy_final.='|';
						$final[$i+$k-1]=$dummy_final;
					}
					$dummy_fives.='<strike>||||</strike>&nbsp;';
					$final[$i+5-1]=$dummy_fives;
				}
				return $final;
			default: //Numbers
				for($i=1;$i<=$count;$i++) {
					$final[$i-1]=$i;
				}
				return $final;
		}
	}
	
	// Redirects the page immediately to another
	function redirect($url = '') {
		global $config, $notify, $debug;

		$_SESSION['notifications'] = $notify->getAll();

		if (!isset($url) || '' == $url) {
			$url = getSelfURL() . '?' . $_SERVER['QUERY_STRING'];
		}
		if (substr($url, 0, strlen('http://')) != 'http://') {
			$url = getSelfURL() . $url;
		}
		$url = str_replace('&amp;', '&', $url);
		if ($config->get('core', 'mod_rewrite') == '1') {
			$url = str_replace('index.php', '', $url);
		}
		$debug->add('core::redirect', $url);
		header('Location: '.$url);
	}
	
	// Returns true if one of the passed strings is empty
	function allFilled() {
		$args = func_get_args();
		foreach ($args as $arg) {
			if ('' == trim($arg))
				return false;
		}
		return true;
	}
	
	// Returns a formatted style which represents the size of a file.
	function formatFileSize($bytes) {
		$decimals = 2;
		if ($bytes < 1024) return $bytes.' B';
		elseif ($bytes < 1048576) return round($bytes / 1024, $decimals).' KB';
		elseif ($bytes < 1073741824) return round($bytes / 1048576, $decimals).' MB';
		elseif ($bytes < 1099511627776) return round($bytes / 1073741824, $decimals).' GB';
		else return round($bytes / 1099511627776, $decimals).' TB';
	}
	
	// Get self uri
	function getSelfURL() {
		global $debug;
		if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
			$uri = 'https://';
		} else {
			$uri = 'http://';
		}
		$uri .= $_SERVER['HTTP_HOST'];
		$uri .= $_SERVER['PHP_SELF'];
		
		$uri = dirname($uri);
		
		if (substr($uri, -1) != '/')
			$uri .= '/';
		
		$debug->add('core::selfurl', $uri);
		return $uri;
	}
	
	// Force disable magic quotes
	function disable_magic_quotes() {
		if (get_magic_quotes_gpc()) {
			$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
			while (list($key, $val) = each($process)) {
				foreach ($val as $k => $v) {
					unset($process[$key][$k]);
					if (is_array($v)) {
						$process[$key][stripslashes($k)] = $v;
						$process[] = &$process[$key][stripslashes($k)];
					} else {
						$process[$key][stripslashes($k)] = stripslashes($v);
					}
				}
			}
			unset($process);
		}
	}
	
	// Password hash function
	function generatePasswordHash($plaintext) {
		global $config;
		$salt = $config->get('core', 'password-salt');
		if ($salt == '') {
			return md5($plaintext);
		}
		else {
			return md5(sha1($salt.md5($plaintext).$salt));
		}
	}
	
	// Extracts a row or column from a mutlidimensional array
	function array_row($data, $row) {
		$col = array();
		foreach ($data as $record) {
			$col[] = $record[$row];
		}
		return $col;
	}
?>