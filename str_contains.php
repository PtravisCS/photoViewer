<?php

if (!function_exists('str_contains')) {

  function str_contains (string $haystack, string $needle) {

    return $needle !== '' && mb_strpos($haystack, $needle) !== false;

  }

}

?>
