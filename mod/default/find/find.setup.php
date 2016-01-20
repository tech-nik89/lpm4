<?php

	$config->register('find', 'results-limit', 20, 'int', 'Specifies the number of results shown in the search results.');
	$config->register('find', 'no-results-redirect', '', 'string', 'Specifies a target url where the user will be redirected to if there are no results found. Placeholder %language% can be used.');

?>