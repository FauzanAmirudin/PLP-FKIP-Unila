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
if (!function_exists("input_name")) {
	function input_name($key = "", $type = FALSE)
	{
		$param = 'name = "' . $key . '"';
		if (strtoupper($type) == "GET") {
			$input = !empty($_GET[$key]) ? ' value="' . $_GET[$key] . '"' : "";
		}
		if (strtoupper($type) == "POST" || $type === FALSE) {
			$input = !empty($_POST[$key]) ? 'value="' . $_POST[$key] . '"' : "";
		}
		return $param . $input;
	}
}
if (!function_exists("select_name")) {
	function select_name($key, $value = "", $return = FALSE)
	{
		static $save = [];
		if ($return === TRUE) {
			return isset($save[$key]) ? $save[$key] : NULL;
		} else {
			$param = 'name = "' . $key . '"';
			if (!empty($value)) {
				$save[$key] = $value;
			}
			return $param;
		}
	}
}
if (!function_exists("select_option")) {
	function select_option($id, $label = "", $value = TRUE)
	{
		$input = select_name($id, "", TRUE);
		if (empty($input)) {
			$input = isset($_POST[$id]) ? $_POST[$id] : $input;
			$input = isset($_GET[$id]) ? $_GET[$id] : "";
		}
		$value = $value === TRUE ? $label : $value;
		$input = $value == $input ? "selected" : "";
		$display = $value === FALSE ? 'style="display:none;"' : '';
		return '<option value="' . $value . '" ' . $display . ' ' . $input . '>' . $label . '</option>';
	}
}
if (!function_exists("input_value")) {
	function input_value(&$var, $key = "")
	{
		if (isset($var[$key])) {
			echo !empty($var[$key]) ? 'value="' . $var[$key] . '"' : "";
		}
	}
}
if (!function_exists("form_imput_value")) {
	function form_imput_value(&$var, $key = "")
	{
		if (isset($var[$key])) {
			return !empty($var[$key]) ? $var[$key] : "";
		}
	}
}
