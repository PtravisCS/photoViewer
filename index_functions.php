<?php

  require_once __DIR__ . '/getFileDate.php';
  require_once __DIR__ . '/str_contains.php';

  function true_basename($image) {
    $formats = ['.jpg', '.png', '.gif', '.apng', '.avif', '.jpeg', '.svg', '.webp', '.bmp', '.tiff', '.mp4', '.ogg'];
    $img = $image;

    foreach ($formats as $format) {
      $img = basename($img, $format);
    }

    return $img;
  }


  function getImages($images_dir) {
    //{jpg,jpeg,png,gif,mp4}
    $images_raw = glob($images_dir . '*.*', GLOB_BRACE);

    return $images_raw;
  }

  function encodeJsonArray($i) {
      $data = ["photoNum" => $i];
      $encoded_data = json_encode($data);
      $encoded_data = "'".$encoded_data."'";

      return $encoded_data;
  }

  function generateHeaderHTML($date, $j) {
    $header_html = '<h3 id="' . 'date' . $j . '" class="dateHeader" >' . $date . '</h3>';

    return $header_html;
  }

  function generateImageHTML($images_relative, $i, $encoded_data) {
    $image = $images_relative[$i]['img'];

    $image_formats = ['jpg', 'png', 'gif', 'apng', 'avif', 'jpeg', 'svg', 'webp', 'bmp', 'tiff'];
    $ext = pathinfo($image)['extension'];
    $images_html = '';

    if (!in_array($ext, $image_formats)) {
      $images_html =
        //'<img src="'.$images_relative["images"][$i].'" name="'.$i.'" id="'.$i.
        //'" class="thumbnail-photo" onClick=redirect(\'./slideShow.php\','.$encoded_data .') loading="lazy" />';
        '<img src="./thumb.php?img='.basename($image, '.jpg').'" name="'.$i.'" id="'.$i.
        '" class="thumbnail-photo" onClick=redirect(\'./slideShow.php\','.$encoded_data .') loading="lazy" />';
    } else {
      $images_html =
        '<video class="thumbnail-photo" id="' . $i . '" loading="lazy" controls>' .
        '<source src="' . $images_relative[$i]['img'] . '" />' .
        '</video>';
    }

    return $images_html;
  }

  function generateImages($images_relative) {
    $images_html = "";

    $date = $images_relative[0]['date'];
    $j = 0;

    $images_html = $images_html . generateHeaderHTML($date, $j);

    for($i = 0; $i < count($images_relative); $i++) {
      if ($date != ($images_relative[$i]['date'])) {
        $j++;
        $date = $images_relative[$i]['date'];
        $images_html = $images_html.generateHeaderHTML($date, $j);
      }

      $encoded_data = encodeJsonArray($i);
      $images_html = $images_html.generateImageHTML($images_relative, $i, $encoded_data);
    }

    return $images_html;
  }

?>
