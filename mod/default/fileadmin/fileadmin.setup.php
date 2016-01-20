<?php
	
	$rights->registerRight('fileadmin', 'use');
	$config->register('fileadmin', 'max-upload-size', '10485760', 'int', 'Specifies the maximum file size in bytes you can upload.'); 
	
?>