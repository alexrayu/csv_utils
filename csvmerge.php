<?php

/**
 * @file
 *  CSV Merge is a tool to merge multiple csv's based on a specified field
 * serving as the main key.
 */

define('KEY_ID', 'id');
define('FOLDER', '');

include 'common.php';

// CSV Files. First will be used as base.
$sources = [
  FOLDER . 'input1.csv',
  FOLDER . 'input2.csv',
];
$output_file_name = FOLDER . 'output.csv';
$header = [];
$data = [];

// Get the base file.
$csvsource = getCSV(array_shift($sources));
$data = $csvsource['data'];
$header = $csvsource['header'];

// Merge other files.
foreach ($sources as $source) {
  $csvsource = getCSV($source);
  foreach ($data as $key => &$value) {
    if (isset($csvsource['data'][$key])) {
      unset($csvsource['data'][$key][KEY_ID]);
      $value = array_merge($value, $csvsource['data'][$key]);
    }
  }

  if (($header_dup_id_pos = array_search(KEY_ID, $csvsource['header'])) !== false) {
    unset($csvsource['header'][$header_dup_id_pos]);
  }
  $header = array_merge($header, $csvsource['header']);
}

array_unshift($data, $header);
putCSV($output_file_name, $data);

print 'Merge complete.' . "\n";
exit;
