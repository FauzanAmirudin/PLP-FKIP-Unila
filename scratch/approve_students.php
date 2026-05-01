<?php
define('GF_BASE_PATH', __DIR__);
require 'application/config/db.php';

$db = $gf_db['default'];
$conn = new mysqli($db['server'], $db['username'], $db['password'], $db['database']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Checking student registration status...\n";
$result = $conn->query("SELECT datamahasiswa.NAMA, databerkas.ID as BERKASID, datastatus.STATUSBERKAS 
                        FROM datamahasiswa 
                        LEFT JOIN databerkas ON datamahasiswa.USRKEY = databerkas.USRKEY 
                        LEFT JOIN datastatus ON databerkas.ID = datastatus.BRKSKEY");

while($row = $result->fetch_assoc()) {
    echo "Student: " . $row['NAMA'] . " | Status: " . ($row['STATUSBERKAS'] ?? 'NULL') . "\n";
    if ($row['STATUSBERKAS'] != 'Disetujui' && $row['BERKASID']) {
        echo "Approving...\n";
        $berkasId = $row['BERKASID'];
        $conn->query("INSERT INTO datastatus (BRKSKEY, STATUSBERKAS, DATEVALID) VALUES ('$berkasId', 'Disetujui', NOW()) ON DUPLICATE KEY UPDATE STATUSBERKAS = 'Disetujui', DATEVALID = NOW()");
    }
}

$conn->close();
