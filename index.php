<?php

  $imagesDir = '/media/main/www/html/photoViewer/thumbs/';

  $images_raw = glob($imagesDir . '*.{jpg,jpeg,png,gif,mp4}', GLOB_BRACE);
  $images_relative = array("images" => [], "dates" => []); 

  foreach($images_raw as $image) {

    $images_relative["images"][] = './img/' . basename($image);
    $images_relative["metadata"][] = exif_read_data($image, "FILE");

  } 

?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <title>Photos</title>
    <link rel="stylesheet" href="./css/mss.css" />
    <script type="text/javascript">
      var images = <?php echo json_encode($images_relative); ?>;
    </script>
  </head>

  <body>
    <div id="outer-container" class="buffer">
      <h3>Bay City Photos</h3>
      <div class="wrap-flex">
        <?php
          for($i = 0; $i < Count($images_relative["images"]); $i++) {
            $data = array("photoNum" => $i);
            $encoded_data = json_encode($data);
            $encoded_data = "'" . $encoded_data . "'";
            echo '<img src="' . $images_relative["images"][$i] . '" name="' . $i . '" id="' . $i . '" class="thumbnail-photo" onClick=redirect(\'./slideShow.php\',' . $encoded_data . ') loading="lazy" />';
          }
        ?>
      </div>
    </div>
  </body>

  <script src="./js/main.js"></script>

</html>
