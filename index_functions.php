<?php

  require_once __DIR__ . '/getFileDate.php';
  require_once __DIR__ . '/str_contains.php';


  function getImages($images_dir) {

    //{jpg,jpeg,png,gif,mp4}
    $images_raw = glob($images_dir . '*.*', GLOB_BRACE);
    asort($images_raw);

    return $images_raw;

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
        '<video class="thumbnail-photo" id="' . $i . '" loading="lazy" controls>' .
        '<source src="' . $images_relative["images"][$i] . '" />' .
        '</video>';

    }

    return $images_html;

  }

  function generateImages($images_relative) {

    $images_html = "";

    $date = getFileDate($images_relative["images"][0]);
    $j = 0;

    $images_html = $images_html . generateHeaderHTML($date, $j);

    for($i = 0; $i < Count($images_relative["images"]); $i++) {

      if ($date != (getFileDate($images_relative["images"][$i]))) {

        $j++;
        $date = getFileDate($images_relative["images"][$i]);
        $images_html = $images_html . generateHeaderHTML($date, $j);

      }

      $encoded_data = encodeJsonArray($i);

      $images_html = $images_html . generateImageHTML($images_relative, $i, $encoded_data);

    }

    return $images_html;

  }

?>
