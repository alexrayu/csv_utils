<?php

/**
 * @file
 *  CSV Common functions.
 */

/**
 * Get content of csv file.
 *
 * @param string $file_name
 *   File name to import.
 * @param string $key_id
 *   Identifier for the main (id) key.
 * @param int $header_rows
 *   How many rows are in the header.
 *
 * @return array
 *   Imported CSV.
 */
function getCSV($file_name, $key_id, $header_rows = 1) {
  $data = [];
  $header = [];
  $new_key = FALSE;
  $pos_id = 0;
  if ($handle = fopen($file_name, "r")) {
    for ($i = 0; $i < $header_rows; $i++) {
      $header = fgetcsv($handle);
      if (array_search($key_id, $header) === FALSE) {
        $new_key = TRUE;
        array_unshift($header, $key_id);
      }
    }
    while (($fragment = fgetcsv($handle)) !== FALSE) {
      if ($new_key) {
        array_unshift($fragment, $pos_id);
        $joined = array_combine($header, $fragment);
        $data[$pos_id] = $joined;
        $pos_id++;
      }
      else {
        $joined = array_combine($header, $fragment);
        $data[$joined[$key_id]] = $joined;
      }
    }
    fclose($handle);
  }

  return ['header' => $header, 'data' => $data];
}

/**
 * Get content of csv file by a different id.
 *
 * @param $file_name
 * @param $header_rows
 * @param $other_id
 * @return array
 */
function getCSVOtherID($file_name, $key_id, $header_rows = 1) {
  $data = [];
  $header = [];
  if ($handle = fopen($file_name, "r")) {
    for ($i = 0; $i < $header_rows; $i++) {
      $header = fgetcsv($handle);
    }
    while (($fragment = fgetcsv($handle)) !== FALSE) {
      $joined = array_combine($header, $fragment);
      $data[$joined[$key_id]][$joined[$key_id]] = $joined;
    }
    fclose($handle);
  }

  return ['header' => $header, 'data' => $data];
}

/**
 * Write the csv file.
 *
 * @param $output_file_name
 * @param $data
 * @return bool
 */
function putCSV($output_file_name, $data) {
  if ($handle = fopen($output_file_name, "w")) {
    foreach ($data as $line) {
      fputcsv($handle, $line);
    }
    fclose($handle);

    return TRUE;
  }

  return FALSE;
}
