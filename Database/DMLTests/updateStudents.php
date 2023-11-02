<?php
$mysqli = new \Database\MySQLWrapper();

$updates = [
    ['Alice', 'Physics'],
    ['Bob', 'Art History'],
    ['Charlie', 'Philosophy'],
    ['David', 'Economics']
];

foreach ($updates as $update) {
    $updateQuery = "UPDATE students SET major='$update[1]' WHERE name='$update[0]'";
    $mysqli->query($updateQuery);
}

// 更新されたレコードを表示します
$selectQuery = "SELECT * FROM students";
$result = $mysqli->query($selectQuery);
while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . ", Name: " . $row['name'] . ", Age: " . $row['age'] . ", Major: " . $row['major'] . "\n";
}
