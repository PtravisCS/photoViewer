<?php

  require_once __DIR__ . '/str_contains.php';

  function getFileDate($image) {

    return getExifTimeStamp($image) ?? /*getImgMagikTimeStamp($image) ??*/ getTitleTimeStamp($image);

  }

  function getImgMagikTimeStamp($image) {

    $image = escapeshellarg($image);

    if (str_contains(basename($image), ".mp4")) {

      //exec("identify -verbose -ping ${image}[0] | grep ", $output, $result);
      exec("ffprobe -v quiet $image -print_format json -show_entries stream=index,codec_type:stream_tags=creation_time:format_tags=creation_time | grep 'creation_time'", $output, $result);

      $date = strtotime($date);

      $date = date("Y-m-d", $date); //H:i:s

      return $output[0];

    }

    exec("identify -verbose -ping $image | grep 'DateTimeOriginal'", $output, $result);

    if (!$output) {
      exec("identify -verbose -ping $image | grep 'GPSDateStamp'", $output, $result);
    }

    if (!$output) {
      exec("identify -verbose -ping $image | grep 'DateTimeDigitized'", $output, $result);
    }

    if (!$output) {
      return NULL;
    } 

  }

  function getTitleTimeStamp($image) {

    preg_match("/(?P<DateTimeOriginal>20[0-9]{2}[0-1]{1}[0-9]{1}[0-3]{1}[0-9]{1}_[0-9]{6})/", basename($image), $output);
    $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})_([0-9]{6})/", "$1-$2-$3T$4", $output["DateTimeOriginal"]);
    $date = strtotime($date);

    $date = date("Y-m-d", $date); //H:i:s

    return $date;

  }

  function getExifTimeStamp($image) {

    if (str_contains(basename($image), ".jpg")) {
      $metadata = exif_read_data($image, "FILE");
    } else {
      return NULL;
    }

    if (isset($metadata["DateTimeOriginal"])) {

      $date = preg_replace("/([0-9]{4}):([0-9]{2}):([0-9]{2})/", "$1-$2-$3T", $metadata["DateTimeOriginal"]);
      $date = preg_replace("/T.*/", "", $date);
      strtotime($date);

    } else {

      return NULL;

    }

    return $date;

  }

?>
