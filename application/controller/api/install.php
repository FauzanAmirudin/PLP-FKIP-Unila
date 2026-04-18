<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
$report	=  "Welcome to istaller set.";
//Connection between a database and php
$mysql 	= new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
/* check connection */
if ($mysql->connect_errno) {
	printf("Connect failed: %s\n", $mysql->connect_error);
	exit();
}
if (isset($database['tabel'])) {
	$report	.= '<h4>Creating tabel...</h4>';
	$report	.= '<ul>';
	$count 	= 0;
	foreach ($database['tabel'] as $tname => $tquery) {
		$report	.= '<li>';
		$report	.= "Create tabel " . $tname . ": ";
		$result = $mysql->query("SHOW TABLES LIKE '" . $tname . "'");
		if ($result->num_rows === 0) {
			$mysql->query($tquery);
			$count++;
			$report	.=  "Tabel created!";
		} else {
			$report	.=  "Tabel already exist!";
		}
		$report	.= '</li>';
	}
	$report	.= "</ul>";
	if (isset($database['data'])) {
		if ($count === count($database['tabel'])) {
			$report	.= '<h4>Populating tabel</h4>';
			$report	.= "Insert data...<br>";
			$num		= 0;
			foreach ($database['data'] as $key => $value) {
				set_time_limit(20);
				$mysql->query($value);
				$num++;
			}
			$report	.= $num . " data inserted!";
		}
	}
	$mysql->close();
	$report	.=  "<h4>Cleaning</h4>";
	unset($mysql, $value, $key, $result, $count);
	$report	.=  "<a>Rewriting configuration: </a>";
	$config_smpl = file_get_contents(GF_GONFIG_PATH . DIRECTORY_SEPARATOR . "db.tmt");
	$config_smpl = str_replace("		'tabel'		=> array({{tabelSQL}}		),\n", '', $config_smpl);
	$config_smpl = str_replace("		'data'		=> array({{dataTabelSQL}}		),\n", '', $config_smpl);
	$result = file_put_contents(GF_GONFIG_PATH . DIRECTORY_SEPARATOR . 'database.php', $config_smpl);
	$report	.=  " done!<br>";
	// unlink(GF_GONFIG_PATH . DIRECTORY_SEPARATOR . "db.tmt");
	// unlink(GF_CONTROLLER_PATH . DIRECTORY_SEPARATOR . "compile.php");
	// unlink(GF_CONTROLLER_PATH . DIRECTORY_SEPARATOR . "install.php");
	$report	.=  "<br>Installation done!";
} else {
	$report	.=  "<br>Installation already done!";
}
echo $report;