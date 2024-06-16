<?php
  require 'vendor/autoload.php'; 

	$img = '';
	if (isset($_GET['img'])) {
		$img = $_GET['img'];
	}

	$img = preg_replace('/[^a-zA-Z0-9\-_]+/', '', $img);

  $photo_formats = ['.jpg', '.png', '.gif', '.apng', '.avif', '.jpeg', '.svg', '.webp', '.bmp', '.tiff'];
  $video_formats = ['.mp4', '.ogg'];

  $dir = __DIR__ . '/img/';

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
    $metadata = @exif_read_data($dir.$img, 'FILE'); 

    $file = imagecreatefromstring($str);
    $width = imagesx($file);
    $height= imagesy($file);

    $gd_image = imagecreatetruecolor(250, 250);

    imagecopyresampled($gd_image, $file, 0, 0, 0, 0, 250, 250, $width, $height);

    $num = rand(0, 100000);
    $filename = '/tmp/photoviewer'.$num.'.jpg';

    $orientation = $metadata['Orientation'];
    
    switch($orientation) {
      case 3:
        $gd_image = imagerotate($gd_image, -180, 0);
        break;
      case 6:
        $gd_image = imagerotate($gd_image, -90, 0);
        break;
      case 8:
        $gd_image = imagerotate($gd_image, -270, 0);
        break;
    }

    imagejpeg($gd_image, $filename, 100);

    exec('exiftool -DateTimeOriginal=\''.$metadata['DateTimeOriginal'].'\' -Orientation \'1\' -- '.$filename, $output);
    $final_file = file_get_contents($filename);

    header('Content-type: image/jpg');
    echo $final_file;
    die;
  }
  elseif ($is_video) {
    $ffmpeg = FFMpeg\FFMpeg::create();
    $video = $ffmpeg->open($dir.$img);

    //ffmpeg -i $i -ss 00:00:01.000 -vframes 1 "$j.png" > /dev/null 2> /dev/null
    $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0));
    $num = rand(0, 100000);
    $frame->save('/tmp/photoviewer'.$num.'.jpg');

    $str = file_get_contents('/tmp/photoviewer'.$num.'.jpg');
    unlink('/tmp/photoviewer'.$num.'.jpg');
    $file = imagecreatefromstring($str);

    $width = imagesx($file);
    $height= imagesy($file);

    $gd_image = imagecreatetruecolor(250, 250);

    imagecopyresampled($gd_image, $file, 0, 0, 0, 0, 250, 250, $width, $height);

    header('Content-type:image');
    imagejpeg($gd_image);

    die;
  }





