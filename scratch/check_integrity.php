<?php
define('GF_BASE_PATH', __DIR__);
require 'application/config/db.php';
$db = $gf_db['default'];
$conn = new mysqli($db['server'], $db['username'], $db['password'], $db['database']);

echo "Databerkas check:\n";
$res = $conn->query("SELECT * FROM databerkas");
while($r = $res->fetch_assoc()) {
    print_r($r);
}

echo "\nLaporan check (distinct BRKSKEY):\n";
$res = $conn->query("SELECT DISTINCT BRKSKEY FROM laporan");
while($r = $res->fetch_assoc()) {
    print_r($r);
}
$conn->close();
