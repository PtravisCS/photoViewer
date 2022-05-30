<?php
  
  /*
  exec("identify -verbose ./thumbs/IMG_20220316_150158.jpg", $output, $result);

  echo '<pre>';
  print_r($output[132]);
  echo '</pre>';
  */

  require_once __DIR__ . '/index_functions.php';

  $images_dir = '/media/main/www/html/photoViewer/thumbs/';

  $images_raw = getImages($images_dir);

  $images_relative = array("images" => [], "dates" => []); 

  foreach($images_raw as $image) {

    $images_relative["images"][] = './thumbs/' . basename($image);

    if (!str_contains(basename($image), ".mp4")) {
      $images_relative["metadata"][] = exif_read_data($image, "FILE");
    }

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
