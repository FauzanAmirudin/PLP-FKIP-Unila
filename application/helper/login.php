<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');

if (!function_exists("is_login")) {
	function is_login()
	{
		if (!empty(session_get('LOGIN')) && session_get('LOGIN') === TRUE) {
			disable_maintenance_banner();
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

if (!function_exists("require_login")) {
	function require_login($code = '')
	{
		if (!is_login()) {
			if (empty($code)) {
				save_notification("Maaf anda harus login untuk dapat mengakses.");
				redirect();
			} else error_page('505');
		}
	}
}

if (!function_exists("is_level")) {
	function is_level($levels)
	{
		$levels = explode(", ", $levels);
		$levels = array_map(function ($level) {
			return strtolower(trim($level));
		}, $levels);
		if (!empty(session_get('LEVEL')) && in_array(strtolower(session_get('LEVEL')), $levels)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

if (!function_exists("require_level")) {
	function require_level($levels)
	{
		if (!is_level($levels)) {
			save_notification("Maaf permintaan anda ditolak, anda tidak memiliki izin yang cukup.");
			redirect('user/dashboard');;
		}
	}
}

if (!function_exists("login")) {
	function login(int $id, string $level)
	{
		session_save('LOGIN', TRUE);
		session_save('ID', $id);
		session_save('LEVEL', $level);
		return TRUE;
	}
}


if (!function_exists("login_data")) {
	function login_data()
	{
		return array(
			"ID" => session_get('ID'),
			"LEVEL" => session_get('LEVEL')
		);
	}
}

if (!function_exists("logout")) {
	function logout()
	{
		session_unset();
		session_destroy();
		setcookie(session_name(), '', 0, '/');
		redirect();
	}
}
