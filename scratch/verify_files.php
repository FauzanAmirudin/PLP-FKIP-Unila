<?php
define('GF_BASE_PATH', __DIR__);
require 'application/config/db.php';
$db = $gf_db['default'];
$conn = new mysqli($db['server'], $db['username'], $db['password'], $db['database']);
$result = $conn->query("SELECT * FROM laporan LIMIT 5");
while($row = $result->fetch_assoc()) {
    print_r($row);
    $path = GF_BASE_PATH . DIRECTORY_SEPARATOR . $row['FILEPATH'] . $row['FILENAME'] . $row['FILEEXT'];
    echo "Full path: " . $path . "\n";
    echo "Exists: " . (file_exists($path) ? "YES" : "NO") . "\n\n";
}
$conn->close();
