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
// date_default_timezone_set($timezone);
if (!function_exists("now")) {
	function now()
	{
		/* get date and time */
		$now = new DateTime('now');
		return $now->format('H:i:s d-m-y');
	}
}
if (!function_exists("full_time")) {
	function full_time()
	{
		$now = new DateTime('now');
		$hour = $now->format('H');
		$minute = $now->format('i');
		$second = $now->format('s');
		$day = $now->format('d');
		$month = $now->format('m');
		$year = $now->format('Y');
		return $hour . ':' . $minute . ':' . $second . ' ' . $day . '/' . $month . '/' . $year;
	}
}
if (!function_exists("time")) {
	function time()
	{
		$now = new DateTime('now');
		$hour = $now->format('H');
		$minute = $now->format('i');
		$second = $now->format('s');
		return $hour . ':' . $minute . ':' . $second;
	}
}
if (!function_exists("date")) {
	function date()
	{
		$now = new DateTime('now');
		$day = $now->format('d');
		$month = $now->format('m');
		$year = $now->format('Y');
		return $day . '/' . $month . '/' . $year;
	}
}
if (!function_exists("day")) {
	function day()
	{
		$now = new DateTime('now');
		return $now->format('d');
	}
}
if (!function_exists("month")) {
	function month()
	{
		$now = new DateTime('now');
		return $now->format('m');
	}
}
if (!function_exists("year")) {
	function year()
	{
		$now = new DateTime('now');
		return $now->format('Y');
	}
}
