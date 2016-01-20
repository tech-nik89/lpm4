<?php
	
	$config->register('usercp', 'hide-overview', '0', 'bool', 'Hides the overview submenu entry of the usercp.');
	$config->register('usercp', 'hide-personal', '0', 'bool', 'Hides the personal submenu entry of the usercp.');
	$config->register('usercp', 'hide-avatar', '0', 'bool', 'Hides the avatar submenu entry of the usercp.');
	$config->register('usercp', 'hide-comments', '0', 'bool', 'Hides the my-comments submenu entry of the usercp.');
	$config->register('usercp', 'hide-changepw', '0', 'bool', 'Hides the changepw submenu entry of the usercp.');
	$config->register('usercp', 'hide-company', '1', 'bool', 'Hides the company submenu entry of the usercp');
	$config->register('core', 'allow-html-tags', '1', 'bool', 'Enables or disables html tags in forms.');
	$config->register('usercp', 'disable-editing', '0', 'bool', 'Specifies, if an user is allowed to change his pre-, lastname and birthday.');
	
?>