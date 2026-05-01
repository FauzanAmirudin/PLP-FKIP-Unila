<?php
define('GF_BASE_PATH', dirname(__DIR__));
require 'application/config/db.php';
$db = $gf_db['default'];
$conn = new mysqli($db['server'], $db['username'], $db['password'], $db['database']);
$res = $conn->query("SELECT FILELINK FROM laporan LIMIT 1");
$row = $res->fetch_assoc();
$link = $row['FILELINK'];
$relativeFilePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $link);
$fullFilePath = GF_BASE_PATH . DIRECTORY_SEPARATOR . $relativeFilePath;
echo "Link: " . $link . "\n";
echo "Full Path: " . $fullFilePath . "\n";
echo "Exists: " . (file_exists($fullFilePath) ? "YES" : "NO") . "\n";
$conn->close();
