<?php
define('GF_BASE_PATH', __DIR__);
require 'application/config/db.php';

$db = $gf_db['default'];
$conn = new mysqli($db['server'], $db['username'], $db['password'], $db['database']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Users table preview:\n";
$result = $conn->query("SELECT * FROM users LIMIT 5");
while($row = $result->fetch_assoc()) {
    print_r($row);
}

echo "\nDosen table preview:\n";
$result = $conn->query("SELECT * FROM dosen LIMIT 5");
while($row = $result->fetch_assoc()) {
    print_r($row);
}

$conn->close();
