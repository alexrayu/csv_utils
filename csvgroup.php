<?php

/**
 * @file
 *  CSV Generate ID is a tool to generate unique ids per row.
 */

define('KEY_ID', 'sku');
define('GROUP_FIELD', 'group');
define('FOLDER', '');

include 'common.php';

// CSV Files. First will be used as base.
$source = FOLDER . 'source.csv';
$output_file_name = FOLDER . 'output.csv';
$header = [];
$data = [];

// Get the base file.
$csvsource = getCSV($source);
$data = $csvsource['data'];
$header = $csvsource['header'];

array_unshift($header, 'group');
foreach ($data as $key => &$items) {
  $sku = $items['sku'];
  $exploded = explode('-', $sku);
  $base = trim($exploded[0]);
  array_unshift($items, $base);
}

array_unshift($data, $header);

putCSV($output_file_name, $data);

print 'Grouping complete.' . "\n";
exit;
