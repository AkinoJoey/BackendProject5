<?php
$mysqli = new \Database\MySQLWrapper();

$selectQuery = "SELECT * FROM students";
$result = $mysqli->query($selectQuery);

while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . ", Name: " . $row['name'] . ", Age: " . $row['age'] . ", Major: " . $row['major'] . "\n";
}
