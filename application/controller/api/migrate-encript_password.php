<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */
$forceUpdate = FALSE;
$limit = 0;

$report	=  "<h4>Welcome to migrate encript password set.</h4>";
$userAccess = new SQL("user");
$updateTabel1 = $userAccess->drop_colum("sss")->alter_tabel();
$last_error1 = $userAccess->last_error;
$updateTabel2 = $userAccess->reset()->add_column("PASSWORD", "varchar(225)", "NULL", "USERID")->alter_tabel();
$last_error2 = $userAccess->last_error;

if ($updateTabel1 || $updateTabel2) {
	$report .= "Tabel updated<br>";
}
if ($last_error1 == "Can't DROP 'sss'; check that column/key exists" && $last_error2 == "Duplicate column name 'PASSWORD'") {
	$report .= "Tabel ready<br>";
} else {
	$report .= "Somethink wrong!!<br>";
	$report .= $last_error1 . "<br>";
	$report .= $last_error2 . "<br>";
}
$report .= "<br/>";
$users = $userAccess->reset()->result_array();
$n = 0;
foreach ($users as $user) {
	if ($n == $limit && $limit > 0) break;
	$n++;
	$report .= "<b>" . $user["USERID"] . "</b><br>";
	if (empty($user["PASSWORD"]) || $forceUpdate == TRUE) {
		if ($forceUpdate) $report .= "Password already set, FORCE UPDATE!<br/>";
		$encryption = str_encrypt(htmlspecialchars($user["PASS"]));
		$report .= "Set password to: \"" . $encryption . "\"</br>";
		$report .= "from: \"" . str_decrypt($encryption) . "\"</br>";
		$data = array('PASSWORD' => $encryption);
		$update_user = $userAccess->reset()->where(["ID" => $user["ID"]])->update($data);
		if ($update_user == FALSE) {
			echo "Somethink wrong, cancel migration";
			break;
		}
	} else {
		$report .= "Password already set!<br/>";
	}
	# code...
}
echo $report;
