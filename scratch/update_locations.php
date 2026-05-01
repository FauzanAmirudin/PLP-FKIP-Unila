<?php
define('GF_BASE_PATH', __DIR__);
require 'application/config/db.php';

$db = $gf_db['default'];
$conn = new mysqli($db['server'], $db['username'], $db['password'], $db['database']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Updating dummy locations for students...\n";
$conn->query("UPDATE datapenempatan SET LOKASIDESA = 'Sukarame', LOKASISEKOLAH = 'SMAN 1 Bandar Lampung' WHERE LOKASIDESA IS NULL OR LOKASIDESA = '' OR LOKASIDESA = 'No Lokasi Found'");

$conn->close();
