<?php
define('GF_BASE_PATH', __DIR__);
require 'application/config/db.php';

$db = $gf_db['default'];
$conn = new mysqli($db['server'], $db['username'], $db['password'], $db['database']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Finding DPL...\n";
$result = $conn->query("SELECT users.ID, users.USERID, dosen.NAMADOSEN, dosen.NIPDOSEN FROM users JOIN dosen ON users.ID = dosen.USRKEY WHERE users.STAT = 'DPL' LIMIT 1");
$dpl = $result->fetch_assoc();

if ($dpl) {
    print_r($dpl);
    
    echo "\nFinding all students...\n";
    $students = $conn->query("SELECT USRKEY, NPM, NAMA FROM datamahasiswa");
    
    $count = 0;
    while($student = $students->fetch_assoc()) {
        $usrkey = $student['USRKEY'];
        $npm = $student['NPM'];
        
        // Check if already in datapenempatan
        $check = $conn->query("SELECT ID FROM datapenempatan WHERE USRKEY = '$usrkey' OR NPMPESERTA = '$npm'");
        if ($check->num_rows > 0) {
            // Update
            $conn->query("UPDATE datapenempatan SET DPLUSRKEY = '{$dpl['ID']}', NIPDPL = '{$dpl['NIPDOSEN']}', NAMADPL = '{$dpl['NAMADOSEN']}', TIMEUPDATE = NOW() WHERE USRKEY = '$usrkey' OR NPMPESERTA = '$npm'");
        } else {
            // Insert
            $conn->query("INSERT INTO datapenempatan (USRKEY, DPLUSRKEY, NPMPESERTA, NAMADPL, NIPDPL, TIMECREATE) VALUES ('$usrkey', '{$dpl['ID']}', '$npm', '{$dpl['NAMADOSEN']}', '{$dpl['NIPDOSEN']}', NOW())");
        }
        $count++;
    }
    echo "\nProcessed $count students.\n";
} else {
    echo "No DPL found.\n";
}

$conn->close();
