<?php
$file = fopen('log.txt', 'r');
echo nl2br(fread($file, filesize('log.txt')));
fclose($file);