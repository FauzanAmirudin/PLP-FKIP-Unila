<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */	
$include_data 		= FALSE;
$exclude_data_tabel	= [];

$report	= "Welcome to compile test.";

$databaseAccess = new SQL();
$tabels = $databaseAccess->result_array("SHOW TABLES");
krsort($tabels);
$report .= "<h4>Collect all tabel.</h4>";
$sqlTabel = "\n";
$sqlData = "\n";
$error = '';
$report	.= '<ul>';
foreach ($tabels as $key => $tabel) {
	$tabel = end($tabel);
	$report .= "<li>Collect tabel " . $tabel . ":&nbsp;";
	$sqlQuery = $databaseAccess->reset()->result_array("SHOW CREATE TABLE " . $tabel);
	if ($sqlQuery !== FALSE) {
		$sqlQuery = preg_replace("/\s\s/", " ", preg_replace("/\s(AUTO_INCREMENT=)\d{1,5}\s/", " ", preg_replace("/\n/", "", end($sqlQuery)["Create Table"])));
		$sqlTabel .= "			\"" . $tabel . "\" => \"" . $sqlQuery . "\",\n";
		$report .= "Ok!</li>";
	} else {
		if (empty($error)) $error .= "<br>";
		$report .= "Fail!</li>";
		$error .= "Error accur when request query for table " . $tabel . ".";
	}
	if (!$include_data) continue;
	$dataTabel = $databaseAccess->reset()->result_array("SELECT * FROM `" . $tabel . "`");
	foreach ($dataTabel as $row) {
		$insertQuery = new SQL($tabel);
		$sqlQuery = $insertQuery->insert($row, TRUE);
		if (in_array($tabel, $exclude_data_tabel)) {
			$sqlQuery = preg_replace("/\s\s/", " ", preg_replace("/\n/", " ", $sqlQuery));
			$sqlData .= "			\"" . $sqlQuery . "\",\n";
		}
	}
}
$report .= "</ul>";
if ($error === '') {
	$config_smpl = file_get_contents(GF_GONFIG_PATH . DIRECTORY_SEPARATOR . "db.tmt");
	$config_smpl = str_replace("{{tabelSQL}}", $sqlTabel, $config_smpl);
	$config_smpl = str_replace("{{dataTabelSQL}}", $sqlData, $config_smpl);
	$result = file_put_contents(GF_GONFIG_PATH . DIRECTORY_SEPARATOR . 'database.php', $config_smpl);

}
if ($result == TRUE) {
	$report .= "Tabel cofiguration colected!";
} else {
	$report .= "Failed put cofiguration into the file!";
}
echo $report;
echo $error;
