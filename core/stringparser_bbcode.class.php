<?php
class StringParser_BBCode{

	function parse($text) {
		global $config;
	
		if($config->get('core', 'bbcode') != "1") {
			return $text;
		}
	
		$text = strip_tags($text);
		
		$text = $this->parse_code($text);
		$text = $this->parse_i($text);
		$text = $this->parse_b($text);
		$text = $this->parse_s($text);
		$text = $this->parse_u($text);
		$text = $this->parse_sub($text);
		$text = $this->parse_sup($text);
		$text = $this->parse_list($text);
		$text = $this->parse_hr($text);
		$text = $this->parse_color($text);
		$text = $this->parse_size($text);
		$text = $this->parse_url($text);
		$text = $this->parse_img($text);
		$text = $this->parse_quote($text);
		
		$text = $this->parse_nl($text);
		
		//table
		
		return $text;
	}
	
	function parse_nl($text) {
		return nl2br($text);
	}
	
	function parse_i($text) {
		return $this->parse_tag('i', 'i', $text);
	}
	
	function parse_b($text) {
		return $this->parse_tag('b', 'b', $text);
	}
	
	function parse_s($text) {
		return $this->parse_tag('s', 's', $text);
	}
	
	function parse_u($text) {
		return $this->parse_tag('u', 'u', $text);
	}
	
	function parse_sub($text) {
		return $this->parse_tag('sub', 'sub', $text);
	}
	
	function parse_sup($text) {
		return $this->parse_tag('sup', 'sup', $text);
	}
	
	function parse_list($text) {
		$text = $this->parse_tag('li', 'ul', $text);
		$text = $this->parse_tag('\*', 'li', $text);
		return $text;
	}
	
	function parse_tag($bbtag, $htmltag, $text) {
		return preg_replace('/\['.$bbtag.'\](.*?)\[\/'.$bbtag.'\]/s', '<'.$htmltag.'>$1</'.$htmltag.'>', $text);
	}
	
	function parse_hr($text) {
		return $this->parse_singletag('hr', 'hr', $text);
	}
	
	function parse_singletag($bbtag, $htmltag, $text) {
		return preg_replace('/\['.$bbtag.'\](.*?)/', '<'.$htmltag.' />', $text);
	}
	
	function parse_color($text) {
		return preg_replace('/\[color=\"?#?([[:alnum:]]{6}?)\"?\](.*?)\[\/color\]/', '<span style="color:#$1">$2</span>', $text);  
	}
	
	function parse_size($text) {
		return preg_replace('/\[size=\"?([0-9]*.?[0-9]*)\"?\](.*?)\[\/size\]/', '<span style="font-size:$1em">$2</span>', $text);  
	}
	
	function parse_url($text) {
		$text = " ".$text;
		$text = preg_replace('/([^=\]\"])(http:\/\/[^ \n]+)/', '$1<a href="$2" target="_blank">$2</a>', " ".$text); 
		$text = preg_replace('/\[url=\"?([^ "]+)\"?\](.*)\[\/url\]/', '<a href="$1" target="_blank">$2</a>', $text);
		return  trim($text);
	}
	
	function parse_img($text) {
		return preg_replace('/\[(img|bild)\](.*?)\[\/(img|bild)\]/s', '<img style="max-width:100%;" src="$2" alt="$2"/>', $text);
	}
	
	function parse_quote($text) {
		return preg_replace('/\[quote\](.*?)\[\/quote\]/s', '<div class="quote">$1</div>', $text);
	}
	
	function parse_code($text) {
		return preg_replace('/\[code\](.*?)\[\/code\]/e', "'<div class=\"code\">'.replaceBars('\\1').'</div>'", $text);
	}
}

?>