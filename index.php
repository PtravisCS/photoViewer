<?php

  require_once __DIR__ . '/index_functions.php';

  $images_dir = '/media/main/www/html/photoViewer/thumbs/';

  $images_raw = getImages($images_dir);

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
    <div class="fullPage">
      <div class="navbar" id="navbar">
      </div>
      <div id="outer-container" class="buffer">
        <div class="wrap-flex">
          <?php
            echo generateImages($images_relative);
          ?>
        </div>
      </div>
    </div>
  </body>

  <script src="./js/main.js"></script>

</html>
