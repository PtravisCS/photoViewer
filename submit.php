<?php

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
  }

  $num_rows = count($_POST['file_name']);
  $basename = './img/';

  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO Photos (ID, username, filename, description, filesize, 
    capture_date, filetype, filePath) values(?, ?, ?, ?, ?, ?, ?, ?)"; 
  $q = $pdo->prepare($sql);

  for ($i = 0; $i < $num_rows; $i++) {

    $filename = $basename.$_POST['file_name'][$i];
    $ext = pathinfo($filename)['extension'];

    $q->execute(array(null, $username, $_POST['file_name'][$i], 
      $_POST['file_desc'][$i], filesize($filename), $_POST['file_date'][$i], $ext, $filename));

  }

  Database::disconnect();

  header('Location: ./index.php'); 

?>
