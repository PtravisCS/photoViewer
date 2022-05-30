<?php

  $imagesDir = '/media/main/www/html/photoViewer/img/';

  $images_raw = glob($imagesDir . '*.{jpg,jpeg,png,gif,mp4}', GLOB_BRACE);
  $images_relative = array("images" => [], "dates" => []); 

  foreach($images_raw as $image) {

    $images_relative["images"][] = './img/' . basename($image);
    $images_relative["metadata"][] = exif_read_data($image, "FILE");

  } 

  
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["photoNum"])) {

    $seqnum = $_POST["photoNum"];

  } else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["photoNum"])) {

    $seqnum = $_GET["photoNum"] == "undefined"? 0: $_GET["photoNum"];

  } else {

    $seqnum = 0;

  }

?>

<!DOCTYPE html>
<html lang="en">

  <head>
  <title id="title"></title>
    <link rel="stylesheet" href="./css/mss.css" />
    <script type="text/javascript">
      var images = <?php echo json_encode($images_relative); ?>;
      var photoNum = <?php echo $seqnum ?>;
    </script>
  </head>

  <body>
    <div>
      <a id="backButton" href="index.php#<?php echo $seqnum ?>"/>Back</a>
    </div>
    <div class="buffer">
      <div class="flex-container">
        <div class="arrow">
          <img height="100px" onclick="back_photo()" class="arrow" src="./left_arrow.png" />
        </div> 
        <img class="photo" id="mainImage" src=<?php echo '"' . $images_relative["images"][$seqnum] . '"'; ?> />
        <div class="arrow">
          <img height="100px" onclick="forward_photo()" class="arrow" src="./right_arrow.png" />
        </div>  
      </div>
    </div>
    <div class="flex-container">
      <p><?php echo $images_relative["metadata"][$seqnum]["COMPUTED"]["UserComment"] ?? "No Description"; ?></p>
    </div>
    <div class="flex-container">
      <p id="imgNum"></p>
      <p id="sep"></p>
      <p id="date"></p>
    </div>
  </body>

  <script src="./js/main.js"></script>
  <script type="text/javascript">
    var seqnum = <?php echo $seqnum; ?>; 
    goto_photo(seqnum);
  </script>

</html>
