<?php

	$img = '';
	if (isset($_GET['img'])) {
		$img = $_GET['img'];
	}

	$img = preg_replace('/[^a-zA-Z0-9]+/', '', $img);
	$img .= '.jpg';

	$file = file_get_contents(__DIR__ . '/img/' . $img);

	header('Content-type:image/jpg');
	echo $file;