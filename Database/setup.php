<?php

use Database\MySQLWrapper;

$mysqli = new MySQLWrapper();

$setupDir = __DIR__ . '/Examples/';
$setupFiles = scandir($setupDir);

foreach(array_reverse(array_slice($setupFiles, 2)) as $file){
    $result = $mysqli->query(file_get_contents($setupDir . $file));

    if ($result === false) throw new Exception('Could not execute query.');
    else print("Successfully ran all SQL setup queries." . PHP_EOL);
}


