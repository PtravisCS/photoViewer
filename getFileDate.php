<?php

  require_once __DIR__ . '/str_contains.php';

  function getFileDate($image) {

    return getImageEpochStamp($image) ?? getTitleTimeStamp($image);

  }

  function getTitleTimeStamp($image) {

    preg_match("/(?P<DateTimeOriginal>20[0-9]{2}[0-1]{1}[0-9]{1}[0-3]{1}[0-9]{1}_[0-9]{6})/", basename($image), $output);
    $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})_([0-9]{6})/", "$1-$2-$3T$4", $output["DateTimeOriginal"]);
    $date = strtotime($date);

    $date = date("Y-m-d", $date); //H:i:s

    return $date;

  }

  function getImageEpochStamp($image) {

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
