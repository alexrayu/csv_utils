<?php

/**
 * @file
 *  CSV Generate ID is a tool to generate unique ids per row.
 */

define('KEY_ID', 'mid');
define('FOLDER', '');

include 'common.php';

// CSV Files. First will be used as base.
$source = FOLDER . 'message.csv';
$output_file_name = FOLDER . 'output.csv';
$header = [];
$data = [];

// Get the base file.
$csvsource = getCSV($source);
$data = $csvsource['data'];
$header = $csvsource['header'];

array_unshift($data, $header);

putCSV($output_file_name, $data);

print 'ID Generation complete.' . "\n";
exit;
