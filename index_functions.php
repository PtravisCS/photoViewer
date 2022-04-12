<?php

  function getImages($images_dir) {

    $images_raw = glob($images_dir . '*.{jpg,jpeg,png,gif,mp4}', GLOB_BRACE);

    return $images_raw;

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

  function generateImages($images_relative) {

    $images_html = "";

    $date = getImageEpochStamp($images_relative, 0);
    $images_html = $images_html . '<h3>' . $date . '</h3>';

    for($i = 0; $i < Count($images_relative["images"]); $i++) {

      if ($date != getImageEpochStamp($images_relative, $i)) {

        $date = getImageEpochStamp($images_relative, $i);
        $images_html = $images_html . '<h3>' . $date . '</h3>';

      }

      $encoded_data = encodeJsonArray($i);

      $images_html = $images_html .
        '<img src="' .
        $images_relative["images"][$i] .
        '" name="' .
        $i .
        '" id="' .
        $i .
        '" class="thumbnail-photo" onClick=redirect(\'./slideShow.php\',' .
        $encoded_data .
        ') loading="lazy" />';

    }

    return $images_html;

  }

?>
