<?php

/**
 * @file
 *  CSV Generate ID is a tool to generate unique ids per row.
 */

define('FOLDER', '');

include 'common.php';

/**
 * Obituraies base.
 */
$key_id = 'DeathRecordId';
$source = FOLDER . 'obits.csv';
$output_file_name = FOLDER . 'source.csv';

// Get the base file.
$csvsource = getCSV($source, $key_id);
$data = $csvsource['data'];
$header = $csvsource['header'];
$new_header = [
  'id',
  'site_id',
  'created',
  'published',
  'field_service_date',
  'field_bio',
  'field_birthdate',
  'field_city',
  'field_country',
  'field_deathdate',
  'field_first_name',
  'field_middle_name',
  'field_last_name',
  'field_state',
  'field_locations',
  'field_gallery_image',
  'comments',
];
foreach ($data as $id => $item) {
  $new_data[$id] = [
    'id' => $id,
    'site_id' => $item['SiteId'],
    'created' => $item['DateCreated'],
    'published' => $item['Visible'],
    'field_service_date' => $item['ServiceDate'] . ' ' . $item['ServiceTime'],
    'field_bio' => $item['Obituary'],
    'field_birthdate' => $item['DateOfBirthText'],
    'field_city' => $item['PlaceOfDeathItemId'],
    'field_country' => $item['PlaceOfDeathItemId'],
    'field_deathdate' => $item['DateOfDeath'],
    'field_first_name' => $item['GivenName'],
    'field_middle_name' => $item['MiddleName'],
    'field_last_name' => $item['LastName'],
    'field_state' => $item['PlaceOfDeathItemId'],
    'field_locations' => [],
    'field_gallery_image' => '',
    'comments' => '',
  ];

  // Add locations.
  if (!empty($item['VisitationItemId'])) {
    $new_data[$id]['field_locations'][] = [
      'id' => $item['VisitationItemId'],
      'type' => 'visitation',
      'date' => $item['VisitationDate1'] . ' ' . $item['VisitationTime1Start'],
    ];
  }
  if (!empty($item['VisitationItemId2'])) {
    $new_data[$id]['field_locations'][] = [
      'id' => $item['VisitationItemId2'],
      'type' => 'visitation',
      'date' => $item['VisitationDate2'] . ' ' . $item['VisitationTime2Start'],
    ];
  }
  if (!empty($item['VisitationItemId3'])) {
    $new_data[$id]['field_locations'][] = [
      'id' => $item['VisitationItemId3'],
      'type' => 'visitation',
      'date' => $item['VisitationDate3'] . ' ' . $item['VisitationTime3Start'],
    ];
  }
  if (!empty($item['ServiceItemId'])) {
    $new_data[$id]['field_locations'][] = [
      'id' => $item['ServiceItemId'],
      'type' => 'service',
      'date' => $item['ServiceDate'] . ' ' . $item['ServiceTime'],
    ];
  }
}

/**
 * Photos.
 */
$key_id = 'Id';
$source = FOLDER . 'photos.csv';

// Get the base file.
$csvsource = getCSV($source, $key_id);
$data = $csvsource['data'];
$header = $csvsource['header'];
foreach ($new_data as $id => $item) {
  $entries = [];
  foreach ($data as $photo) {
    if ($photo['DeathRecordId'] == $id) {
      $entries[] = $id . '/' . $photo['Name'];
    }
  }
  $new_data[$id]['field_gallery_image'] = serialize($entries);
}

/**
 * Comments.
 */
$key_id = 'RegisterBookItemId';
$source = FOLDER . 'comments.csv';

// Get the base file.
$csvsource = getCSV($source, $key_id);
$data = $csvsource['data'];
$header = $csvsource['header'];
foreach ($new_data as $id => $item) {
  $entries = [];
  foreach ($data as $comment) {
    if ($comment['DeathRecordId'] == $id) {
      $entries[] = $comment;
    }
  }
  $new_data[$id]['comments'] = serialize($entries);
}

/**
 * Locations.
 */
$key_id = 'LocationId';
$source = FOLDER . 'locations.csv';

// Get the base file.
$csvsource = getCSV($source, $key_id);
$data = $csvsource['data'];
$header = $csvsource['header'];
foreach ($new_data as $id => $item) {
  $entries = [];
  foreach ($data as $location) {
    foreach ($item['field_locations'] as $key => $obit_location) {
      if ($location['LocationId'] == $obit_location['id']) {
        $new_data[$id]['field_locations'][$key]['data'] = $location;
      }
    }
  }
  $new_data[$id]['field_locations'] = serialize($new_data[$id]['field_locations']);
}

array_unshift($new_data, $new_header);
putCSV($output_file_name, $new_data);

print 'Obits extract complete.' . "\n";
exit;
