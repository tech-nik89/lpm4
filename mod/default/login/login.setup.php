<?php
	
	$config->register('login', 'register-send-email', 0, 'bool', 'Sends a mail after registration.');
	$config->register('login', 'register-activation-required', 0, 'bool', 'Sets if an activation is required.');
	$config->register('login', 'register-mail-subject', '', 'string', 'Sets the subject of the registration mail.');
	$config->register('login', 'register-mail-text', '', 'text', 'Sets the text of the registration mail.');
	$config->register('login', 'register-mail-sender', '', 'string', 'Sets the sender of the registration and password mail.');
	
	$config->register('login', 'register-notification-mail-address', '', 'string', 'Specifies the mail address where the register notification will be sent to.');
	
	$config->register('login', 'lostpw-mail-text', '', 'text', 'Sets the text of the password mail.');
	$config->register('login', 'lostpw-mail-subject', '', 'string', 'Sets the subject of the password mail.');
	
	$config->register('login', 'disable-second-email', 0, 'bool', 'Enables or disables the second e-mail field of registration.');
	$config->register('login', 'disable-nickname', 0, 'bool', 'Disables the nickname field. It will be replaced by pre- and lastname.');
	$config->register('login', 'disable-birthday', 0, 'bool', 'Disables the birthday field.');
	
	$config->register('login', 'register-disable', 0, 'bool', 'Enables or disables the register functionality');
	
	
?>