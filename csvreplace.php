<?php

/**
 * @file
 *  CSV Merge is a tool to merge multiple csv's based on a specified field
 * serving as the main key.
 */

// Main id.
define('KEY_ID', 'sku');

// Alternative id.
define('ALT_ID', 'files');

// Concatenate string.
define('CNC_STR', ',');
define('FOLDER', '');

include 'common.php';

// CSV Files. First will be used as base.
$base = FOLDER . 'source.csv';
$replace = FOLDER . 'replace.csv';
$output_file_name = FOLDER . 'output.csv';
$header = [];
$data = [];

// Get the files.
$csvsource = getCSV($base);
$data = $csvsource['data'];
$header = $csvsource['header'];

// Merge other files.
$csvreplace = getCSV($replace);
$replace_data = $csvreplace['data'];
$replace_header = $csvreplace['header'];

foreach ($data as $key => &$value) {
  $value[ALT_ID] = strtolower($value[ALT_ID]);
  foreach ($csvreplace['data'] as $rep_key => $replace_array) {
    $search_item = trim(strtolower($replace_array[ALT_ID]));
    $replace_item = trim(strtolower($replace_array['replace']));
    $value[ALT_ID] = str_replace($search_item, $replace_item, $value[ALT_ID]);
  }
}

array_unshift($data, $header);

putCSV($output_file_name, $data);

print 'Replace complete.' . "\n";
exit;
