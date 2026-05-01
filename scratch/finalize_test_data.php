<?php
define('GF_BASE_PATH', __DIR__);
require 'application/config/db.php';

$db = $gf_db['default'];
$conn = new mysqli($db['server'], $db['username'], $db['password'], $db['database']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$year = '2026';
$periode = 'Periode 1';

echo "Ensuring databerkas and approval for all students...\n";
$students = $conn->query("SELECT USRKEY, NPM, NAMA FROM datamahasiswa");

while($student = $students->fetch_assoc()) {
    $usrkey = $student['USRKEY'];
    
    // Ensure databerkas
    $checkBerkas = $conn->query("SELECT ID FROM databerkas WHERE USRKEY = '$usrkey'");
    if ($checkBerkas->num_rows == 0) {
        echo "Creating databerkas for " . $student['NAMA'] . "...\n";
        $conn->query("INSERT INTO databerkas (USRKEY, TAHUNDAFTAR, PERIODEDAFTAR, TIMECREATE) VALUES ('$usrkey', '$year', '$periode', NOW())");
        $berkasId = $conn->insert_id;
    } else {
        $berkasId = $checkBerkas->fetch_assoc()['ID'];
    }
    
    // Ensure datastatus Approved
    echo "Approving " . $student['NAMA'] . "...\n";
    $conn->query("INSERT INTO datastatus (BRKSKEY, STATUSBERKAS, DATEVALID) VALUES ('$berkasId', 'Disetujui', NOW()) ON DUPLICATE KEY UPDATE STATUSBERKAS = 'Disetujui', DATEVALID = NOW()");
}

$conn->close();
