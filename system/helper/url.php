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
if (!function_exists("set_url")) {
	function set_url($url = '')
	{
		return empty($url) ? GF_CONFIG['base_url'] . "/" : GF_CONFIG['base_url'] . "/?page=" . ltrim($url, '/');
	}
}
if (!function_exists("redirect")) {
	function redirect($url = '', $permanent = false)
	{
		session_write_close();
		$targetUrl = empty($url) ? GF_CONFIG['base_url'] . "/" : GF_CONFIG['base_url'] . "/?page=" . ltrim($url, '/');
		header('Location: ' . $targetUrl, true, $permanent ? 301 : 302);
		exit();
	}
}
