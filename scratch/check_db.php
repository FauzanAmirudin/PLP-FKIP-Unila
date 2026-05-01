<?php
define('GF_BASE_PATH', __DIR__);
require 'application/config/db.php';

$db = $gf_db['default'];
$conn = new mysqli($db['server'], $db['username'], $db['password'], $db['database']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function show_columns($conn, $table) {
    echo "\nColumns in $table:\n";
    $result = $conn->query("SHOW COLUMNS FROM $table");
    while($row = $result->fetch_assoc()) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
}

show_columns($conn, 'dosen');
show_columns($conn, 'datapenempatan');
show_columns($conn, 'datamahasiswa');
show_columns($conn, 'users');

$conn->close();
