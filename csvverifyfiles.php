<?php

/**
 * @file
 *  CSV Generate ID is a tool to generate unique ids per row.
 */

define('KEY_ID', 'id');
define('FILENAME_ID', 'iid');
define('FOLDER', '');
define('FILES_FOLDER', 'photos');

include 'common.php';

// CSV Files. First will be used as base.
$source = FOLDER . 'images.csv';
$output_file_name = FOLDER . 'output.csv';
$header = [];
$data = [];
$missing = [];

// Get the base file.
$csvsource = getCSV($source);
$data = $csvsource['data'];
$header = $csvsource['header'];

foreach ($data as $key => $entry) {
  $filename = FILES_FOLDER . '/' . $entry[FILENAME_ID] . '.jpg';
  if (!file_exists($filename)) {
    $missing[] = [$entry[FILENAME_ID], $entry[FILENAME_ID] . '.jpg'];
  }
}

$header = [FILENAME_ID, 'file_name'];
array_unshift($missing, $header);

putCSV($output_file_name, $missing);

print count($missing) - 1 . ' files listed.' . "\n";
exit;
