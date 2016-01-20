<?php

function resizePicture($file, $width, $height)
{
	
	if(!file_exists($file))
		return false;
	
	header('Content-type: image/jpeg');

	$info = getimagesize($file);

	if($info[2] == 1)
	{
		$image = imagecreatefromgif($file);
	}
	elseif($info[2] == 2)
	{
		$image = imagecreatefromjpeg($file);
	}
	elseif($info[2] == 3)
	{
		$image = imagecreatefrompng($file);
	}
	else
	{
			return false;
	}
	
	if ($width && ($info[0] < $info[1])) 
	{
		$width = ($height / $info[1]) * $info[0];
	} 
	else 
	{
		$height = ($width / $info[0]) * $info[1];
	}

	$imagetc = imagecreatetruecolor($width, $height);

	imagecopyresampled($imagetc, $image, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);

	imagejpeg($imagetc, null, 100);	
	
}

$file = $_GET['file'];

$width = (int)$_GET['width'];
$height = (int)$_GET['width'] / 1.4;
resizePicture($file, $width, $height)
?> 