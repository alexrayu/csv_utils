<?php

$src = file_get_contents('src.txt');
preg_match_all( '/(?<=AID=)\d+/', $src, $matches);
$items = array_unique($matches[0]);
$res = implode(', ', $items);
file_put_contents('res.txt', $res);
