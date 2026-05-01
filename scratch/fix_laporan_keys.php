<?php
define('GF_BASE_PATH', __DIR__);
require 'application/config/db.php';
$db = $gf_db['default'];
$conn = new mysqli($db['server'], $db['username'], $db['password'], $db['database']);

echo "Fixing BRKSKEY in laporan table...\n";
$res = $conn->query("SELECT ID, USRKEY FROM databerkas");
while($r = $res->fetch_assoc()) {
    $berkasId = $r['ID'];
    $usrkey = $r['USRKEY'];
    echo "Updating reports for USRKEY $usrkey with BRKSKEY $berkasId...\n";
    $conn->query("UPDATE laporan SET BRKSKEY = '$berkasId' WHERE USRKEY = '$usrkey'");
}

$conn->close();
