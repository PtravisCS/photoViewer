<?php
  
  //require_once __DIR__ . '/magikMetaData.php';
  require_once __DIR__ . '/index_functions.php';

  /*
  exec("identify -verbose ./thumbs/IMG_20220316_150158.jpg", $output, $result);

  $meta = new magikMetaData();

  foreach ($output as $indice) {

    $arr = explode(":", $indice);
    $result = array();

    for ($i = 0; $i < count($arr) - 1; $i++) {
      $arr[$i] = preg_replace("/(\S)\s{1,}/m", "$1", $arr[$i]);
      //$meta->{$arr[0] . $arr[1]} = $arr[1];

      //echo $arr[$i];
    }

    //echo "<br />";

  }

  echo '<pre>';
  print_r($output);
  echo '</pre>';
   */

  $images_dir = '/media/main/www/html/photoViewer/thumbs/';

  $images_raw = getImages($images_dir);

  $images_relative = array("images" => [], "metadata" => []); 

  foreach($images_raw as $image) {

    $images_relative["images"][] = './thumbs/' . basename($image);

    if (str_contains(basename($image), ".jpg")) {
      $metadata = exif_read_data($image, "FILE") ;
      $images_relative["metadata"][] = $metadata;
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
      <div class="navbar_container">
        <div class="navbar" id="navbar">
        </div> 
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
