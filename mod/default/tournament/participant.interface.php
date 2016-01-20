<?php
	
	interface participant
	{
		function getName();
		function getUrl();
		function getId();
		function userCanSubmit($enc);
	}
	
?>