<?php
  require 'vendor/autoload.php'; 

	$img = '';
	if (isset($_GET['img'])) {
		$img = $_GET['img'];
	}

	$img = preg_replace('/[^a-zA-Z0-9\-_]+/', '', $img);

  $photo_formats = ['.jpg', '.png', '.gif', '.apng', '.avif', '.jpeg', '.svg', '.webp', '.bmp', '.tiff'];
  $video_formats = ['.mp4', '.ogg'];

  $dir = __DIR__ . '/thumbs/';

  $is_image = false;
  $is_video = false;

  foreach ($photo_formats as $format) {
    if (file_exists($dir.$img.$format)) {
      $img .= $format;
      $is_image = true;
    }
  }

  if (!$is_image) {
    foreach ($video_formats as $format) {
      if (file_exists($dir.$img.$format)) {
        $img .= $format;
        $is_video = true;
      }
    }
  }

  if (!$is_image && !$is_video) {
    http_response_code(404);
    die;
  }
  elseif ($is_image) {
    $str = file_get_contents($dir.$img);

    $file = imagecreatefromstring($str);
    $width = imagesx($file);
    $height= imagesy($file);

    $new_file = imagecreatetruecolor(250, 250);

    imagecopyresampled($new_file, $file, 0, 0, 0, 0, 250, 250, $width, $height);

    header('Content-type:image');
    imagejpeg($new_file);

    die;
  }
  elseif ($is_video) {
    $ffmpeg = FFMpeg\FFMpeg::create();
    $video = $ffmpeg->open($dir.$img);

    //ffmpeg -i $i -ss 00:00:01.000 -vframes 1 "$j.png" > /dev/null 2> /dev/null
    $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0));
    print_r($frame);
  }





