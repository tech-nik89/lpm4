<?php
	
	/**
	 * Project: Higher For Hire
	 * File: mail.core.php
	 *
	**/
	
	class Mail {
		
		private $vars;
		
		function __construct() {
			$this->vars = array();
		}
		
		function setVar($key, $value) {
			$this->vars[$key] = $value;
		}
		
		function replaceVars($text) {
			$output = $text;
			if (count($this->vars) > 0) {
				foreach ($this->vars as $key => $val) {
					$output = str_replace('{$'.$key.'}', $val, $output);
				}
			}
			return $output;
		}
		
		function send($subject, $text, $reciever, $sender = '') {
			
			global $debug;
			
			if ($sender == '')
				$sender = $this->getRegisterAdress();
				
			if ($sender == '') {
				$debug->add('mail', '<b>No sender adress specified!</b>');
				return false;
			}
			
			if ($subject == '' || $text == '' || $reciever == '') {
				$debug->add('mail', '<b>Not all parameteres where passed/filled!</b>');
				return false;
			}
			
			$debug->add('mail', 'Sending email to ' . $reciever . ' ...');
			
			$header  = "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html; charset=utf-8\r\n";
			
			$header .= "From: ".$sender."\r\n";
			$header .= "Reply-To: ".$sender."\r\n";
			
			$text = $this->replaceVars($text);
			
			$sent = mail($reciever, $subject, $text, $header);
			if ($sent) $debug->add('mail', 'Mail sent successfully.');
			
			return $sent;
			
		}
		
		function getRegisterAdress() {
			global $config;
			return trim($config->get('login', 'register-mail-sender'));
		}
		
		function getAdminAdress() {
			global $config;
			return $config->get('core', 'admin-mail');
		}
	
	}

?>