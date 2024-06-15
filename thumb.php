<?php

	$img = '';
	if (isset($_GET['img'])) {
		$img = $_GET['img'];
	}

	$img = preg_replace('/[^a-zA-Z0-9\-_]+/', '', $img);
	$img .= '.jpg';

	$file = imagecreatefromjpeg(__DIR__ . '/thumbs/' . $img);
	$width = imagesx($file);
	$height= imagesy($file);

	$new_file = imagecreatetruecolor(250, 250);

	imagecopyresampled($new_file, $file, 0, 0, 0, 0, 250, 250, $width, $height);

	header('Content-type:image/jpg');
	imagejpeg($new_file);
