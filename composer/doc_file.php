<?php
$file = fopen("error.log", "r") or die("Unable to open file!");

$content = fread($file, filesize("error.log"));

fclose($file);

echo nl2br($content);

echo '<br/>';

echo "<pre>";
var_dump(explode("\n", $content));