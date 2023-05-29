<?php

  require_once __DIR__ . '/getFileDate.php';
  require_once __DIR__ . '/../shared_tools/common_functions.php';
  require '../shared_tools/database.php';

  session_start();

  if (is_logged_in()) {
    $username = $_SESSION['username'];
    $profile_picture = $_SESSION['profile_picture'];
    is_admin(); 
  }
  else {
    $username = '';
    $profile_picture = '';
    header('Location: ./index.php'); 
    die;
  }

  if (isset($_FILES['files'])) {
    $num_files = count($_FILES['files']['name']); 
    $target_dir = __DIR__ . '/upload/';
  } else {
    header('Location: ./index.php'); 
    die;
  }
  
  for ($i = 0; $i < $num_files; $i++) {

    $name = $_FILES['files']['name'][$i];
    $tmp_name = $_FILES['files']['tmp_name'][$i];
    $target_file = $target_dir . strtolower(basename($name));

    move_uploaded_file($tmp_name, $target_file);
  }

  exec('cd '.__DIR__.'/upload && ./format.sh'." 2>&1", $out, $res);

  if ($res != 0) {
    echo '<pre>';
    echo print_r($out);
    echo "\n";
    echo print_r($res);
    echo '</pre>';
    echo 'An error was encountered while operating on the uploaded files';
    die;
  }

  header('Location: ./index.php'); 

?>
