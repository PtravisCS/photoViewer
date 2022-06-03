<?php

  if (!function_exists('str_contains')) {

    function str_contains (string $haystack, string $needle) {

      return $needle !== '' && mb_strpos($haystack, $needle) !== false;

    }

  }

  function getImages($images_dir) {

    $images_raw = glob($images_dir . '*.{jpg,jpeg,png,gif,mp4}', GLOB_BRACE);

    return $images_raw;

  }

  function getVideoEpochStamp($video) {

    preg_match("/(?P<DateTimeOriginal>20[0-9]{2}[0-1]{1}[0-9]{1}[0-3]{1}[0-9]{1})/", $video, $output);
    $output["DateTimeOriginal"] = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "$1-$2-$3T", $output["DateTimeOriginal"]);

    return $output;

  }

  function getImageEpochStamp($images, $i) {

    $date = preg_replace("/([0-9]{4}):([0-9]{2}):([0-9]{2})/", "$1-$2-$3T", $images["metadata"][$i]["DateTimeOriginal"]);
    $date = preg_replace("/T.*/", "", $date);
    strtotime($date);

    return $date;

  }

  function encodeJsonArray($i) {

      $data = array("photoNum" => $i);
      $encoded_data = json_encode($data);
      $encoded_data = "'" . $encoded_data . "'";

      return $encoded_data;

  }

  function generateHeaderHTML($date, $j) {

    $header_html = '<h3 id="' . 'date' . $j . '" class="dateHeader" >' . $date . '</h3>';

    return $header_html;

  }

  function generateImageHTML($images_relative, $i, $encoded_data) {

    if (!str_contains($images_relative["images"][$i], ".mp4")) {

      $images_html = 
        '<img src="' .
        $images_relative["images"][$i] .
        '" name="' .
        $i .
        '" id="' .
        $i .
        '" class="thumbnail-photo" onClick=redirect(\'./slideShow.php\',' .
        $encoded_data .
        ') loading="lazy" />';

    } else {

      $images_html = 
        '<video class="thumbnail-photo" loading="lazy" controls>' .
        '<source src="' . $images_relative["images"][$i] . '" />' .
        '</video>';

    }

    return $images_html;

  }

  function generateImages($images_relative) {

    $images_html = "";

    $date = getImageEpochStamp($images_relative, 0);
    $j = 0;

    $images_html = $images_html . generateHeaderHTML($date, $j);

    for($i = 0; $i < Count($images_relative["images"]); $i++) {

      if ($date != getImageEpochStamp($images_relative, $i)) {

        $j++;
        $date = getImageEpochStamp($images_relative, $i);
        $images_html = $images_html . generateHeaderHTML($date, $j);

      }

      $encoded_data = encodeJsonArray($i);

      $images_html = $images_html . generateImageHTML($images_relative, $i, $encoded_data);

    }

    return $images_html;

  }

?>
