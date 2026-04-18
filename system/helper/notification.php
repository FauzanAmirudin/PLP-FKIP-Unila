<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
|  
|--------------------------------------------------------------------------
|
| 
|
*/
if (!function_exists("save_notification")) {
	function save_notification($notif)
	{
		/* get date and time */
		if (!isset($_SESSION['notification'])) $_SESSION['notification'] = array();
		array_push($_SESSION['notification'], $notif);
		return TRUE;
	}
}

if (!function_exists("get_notification")) {
	function get_notification()
	{
		/* get date and time */
		if (isset($_SESSION['notification'])) {
			$notif = $_SESSION['notification'];
			unset($_SESSION['notification']);
			return $notif;
		} else return array();
	}
}
