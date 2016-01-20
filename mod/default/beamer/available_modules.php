<?php

	$available[] = array('name' => '', 'url' => '');
	$available[] = array('name' => $lang->get('news'), 'url' => makeURL('news'));
	$available[] = array('name' => $lang->get('tournament_overview'), 'url' => makeURL('tournament', array('mode' => 'beamer', 'view' => 'overview')));
	$available[] = array('name' => $lang->get('tournament_nextencounters'), 'url' => makeURL('tournament', array('mode' => 'beamer', 'view' => 'nextencounters')));

?>