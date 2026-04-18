<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/**
 *	
 */

$report	=  "<h4>Welcome to migrate dencript password set.</h4>";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$userAccess = new SQL("user");
	$npm = strip_tags($_POST['npm']);
	$user = $userAccess->reset()->where(["USERID" => $npm])->result_row_array();
	$report .= "from: \"" . str_decrypt($user['PASSWORD']) . "\"</br>";
}
$report .= '<input name="mpm" value="" placeholder="Masukan id Akun Anda" type="text" required="required"/>';
echo $report;