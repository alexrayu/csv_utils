<?php

$src = file_get_contents('src.txt');
preg_match_all( '/http.+jpg/', $src, $matches);
$items = $matches[0];
$total = count($items);
$i = 0;
foreach ($items as $item) {
  $i++;
  print_progress('Getting images', $i, $total);
  $name = pathinfo($item)['basename'];
  $contents = file_get_contents($item);
  file_put_contents('img/' . $name, $contents);
}

function print_progress($message, $cur, $max) {
  printf("\r%s %6.0f%% (%s/%s).          ", $message, ($cur / $max) * 100, $cur, $max);
}
