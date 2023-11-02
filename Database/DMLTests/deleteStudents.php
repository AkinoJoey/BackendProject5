<?php
$mysqli = new \Database\MySQLWrapper();

$studentsToDelete = ['Alice', 'Bob', 'Charlie'];

foreach ($studentsToDelete as $studentName) {
    $deleteQuery = "DELETE FROM students WHERE name='$studentName'";
    $mysqli->query($deleteQuery);
}

// 残りのレコードを表示します
$selectQuery = "SELECT * FROM students";
$result = $mysqli->query($selectQuery);
while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . ", Name: " . $row['name'] . ", Age: " . $row['age'] . ", Major: " . $row['major'] . "\n";
}
