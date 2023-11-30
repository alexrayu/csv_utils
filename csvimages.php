 
<?php

/**
 * Map:
 * $data[0] -
 * $data[1] -
 * $data[2] - coded image.
 */

$file_name = getcwd() . '/photos.csv';
if ($handle = fopen($file_name, "r")) {
  fgetcsv($handle);
  while (($data = fgetcsv($handle)) !== FALSE) {
    $source = substr($data[2], 2);
    $image = hex2bin($source);
    file_put_contents('images/' . $data[0] . '.jpg', $image);
  }
  fclose($handle);
}


