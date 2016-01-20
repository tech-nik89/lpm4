<?php
	
	global $current_language;
	
	$result_string_length = 300;
	$smarty->assign('result_string_length', $result_string_length);
	
	$result_limit = (int)$config->get($mod, 'results-limit');
	if ($result_limit == 0) $result_limit = 20;
	$smarty->assign('result_limit', $result_limit);
	
	$lang->addModSpecificLocalization($mod);
	$smarty->assign('path', $template_dir . "/find.tpl");
	
	$breadcrumbs->addElement($lang->get('find'), makeURL($mod));
	
	require_once($mod_dir . "/engines.php");
	
	function strcount($haystack, $needle) {
		$offset = 0;
		$count = 0;
		while (stripos($haystack, $needle, $offset)  !== false) {
			$count++;
			$offset = stripos($haystack, $needle, $offset) + 1;
		}
		return $count;
	}
	
	function highlight($haystack, $needle) {
		$arr_needle = explode(" ", $needle);
		foreach($arr_needle as $item) {
			$search = '/('.$item.')/i';
			$replace = '<span style="background-color:#FFD800;">$1</span>';
			$haystack = preg_replace($search, $replace, $haystack);
		}
		return $haystack;
	}
	
	foreach ($engines as $e)
	{
		if ($e['key'] == '' || $e['key'] == 'users' || $this->isInstalled($e['key'])) {
			$t['key'] = $e['key'];
			$t['name'] = $e['name'];
			$engines2[] = $t;
		}
	}
	$smarty->assign('engines', $engines2);
	@$smarty->assign('engine', $_GET['engine']);
	
	@$search_string = trim(secureMySQL($_GET['q']));
	$smarty->assign('search_string', $search_string);
	
	if (strlen($search_string) >= 3 || $search_string == '') {
	
		// Search button has been pressed
		if ((isset($_GET['find']) || isset($_GET['find_x']) || isset($_GET['q'])) && count($engines) > 0 && $search_string != '')
		{
			$results = array();
			
			if (@$_GET['engine'] == '')
			{
				foreach ($engines as $i => $e)
				{
					if ($e['key'] == 'users' || $this->isInstalled($e['key'])) {
						if ($e['key'] != '') {
							require_once($mod_dir . "/engines/" . $e['file']);
							$result = call_user_func($e['key'], $search_string);
							if (count($result) > 0)
								$results = array_merge($results, $result);
						}
					}
				}
			} else {
				
				require_once($mod_dir . "/engines/" . $engines[$_GET['engine']]['file']);
				$result = call_user_func($_GET['engine'], $search_string);
				if (count($result) > 0)
					$results = array_merge($results, $result);
			}
			
			function cmp($a, $b) {
				if ($a['relevance'] == $b['relevance'])
					return 0;
				return ($a['relevance'] > $b['relevance']) ? -1 : 1;
			}
			
			uasort($results, 'cmp');
			
			$c = 0;
			if (count($results) > 0) {
				foreach ($results as $v) {
					$r[$c] = $v;
					$r[$c]['i'] = $c + 1;
					$r[$c]['description'] = strip_tags($bbcode->parse($r[$c]['description']));
					
					$pos = strpos(strtolower($r[$c]['description']), strtolower($search_string));
					$start = $pos - floor($result_string_length / 2);
					if ($start > 0) {
						$r[$c]['description'] = "...".substr($r[$c]['description'], $start);
					}
					
					$r[$c]['description'] = highlight($r[$c]['description'], $search_string);
					
					$c++;
				}
			}
			else {
				$redirect = $config->get('find', 'no-results-redirect');
				if (trim($redirect) != '') {
					$redirect = str_replace('%language%', $current_language, $redirect);
					redirect($redirect);
				}
			}
			
			@$smarty->assign('results', $r);
		}
	}
	else {
		$notify->add($lang->get('error'), $lang->get('length_error'));
	}
?>