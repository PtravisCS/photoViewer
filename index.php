<?php
  
  //require_once __DIR__ . '/magikMetaData.php';
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

  $images_dir = __DIR__ . '/thumbs/';

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
    <?php bootstrap_css(); ?>
    <script type="text/javascript">
      var images = <?php echo json_encode($images_relative); ?>;
    </script>
  </head>

  <body>
    <?php print_navbar($profile_picture, $username); ?>
      <?php if (is_logged_in()) { ?>
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-4">
                <form action="./upload.php" method="post" enctype="multipart/form-data">
                  <div class="input-group mb-3">
                    <input type="file" class="form-control" name="files[]" id="files" multiple />
                    <input type="submit" class="btn btn-primary" value="Upload File" name="submit">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    <div class="row">
      <div class="nav nav-pills col-1 ps-2" id="datebar">
      </div>
      <div class="col-11">
        <div data-bs-spy="scroll" data-bs-target="#datebar" data-bs-smooth-scroll="true" class="scrollspy-example-2" tabindex="0">
          <?php
            echo generateImages($images_relative);
          ?>
        </div>
      </div>
    </div>
  </body>

  <script src="./js/main.js"></script>
  <?php bootstrap_js(); ?>
</html>
