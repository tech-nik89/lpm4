<?php
	
	$rights->registerRight('portal', 'manage');
	
	$config->register('portal', 'topnews', '1', 'bool', 'Displays the topnews.');
	$config->register('portal', 'news', '1', 'bool', 'Displays a news overview.');
	$config->register('portal', 'poll', '0', 'bool', 'Displays a poll overview.');
	$config->register('portal', 'posts', '1', 'bool', 'Displays a post overview.');
	$config->register('portal', 'calendar', '0', 'bool', 'Displays a calendar overview.');
	$config->register('portal', 'article', '0', 'bool', 'Displays an article overview.');
	
?>