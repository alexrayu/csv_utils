<?php

/**
 * @file
 *  CSV Merge is a tool to merge multiple csv's based on a specified field
 * serving as the main key.
 */

// Main id.
define('KEY_ID', 'id');

// Alternative id.
define('ALT_ID', 'lid');

// Concatenate string.
define('CNC_STR', ',');

include 'common.php';

// CSV Files. First will be used as base.
$base = 'main_src.csv';
$append = [
  'append_src_1.csv',
];
$output_file_name = 'output.csv';
$header = [];
$data = [];

// Get the base file.
$csvsource = getCSV($base);
$data = $csvsource['data'];
$header = $csvsource['header'];

// Merge other files.
foreach ($append as $source) {
  $csvsource = getCSVOtherID($source);
  foreach ($data as $key => &$value) {
    if (isset($csvsource['data'][$key])) {
      unset($csvsource['data'][$key][KEY_ID]);
      $added = [];
      foreach ($csvsource['data'][$key] as $altkey => $altvalue) {
        $added[] = $altvalue[ALT_ID];
      }
      $added_data = [ALT_ID => implode(CNC_STR, $added)];
      $value = array_merge($value, $added_data);
    }
  }
}

array_push($header, ALT_ID);
array_unshift($data, $header);

putCSV($output_file_name, $data);

print 'Append complete.' . "\n";
exit;
