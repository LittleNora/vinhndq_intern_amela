<?php
$current = file_get_contents('log.txt');

$file = fopen('log.txt', 'w');
//fwrite($file, $_SERVER['REMOTE_ADDR'] . ' ' . date('Y-m-d H:i:s') . "\n");


//echo $current;
//
fwrite($file, $_SERVER['REMOTE_ADDR'] . ' ' . date('Y-m-d H:i:s') . "\n" . $current);

fclose($file);


echo 'success';
//echo file_get_contents('log.txt');
