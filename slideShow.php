<?php

  require_once __DIR__ . '/dms.php';
  require_once __DIR__ . '/getFileDate.php';
  require_once __DIR__ . '/index_functions.php';
  require_once __DIR__ . '/../shared_tools/common_functions.php';

  session_start();

  if (is_logged_in()) {
    $username = $_SESSION['username'];
    $profile_picture = $_SESSION['profile_picture'];
    is_admin(); 
  }
  else {
    $username = '';
    $profile_picture = '';
  }

  $imagesDir = __DIR__ . '/img/';

  //{jpg,jpeg,png,gif,mp4}
  $images_raw = glob($imagesDir . '*.*', GLOB_BRACE);
  asort($images_raw);
  $num_images = count($images_raw);
  $images_relative = ['images' => [], 'metadata' => []]; 

  for ($i = 0; $i < $num_images; $i++) {

    $image = $images_raw[$i];

    $images_relative['images'][] = './img/' . basename($image);
    if (str_contains(basename($image), '.jpg')) {
      $images_relative['metadata'][] = exif_read_data($image, 'FILE');
    } else {
      $images_relative['metadata'][$i]['DateTimeOriginal'] = getFileDate($images_relative['images'][$i]);
    }

  } 
  
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['photoNum'])) {

    $seqnum = $_POST['photoNum'];

  } elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['photoNum'])) {

    $seqnum = $_GET['photoNum'] == 'undefined' ? 0 : $_GET['photoNum'];

  } else {

    $seqnum = 0;

  }

?>

<!DOCTYPE html>
<html lang="en">

  <head>
  <title id="title"></title>
    <link rel="stylesheet" href="./css/mss.css" />
    <?php bootstrap_css(); ?>
    <script type="text/javascript">
      var images = <?php echo json_encode($images_relative); ?>;
      var photoNum = <?php echo $seqnum ?>;
    </script>
  </head>

  <body>
    <?php print_navbar($profile_picture, $username); ?>
    <div>
      <a id="backButton" href="index.php#<?php echo $seqnum ?>"/>Back</a>
    </div>
    <div class="buffer">
      <div class="flex-container">
        <div class="arrow">
          <img height="100px" onclick="back_photo()" class="arrow" src="./left_arrow.png" />
        </div> 
        <div class="photo" id="content_port">
          <img class="photo" id="mainImage" src=<?php echo '"' . $images_relative["images"][$seqnum] . '"'; ?> />
        </div>
        <div class="arrow">
          <img height="100px" onclick="forward_photo()" class="arrow" src="./right_arrow.png" />
        </div>  
      </div>
    </div>
    <div class="container mb-1 mt-2">
      <div class="card">
        <div class="card card-body">
          <div class="flex-container">
            <p id="imgNum"></p>
            <p id="sep"></p>
            <p id="date"></p>
          </div>
          <div class="flex-container">
            <a href="" id="location">Location</a>
          </div>
        </div>
      </div>
    </div>
    <div class="container mb-3">
      <div class="card">
        <div class="card card-body">
          <p id="description"><?php echo $images_relative["metadata"][$seqnum]["UserComment"] ?? "No Description"; ?>
          </p>
        </div>
      </div>
    </div>
  </body>

  <script src="./js/main.js"></script>
  <script type="text/javascript">
    var seqnum = <?php echo $seqnum; ?>; 
    goto_photo(seqnum);
  </script>

</html>
